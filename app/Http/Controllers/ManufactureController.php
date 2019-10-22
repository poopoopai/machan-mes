<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ManufactureRepository;
use App\Services\GetProduceService;

class ManufactureController extends Controller
{
    protected $produceService;
    protected $manufactRepo;

    public function __construct(GetProduceService $produceService, ManufactureRepository $manufactRepo)
    {
        $this->produceService = $produceService;
        $this->manufactRepo = $manufactRepo;
    }
    
    public function index()
    {
        return view('dataload/search-manufacture');
    }
    public function getManufactureData()
    {
        $this->produceService->getSourceOrder(request()->mo_id);
        return redirect()->route('manufacture-result', ['mo_id' => request()->mo_id]);
    }

    public function manufactureResult()
    {
        return view('dataload/manufacture-result', ['mo_id' => request()->mo_id]);
    }

    public function getCurrentLoadedData()
    {      
        return response()
            ->json($this->manufactRepo->currentLoadedData(request()->all())
            ->paginate(request()->amount));
    }
}
