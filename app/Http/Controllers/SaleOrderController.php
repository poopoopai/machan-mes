<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchSaleOrder;
use App\Services\GetProduceService;
use App\Repositories\SaleOrderRepository;

class SaleOrderController extends Controller
{
    protected $produceService;
    protected $saleRepo;

    public function __construct(GetProduceService $produceService, SaleOrderRepository $saleRepo)
    {
        $this->saleRepo = $saleRepo;
        $this->produceService = $produceService;
    }
    public function index() 
    {
        return view('dataload/sync-order', [
            'dateInfo' => $this->saleRepo->getSynchroizedDate()
        ]);
    }

    public function synchroizedForm()
    {
        // $data = $this->saleRepo->getSynchroizedResult(request()->date);
        return view('dataload.sync-order-result-form');
    }

    public function getSaleOrderData(SearchSaleOrder $request)
    {
        
        $data = request([
            'org_id', 'container_date_start',
            'container_date_end', 'bill_date_start',
            'bill_date_end', 'so_id', 'customer_name',
            'amount', 'page',
        ]);
            
        $billStratDate = date('Ymd', strtotime($data['bill_date_start']));
        $billEndDate = date('Ymd', strtotime($data['bill_date_end']));

        $saleOrdeData = $this->produceService->getSourceOrder(
            $data['org_id'],
            $billStratDate,
            $billEndDate,
            $data['container_date_start'],
            $data['container_date_end'],
            $data['so_id'],
            $data['customer_name']
        );
        return redirect()->route('sale-order-result', [
            'org_id' => $data['org_id'],
            'bill_date_start' => $billStratDate,
            'bill_date_end' => $billEndDate,
            'container_date_start' => $data['container_date_start'],
            'container_date_end' => $data['container_date_end'],
            'so_id' => $data['so_id'],
            'customer_name' => $data['customer_name'],
        ]);
    }

    public function orderResult()
    {
        return view('dataload/sync-order-result');
    }

    public function getCurrentLoadedData()
    {
        $data = request()->all(
            'bill_date_start',
            'bill_date_end',
            'org_id',
            'container_date_start',
            'container_date_end',
            'customer_name',
            'so_id'
        );

        return response()
            ->json($this->saleRepo->currentLoadedData($data)
            ->paginate(request()->amount));
    }

    public function getSynchroizedResult() //
    {
        return response()
            ->json($this->saleRepo->getSynchroizedResult(request()->date)
            ->paginate(request()->amount));
    }

   
}
