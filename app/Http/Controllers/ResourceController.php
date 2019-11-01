<?php

namespace App\Http\Controllers;

use DB;
use Exception;
use Carbon\Carbon;
use App\Entities\Summary;
use App\Entities\Resource;

class ResourceController extends Controller
{
    public function test2()
    {
        $last = Resource::where('date', Carbon::today())->orderby('time', 'desc')->first();
        if (is_null($last)) {
            $last['time'] = "00:00:00";
        }

        try {
            $datas = DB::connection('mysql2')->table('db')->where('Date', Carbon::today()->format("Y-m-d"))->where('Time', '>', $last['time'])->orderby('Time')->get();
        } catch (Exception $e) {
            throw new Exception('DATABASE CONNECT ERROR!');
        }
        foreach ($datas as $key => $data) {

            Resource::create([
                'machine_id' => $data->Id,
                'orderno' => trim($data->OrderNo),
                'status_id' => $data->Status,
                'code' => $data->Code,
                'date' => $data->Date,
                'time' => $data->Time,
            ]);
        }
        dd("資料抓完了");
    }
    public function show()
    {
        $data = Summary::paginate(100);

        return view('machineperformance', ['datas' => $data]);
    }
    public function getdatabase()
    {
        $results = Summary::wheredate('created_at', '>=', Carbon::today())
            ->wheredate('created_at', '<', Carbon::tomorrow())
            ->orderby('id', 'desc')
            ->first();
        if ($results) {
            return response()->json(['data' => $results]);
        } else {
            return response()->json(['data' => 'error']);
        }
    }
}
