<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class DebugDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:debug-database';

    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    protected $signature = 'debug:db';
    protected $description = 'Debug database columns';
    public function handle()
    {
        $columns = Schema::getColumnListing('users');
        $this->info('Columns in users table:');
        foreach ($columns as $column) {
            $this->line($column);
        }
        return Command::SUCCESS;
    }
}
