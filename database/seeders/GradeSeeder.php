<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('grades')->truncate();
        $grades = [
            'low', 'medium', 'high'
        ];

        foreach ($grades as $grade) {
            Grade::create(['name' => $grade]);
        }
    }
}
