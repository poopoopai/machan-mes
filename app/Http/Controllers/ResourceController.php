<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\Summary;
use App\Entities\Resource;
use Carbon\Carbon;
use DB;
class ResourceController extends Controller
{
    public function test2(){
        
        $datas = DB::connection('mysql2')->table('db')->get();
       
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
        $data = Summary::paginate (100);
        
        return view('machineperformance', ['datas' => $data]);
    }
     public function getdatabase()
    {
        $results = Summary::wheredate('created_at' , '>=' , Carbon::today())
                    ->wheredate('created_at' , '<' , Carbon::tomorrow())
                    ->orderby('id' , 'desc')
                    ->first();
        if($results){
            return response()->json(['data' => $results]);
        }
        else{
            return response()->json(['data' => 'error']);
        }
       
    }
    
    public function inform()
    {
          
        $data = collect(request()->all())->except('_token')->values();
    
        $sum = "" ;
        for( $i = 1 ; $i < sizeof($data) ; $i++){          
            $sum = $sum.$data[$i];
        }
        $sum = "23*((72/2)+231/11)";
        $result = eval("return $sum;");

        echo $sum.'='.$result;
            
    }    
    
}
