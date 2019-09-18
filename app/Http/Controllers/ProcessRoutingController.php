<?php

namespace App\Http\Controllers;

use App\Services\MachanService;
use App\Repositories\ProcessRoutingRepository;

class ProcessRoutingController extends Controller
{

    protected $processRepo;
    protected $machanService;
    public function __construct(ProcessRoutingRepository $processRepo, MachanService $machanService)
    {
        $this->processRepo = $processRepo;
        $this->machanService = $machanService;
    }


    public function syncProcessRouting()
    {
        $data = $this->machanService->syncTechRouting();
        
        return redirect('process-routing');
    }

    public function index()
    {
        return view('system.processrouting');
    }

    public function processRoutingIndex() //index paginate
    {
        return $this->processRepo->processRoutingIndex(request()->amount);
    }
    public function getApsData()
    {
        $data = $this->processRepo->getData();
        
        return response()->json($data);
    }
}
