<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\GivController as giv;

class initCtegories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'categories:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $categories = new giv();
        $categories->getCategoriesList();
        return 0;
    }
}
