<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Права
        $permissions = [
            'view_books'     => 'Просмотр книг',
            'borrow_books'   => 'Выдача книг',
            'return_books'   => 'Возврат книг',
            'manage_books'   => 'Управление книгами',
            'manage_users'   => 'Управление пользователями',
            'manage_roles'   => 'Управление ролями',
            'view_reports'   => 'Просмотр отчётов',
        ];

        $permissionModels = collect($permissions)->mapWithKeys(function ($display, $name) {
            $permission = Permission::firstOrCreate(
                ['name' => $name],
                ['display_name' => $display]
            );

            return [$name => $permission];
        });

        // 2. Роли и их права
        $roles = [
            'user' => [
                'display' => 'Пользователь',
                'permissions' => ['view_books'],
            ],
            'student' => [
                'display' => 'Студент',
                'permissions' => ['view_books', 'borrow_books', 'return_books'],
            ],
            'librarian' => [
                'display' => 'Библиотекарь',
                'permissions' => [
                    'view_books', 'borrow_books', 'return_books',
                    'manage_books', 'view_reports',
                ],
            ],
            'admin' => [
                'display' => 'Администратор',
                'permissions' => array_keys($permissions), // все права
            ],
        ];

        foreach ($roles as $name => $data) {
            $role = Role::firstOrCreate(
                ['name' => $name],
                ['display_name' => $data['display']]
            );

            $role->permissions()->sync(
                $permissionModels->only($data['permissions'])->pluck('id')->all()
            );
        }
    }
}