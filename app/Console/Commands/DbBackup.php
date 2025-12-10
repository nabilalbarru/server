<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DbBackup extends Command
{
    protected $signature = 'db:backup';
    protected $description = 'Backup database MySQL ke storage/app/backups/db dengan rotasi & log';

    public function handle()
    {
        $db   = config('database.connections.mysql.database');
        $user = config('database.connections.mysql.username');
        $pass = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        $dump = env('MYSQLDUMP_PATH', 'C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysqldump.exe');

        $folder     = 'backups/db';
        $storageDir = storage_path("app/{$folder}");
        $timestamp  = now()->format('Ymd_His');
        $filename   = "{$db}_{$timestamp}.sql.gz";
        $filePath   = "{$storageDir}/{$filename}";

        
        if (!file_exists($storageDir)) {
            mkdir($storageDir, 0755, true);
        }

        $command = "\"{$dump}\" --user={$user} --password={$pass} --host={$host} {$db} | gzip > \"{$filePath}\"";
        exec($command, $output, $result);

        if ($result === 0) {
            $this->info("Backup database berhasil disimpan di: {$filePath}");
        } else {
            $this->error("Backup database gagal dijalankan.");
        }
    }
}
