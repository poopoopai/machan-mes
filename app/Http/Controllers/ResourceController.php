<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\Summary;
use Carbon\Carbon;
use DB;
class ResourceController extends Controller
{
    public function test(){
       
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
        $sum  = "" ; 
        
        $abc = request()->all();
        
        $bbb = collect($abc)->except('_token')->values();
        
        for( $i = 1 ; $i < sizeof($bbb) ; $i++){          
            $sum = $sum.$bbb[$i];
        }
        dd($sum);
        
        dd($abc, $bbb ,$sum,$total,$i , data_get($abc , $default = null));
    }
    
}
