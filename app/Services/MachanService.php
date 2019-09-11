<?php

namespace App\Services;

use App\Repositories\ProcessRoutingRepository;
use Ixudra\Curl\Facades\Curl;

class MachanService
{
    private $apiServer;
    protected $processRoutingRepo;

    public function __construct(ProcessRoutingRepository $processRoutingRepo)
    {
        $this->apiServer = 'http://'.env('MACHAN_JSON_API');
        $this->processRoutingRepo = $processRoutingRepo;
    }

    private function connectWebService($uri, $params = [])
    {
        if (!env('MACHAN_JSON_API')) {
            throw new Exception('MACHAN_JSON_API not defined');
        }

        return Curl::to($this->apiServer.$uri)
            ->withData($params)
            ->get();
    }


    public function syncTechRouting()
    {
        $uri = '/api/TechRouteV';
        $result = $this->connectWebService($uri);
        $data = $this->processRoutingRepo->syncTechRouting(json_decode($result));
        return $data;
    }
}
