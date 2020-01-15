<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\api\ResourceController;
class GetMachineData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'machine-data:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get machinedata';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ResourceController $resourceController)
    {
        parent::__construct();
        $this->resourceController = $resourceController;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->resourceController->fixmachinedatabase();
    }
}
