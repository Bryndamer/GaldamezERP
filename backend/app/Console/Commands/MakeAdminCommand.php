<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MakeAdminCommand extends Command
{
    protected $signature = 'make:admin
                            {--name= : Nombre del administrador}
                            {--email= : Email del administrador}';

    protected $description = 'Crea un usuario con rol admin (interactivo)';

    public function handle(): int
    {
        $data = [
            'name'                  => $this->option('name') ?: $this->ask('Nombre'),
            'email'                 => $this->option('email') ?: $this->ask('Email'),
            'password'              => $this->secret('Contraseña'),
            'password_confirmation' => $this->secret('Confirma la contraseña'),
        ];

        $validator = Validator::make($data, [
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:10|confirmed',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return self::FAILURE;
        }

        User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'admin',
            'phone'    => null,
        ]);

        $this->info("Admin creado: {$data['email']}");

        return self::SUCCESS;
    }
}
