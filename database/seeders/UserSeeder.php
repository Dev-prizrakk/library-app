<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // email => [ФИО, имя роли, пароль]
        $users = [
            'user@example.com'      => ['Иванов Иван Иванович',    'user',      'user12345'],
            'student@example.com'   => ['Петров Пётр Петрович',    'student',   'student12345'],
            'librarian@example.com' => ['Сидорова Анна Сергеевна', 'librarian', 'librarian12345'],
            'admin@example.com'     => ['Админов Админ Админович', 'admin',     'admin12345'],
        ];

        foreach ($users as $email => [$name, $roleName, $password]) {
            $role = Role::where('name', $roleName)->first();

            if (! $role) {
                $this->command->warn("Роль '{$roleName}' не найдена — пропускаю {$email}");
                continue;
            }

            User::factory()->create([
                'name' => $name,
                'email' => $email,
                'role_id' => $role->id,
                'password' => $password, // хэшируется кастом 'hashed' в модели User
            ]);
        }
    }
}