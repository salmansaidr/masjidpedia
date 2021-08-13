<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:project';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'install project';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Artisan::call('key:generate');
        Artisan::call('migrate');
        Artisan::call('db:seed');
        Artisan::call('passport:install');
        Artisan::call('config:clear');
    }
}
