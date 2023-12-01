<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class EmailCleaner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:email-cleaner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete notifications that are read more than 2 months ago';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Notification::whereNotNull('read_at')
            ->where('read_at', '<', Carbon::now()->subMonths(2))
            ->delete();

        $this->info('Email cleaner command completed successfully.');
    }
}
