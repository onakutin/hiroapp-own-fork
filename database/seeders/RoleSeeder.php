<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->truncate();
        $roles = [
            'admin', 'candidate', 'recruiter'
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }
    }
}
