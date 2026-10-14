<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

#[Signature('project:build')]
#[Description('Genera el directorio build, instala dependencias de producción y comprime el vendor en un ZIP')]
class BuildProject extends Command
{
    public function handle()
    {
        $buildPath = base_path('build');
        $vendorPath = $buildPath.'/vendor';
        $zipPath = $buildPath.'/vendor.zip';

        // 1. Preparar el directorio
        if (! File::exists($buildPath)) {
            File::makeDirectory($buildPath);
            $this->info('Directorio build creado exitosamente.');
        } else {
            // Limpiar builds anteriores si existen
            File::cleanDirectory($buildPath);
        }

        // 2. Copiar archivos de Composer
        File::copy(base_path('composer.json'), $buildPath.'/composer.json');
        File::copy(base_path('composer.lock'), $buildPath.'/composer.lock');

        // 3. Ejecutar Composer de forma segura usando Process
        $this->info('Instalando dependencias sin dev (esto puede tardar unos segundos)...');

        $result = Process::path($buildPath)
            ->forever()
            ->run('composer install --no-dev --optimize-autoloader --no-interaction --no-progress --prefer-dist --no-scripts');

        if ($result->failed()) {
            $this->error('Error al ejecutar Composer:');
            $this->error($result->errorOutput());

            return Command::FAILURE;
        }

        // 4. Comprimir el vendor en un ZIP
        $this->info('Comprimiendo la carpeta vendor...');

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {

            // Iterar sobre todos los archivos dentro de vendor
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($vendorPath),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file) {
                if (! $file->isDir()) {
                    $filePath = $file->getRealPath();
                    // Calcular la ruta relativa para el ZIP
                    $relativePath = 'vendor/'.substr($filePath, strlen($vendorPath) + 1);
                    $zip->addFile($filePath, str_replace('\\', '/', $relativePath));
                }
            }
            $zip->close();
            $this->info('Archivo vendor.zip creado correctamente.');
        } else {
            $this->error('No se pudo crear el archivo ZIP.');

            return Command::FAILURE;
        }

        // 5. Limpieza: Eliminar archivos temporales para dejar solo el ZIP
        $this->info('Limpiando archivos temporales...');
        File::deleteDirectory($vendorPath);
        File::delete([$buildPath.'/composer.json', $buildPath.'/composer.lock']);

        $this->info('✅ Build finalizado. Tu vendor optimizado está en: build/vendor.zip');

        return Command::SUCCESS;
    }
}
