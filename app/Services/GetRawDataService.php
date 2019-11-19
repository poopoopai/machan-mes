<?php

namespace App\Services;

use DB;
use Carbon\Carbon;
use App\Entities\Resource;


class GetRawDataService
{

    public function getrawdata()
    {

        $last = Resource::where('date', Carbon::today())->orderby('time', 'desc')->first();
        if (is_null($last)) {
            $last['time'] = "00:00:00";
        }
        $datas = DB::connection('mysql2')->table('12_11_backup')->where('Date', Carbon::today()->format("Y-m-d"))->where('Time', '>', $last['time'])->orderby('Time')->get();
        // $datas = DB::connection('mysql2')->table('12_11_backup')->where('Date', Carbon::today()->format("Y-m-d"))->where('Time', '>', $last['time'])->orderby('Time')->get();
        
        foreach ($datas as $key => $data) {

            Resource::create([
                'machine_id' => $data->id,
                'orderno' => trim($data->OrderNo),
                'status_id' => $data->Status,
                'code' => $data->Code,
                'date' => $data->Date,
                'time' => $data->Time,
            ]);
        }

        dd("資料抓完了");
    }
}
