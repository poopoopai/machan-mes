<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GetRawDataService;

class GetRawData extends Command
{
    protected $rawdataService;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'raw-data:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get rawdata';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(GetRawDataService $rawdataService)
    {
        parent::__construct();
        $this->rawdataService = $rawdataService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->rawdataService->getrawdata();
    }
}
