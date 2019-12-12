<?php
namespace App\Services;
use App\Services\MachinePerformanceService;


class TestService
{
    public function __construct( MachinePerformanceService $machinePerformanceService)
    {
        $this->machinePerformanceService = $machinePerformanceService;
    }
    public function aaa()
    {
        dd(14);
    }
    
}


?>