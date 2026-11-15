<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class OneDriveDocumentStorage
{
    public const DISK = 'onedrive';

    public function store(UploadedFile $file, string $filename): string
    {
        $contents = $file->get();

        if ($contents === false || $contents === '') {
            throw new RuntimeException('El PDF seleccionado no contiene datos para subir.');
        }

        return $this->uploadContent($contents, $filename);
    }

    public function uploadContent(string $contents, string $filename): string
    {
        $user = rawurlencode($this->requiredConfig('user'));
        $folderId = rawurlencode($this->folderId());
        $filename = rawurlencode($filename);
        $url = "https://graph.microsoft.com/v1.0/users/{$user}/drive/items/{$folderId}:/{$filename}:/content";

        $response = $this->graphRequest()
            ->withBody($contents, 'application/pdf')
            ->put($url)
            ->throw();

        $itemId = $response->json('id');

        if (! is_string($itemId) || $itemId === '') {
            throw new RuntimeException('Microsoft Graph no devolvió el identificador del documento subido.');
        }

        return $itemId;
    }

    public function response(string $disk, string $path, string $filename): Response
    {
        $headers = [
            'Content-Type' => 'application/pdf',
            'X-Content-Type-Options' => 'nosniff',
            'Content-Disposition' => 'inline; filename="'.Str::ascii($filename).'"',
            'Cache-Control' => 'private, no-store',
        ];

        if ($disk === 'local') {
            $localDisk = Storage::disk('local');
            abort_unless($localDisk->exists($path), 404);

            return response()->file($localDisk->path($path), $headers)->setPrivate();
        }

        if ($disk !== self::DISK) {
            throw new RuntimeException('El expediente utiliza un proveedor de almacenamiento no compatible.');
        }

        $user = rawurlencode($this->requiredConfig('user'));
        $itemId = rawurlencode($path);
        $url = "https://graph.microsoft.com/v1.0/users/{$user}/drive/items/{$itemId}/content";
        $download = $this->graphRequest()->get($url);

        if ($download->notFound()) {
            abort(404);
        }

        $contents = $download->throw()->body();

        return response($contents, 200, $headers);
    }

    public function delete(string $disk, string $path): bool
    {
        if ($disk === 'local') {
            return Storage::disk('local')->delete($path);
        }

        if ($disk !== self::DISK) {
            throw new RuntimeException('El expediente utiliza un proveedor de almacenamiento no compatible.');
        }

        $user = rawurlencode($this->requiredConfig('user'));
        $itemId = rawurlencode($path);
        $url = "https://graph.microsoft.com/v1.0/users/{$user}/drive/items/{$itemId}";
        $response = $this->graphRequest()->delete($url);

        if ($response->notFound()) {
            return true;
        }

        $response->throw();

        return true;
    }

    private function folderId(): string
    {
        $cacheKey = $this->cacheKey('folder');

        return Cache::store('file')->remember($cacheKey, now()->addHours(6), function (): string {
            $user = rawurlencode($this->requiredConfig('user'));
            $folderName = rawurlencode($this->requiredConfig('folder'));
            $baseUrl = 'https://graph.microsoft.com/v1.0/users/'.$user.'/drive';
            $folderUrl = $baseUrl.'/root:/'.$folderName;
            $existingFolder = $this->graphRequest()->get($folderUrl);

            if ($existingFolder->successful()) {
                return $this->itemIdFromResponse($existingFolder->json(), 'la carpeta de documentos');
            }

            if (! $existingFolder->notFound()) {
                $existingFolder->throw();
            }

            $createdFolder = $this->graphRequest()
                ->post($baseUrl.'/root/children', [
                    'name' => $this->requiredConfig('folder'),
                    'folder' => (object) [],
                    '@microsoft.graph.conflictBehavior' => 'fail',
                ]);

            if ($createdFolder->status() === 409) {
                $existingFolder = $this->graphRequest()->get($folderUrl)->throw();

                return $this->itemIdFromResponse($existingFolder->json(), 'la carpeta de documentos');
            }

            $createdFolder->throw();

            return $this->itemIdFromResponse($createdFolder->json(), 'la carpeta creada');
        });
    }

    private function itemIdFromResponse(mixed $payload, string $description): string
    {
        $itemId = is_array($payload) ? ($payload['id'] ?? null) : null;

        if (! is_string($itemId) || $itemId === '') {
            throw new RuntimeException("Microsoft Graph no devolvió el identificador de {$description}.");
        }

        return $itemId;
    }

    private function accessToken(): string
    {
        $tenantId = $this->requiredConfig('tenant_id');
        $clientId = $this->requiredConfig('client_id');
        $cacheKey = $this->cacheKey('access-token');
        $cache = Cache::store('file');
        $cachedToken = $cache->get($cacheKey);

        if (is_string($cachedToken) && $cachedToken !== '') {
            return $cachedToken;
        }

        $response = Http::asForm()
            ->connectTimeout(5)
            ->timeout(15)
            ->post("https://login.microsoftonline.com/{$tenantId}/oauth2/v2.0/token", [
                'grant_type' => 'client_credentials',
                'client_id' => $clientId,
                'client_secret' => $this->requiredConfig('client_secret'),
                'scope' => 'https://graph.microsoft.com/.default',
            ])
            ->throw();

        $token = $response->json('access_token');

        if (! is_string($token) || $token === '') {
            throw new RuntimeException('Microsoft Entra no devolvió un token de acceso para OneDrive.');
        }

        $expiresIn = $response->json('expires_in');
        $cacheSeconds = is_numeric($expiresIn) ? max(1, (int) $expiresIn - 60) : 300;
        $cache->put($cacheKey, $token, now()->addSeconds($cacheSeconds));

        return $token;
    }

    private function graphRequest(): PendingRequest
    {
        return Http::withToken($this->accessToken())
            ->connectTimeout(5)
            ->timeout(60);
    }

    private function requiredConfig(string $key): string
    {
        $value = config('services.onedrive.'.$key);

        if (! is_string($value) || trim($value) === '') {
            throw new RuntimeException('Falta configurar services.onedrive.'.$key.' para usar OneDrive.');
        }

        return trim($value);
    }

    private function cacheKey(string $purpose): string
    {
        return 'onedrive:'.hash('sha256', implode('|', [
            config('services.onedrive.tenant_id'),
            config('services.onedrive.client_id'),
            config('services.onedrive.user'),
            config('services.onedrive.folder'),
            hash('sha256', (string) config('services.onedrive.client_secret')),
            $purpose,
        ]));
    }
}
