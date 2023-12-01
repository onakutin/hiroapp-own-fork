<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('statuses')->truncate();
        $statuses = ['applied', 'in progress', 'interview', 'feedback', 'hired', 'ended'];

        foreach ($statuses as $status) {
            Status::create(['name' => $status]);
        }
    }
}
