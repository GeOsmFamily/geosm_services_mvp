<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\OuvrageController;
use Illuminate\Console\Command;
use App\Http\Controllers\thematiqueController;
use Illuminate\Support\Facades\DB;

class AddMeta extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addmeta';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creation / re - creation de toutes les metas pradec';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $ouvrageController = new OuvrageController();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ouvrageController = new OuvrageController();
        $ouvrageController->addMetadatas();
    }
}
