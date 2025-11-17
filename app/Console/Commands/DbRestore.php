<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DbRestore extends Command
{
    protected $signature = 'db:restore {filename?}';
    protected $description = 'Restore database MySQL dari file backup di storage/app/backups/db';

    public function handle()
    {
        $db   = config('database.connections.mysql.database');
        $user = config('database.connections.mysql.username');
        $pass = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');
        $dump = env('MYSQL_PATH', 'C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysql.exe');

        $folder = storage_path('app/backups/db');

        
        $filename = $this->argument('filename');
        if (!$filename) {
            $files = glob("{$folder}/*.sql.gz");
            if (empty($files)) {
                $this->error("Tidak ada file backup di folder {$folder}");
                return;
            }
            usort($files, function ($a, $b) {
                return filemtime($b) - filemtime($a);
            });
            $filename = basename($files[0]);
        }

        $filePath = "{$folder}/{$filename}";

        if (!file_exists($filePath)) {
            $this->error("File {$filename} tidak ditemukan di folder {$folder}");
            return;
        }

       
        $tempSql = "{$folder}/temp_restore.sql";
        $this->info("Mengekstrak file backup...");
        $gz = gzopen($filePath, 'rb');
        $out = fopen($tempSql, 'wb');
        while (!gzeof($gz)) {
            fwrite($out, gzread($gz, 4096));
        }
        gzclose($gz);
        fclose($out);


        $command = "\"{$dump}\" --user={$user} --password={$pass} --host={$host} {$db} < \"{$tempSql}\"";
        exec($command, $output, $result);

        if ($result === 0) {
            $this->info("Database berhasil direstore dari {$filename}");
        } else {
            $this->error("Restore database gagal dijalankan.");
        }

    
        unlink($tempSql);
    }
}
