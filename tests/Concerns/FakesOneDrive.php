<?php

namespace Tests\Concerns;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

trait FakesOneDrive
{
    protected function fakeOneDrive(): void
    {
        config([
            'services.onedrive.tenant_id' => 'tenant-test',
            'services.onedrive.client_id' => 'client-test',
            'services.onedrive.client_secret' => 'secret-test',
            'services.onedrive.user' => 'archivo@example.edu',
            'services.onedrive.folder' => 'SistemaConvocatoria',
        ]);
        $uploadedFiles = 0;
        Http::preventStrayRequests();
        Http::fake(function (Request $request) use (&$uploadedFiles) {
            $url = $request->url();

            if (str_starts_with($url, 'https://login.microsoftonline.com/')) {
                return Http::response([
                    'access_token' => 'test-token',
                    'expires_in' => 3600,
                ]);
            }

            if (str_contains($url, '/root:/SistemaConvocatoria')) {
                return Http::response(['id' => 'folder-test']);
            }

            if ($request->method() === 'PUT' && str_contains($url, '/content')) {
                $uploadedFiles++;

                return Http::response(['id' => 'onedrive-item-'.$uploadedFiles], 201);
            }

            if ($request->method() === 'GET' && str_ends_with($url, '/content')) {
                return Http::response('%PDF-1.4 test document', 200, [
                    'Content-Type' => 'application/pdf',
                ]);
            }

            if ($request->method() === 'DELETE') {
                return Http::response('', 204);
            }

            return Http::response(['error' => 'Unexpected OneDrive test request.'], 500);
        });
    }
}
