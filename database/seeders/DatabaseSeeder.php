<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Application;
use App\Models\Department;
use App\Models\Grade;
use App\Models\Position;
use App\Models\Role;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        DB::table('users')->truncate();
        DB::table('positions')->truncate();
        DB::table('applications')->truncate();
        DB::table('departments')->truncate();

        $this->call([
            UserSeeder::class,
            PositionSeeder::class,
            DepartmentSeeder::class,
            RoleSeeder::class,
            StatusSeeder::class,
            GradeSeeder::class,
            ApplicationSeeder::class,
        ]);
    }
}

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->count(450)->create();
    }
}
class PositionSeeder extends Seeder
{
    public function run(): void
    {
        Position::factory()->count(550)->create();
    }
}
class ApplicationSeeder extends Seeder
{
    public function run(): void
    {
        Application::factory()->count(255)->create();
    }
}
class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        Department::factory()->count(20)->create();
    }
}
