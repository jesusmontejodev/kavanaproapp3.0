<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ImportAsesores extends Command
{
    protected $signature = 'import:asesores {path=/mnt/data/ASESORES_HASHED.csv}';

    protected $description = 'Importa usuarios desde un CSV y los registra en la base de datos';

    public function handle()
    {
        $path = $this->argument('path');

        if (!file_exists($path)) {
            $this->error("El archivo no existe: $path");
            return;
        }

        $this->info("Importando archivo: $path");

        $file = fopen($path, 'r');

        // Leer encabezados
        $headers = fgetcsv($file);

        $count = 0;

        while (($data = fgetcsv($file)) !== false) {

            $row = array_combine($headers, $data);

            // Evita duplicados
            if (User::where('email', $row['email'])->exists()) {
                $this->warn("Saltado (ya existe): " . $row['email']);
                continue;
            }

            User::create([
                'name' => $row['nombre'],
                'email' => $row['correo'],
                'password' => $row['password'], // bcrypt
                'phone' => $row['Numero'] ?? null,
                'estado' => $row['estado'] ?? null,
                'ciudad' => $row['ciudad'] ?? null,
                'empresa' => $row['empresa'] ?? null,
            ]);
 
            $count++;
        }

        fclose($file);

        $this->info("Importación completa. Usuarios agregados: $count");
    }
}
