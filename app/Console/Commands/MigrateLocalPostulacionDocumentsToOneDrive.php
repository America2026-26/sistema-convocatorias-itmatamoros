<?php

namespace App\Console\Commands;

use App\Models\Postulacion;
use App\Models\PostulacionDocumento;
use App\Services\OneDriveDocumentStorage;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

#[Signature('postulaciones:migrate-local-documents-to-onedrive')]
#[Description('Migra los documentos locales de postulaciones a OneDrive y elimina el original local tras verificarlo.')]
class MigrateLocalPostulacionDocumentsToOneDrive extends Command
{
    public function handle(OneDriveDocumentStorage $oneDrive): int
    {
        $migrated = 0;
        $failures = 0;

        PostulacionDocumento::query()
            ->where('storage_disk', PostulacionDocumento::STORAGE_DISK_LOCAL)
            ->orderBy('id')
            ->chunkById(100, function ($documents) use ($oneDrive, &$migrated, &$failures): void {
                foreach ($documents as $documento) {
                    if (! Storage::disk('local')->exists($documento->path)) {
                        $this->error("No se encontró el archivo local del documento #{$documento->id}.");
                        $failures++;

                        continue;
                    }

                    $localPath = $documento->path;
                    $fileName = "postulacion-{$documento->postulacion_id}-documento-{$documento->id}-migrado.pdf";
                    $itemId = $oneDrive->uploadContent(
                        Storage::disk('local')->get($localPath),
                        $fileName,
                    );

                    $documento->update([
                        'path' => $itemId,
                        'storage_disk' => PostulacionDocumento::STORAGE_DISK_ONEDRIVE,
                    ]);

                    if (! Storage::disk('local')->delete($localPath)) {
                        $this->error("El documento #{$documento->id} se migró a OneDrive, pero no se pudo eliminar la copia local '{$localPath}'.");
                        $failures++;

                        continue;
                    }

                    $migrated++;
                }
            });

        Postulacion::query()
            ->where('documento_storage_disk', Postulacion::DOCUMENT_STORAGE_DISK_LOCAL)
            ->whereNotNull('documento_path')
            ->where('documento_path', '<>', '')
            ->orderBy('id')
            ->chunkById(100, function ($postulaciones) use ($oneDrive, &$migrated, &$failures): void {
                foreach ($postulaciones as $postulacion) {
                    if (! Storage::disk('local')->exists($postulacion->documento_path)) {
                        $this->error("No se encontró el PDF local de la postulación #{$postulacion->id}.");
                        $failures++;

                        continue;
                    }

                    $localPath = $postulacion->documento_path;
                    $fileName = "postulacion-{$postulacion->id}-expediente-migrado.pdf";
                    $itemId = $oneDrive->uploadContent(
                        Storage::disk('local')->get($localPath),
                        $fileName,
                    );

                    $postulacion->update([
                        'documento_path' => $itemId,
                        'documento_storage_disk' => Postulacion::DOCUMENT_STORAGE_DISK_ONEDRIVE,
                    ]);

                    if (! Storage::disk('local')->delete($localPath)) {
                        $this->error("El expediente #{$postulacion->id} se migró a OneDrive, pero no se pudo eliminar la copia local '{$localPath}'.");
                        $failures++;

                        continue;
                    }

                    $migrated++;
                }
            });

        $this->info("Documentos migrados: {$migrated}.");

        if ($failures > 0) {
            $this->error("Se detectaron {$failures} problema(s); revisa los archivos indicados y elimina manualmente las copias locales que no se pudieron quitar.");

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
