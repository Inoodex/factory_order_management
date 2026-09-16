<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-excel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('Testing Excel facade...');
            $excel = \Maatwebsite\Excel\Facades\Excel::class;
            $this->info('Excel facade class: ' . $excel);
            $this->info('Excel facade is available!');
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
