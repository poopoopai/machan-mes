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
        $sum  = "" ; 
        
        $abc = request()->all();
        
        $bbb = collect($abc)->except('_token')->values();
        
        for( $i = 1 ; $i < sizeof($bbb) ; $i++){          
            $sum = $sum.$bbb[$i];
        }
      

        $num_arr = array();// 宣告數字棧
        $op_arr = array();// 宣告符號棧
        $str = $sum;
        preg_match_all('/./', $str, $arr);// 把運算串分解成每個字元到$arr陣列
     
        $str_arr = $arr[0];
        $length = count($str_arr);
        $pre_num = '';
        // 開始入棧
        for($i=0; $i<$length; $i++){
        $val = $str_arr[$i];
        // 數字
        if (is_numeric($val)){
        $pre_num .= $val;// 兼顧下一個字元可能也是數字的情況（多位數）
        if($i+1>=$length || $this->isOper($str_arr[$i+1])){// 下一個是運算子或者到頭了，則把數字塞進數字棧
        array_push($num_arr, $pre_num);
        $pre_num = '';
        }
        // 符號判斷優先順序，選擇是否入棧
        } else if ($this->isOper($val)){
        if (count($op_arr)>0){
        // 判斷優先順序，只要不大於符號棧頂的優先順序，就開始計算，直到優先順序大於了棧頂的，計算後才再把這個運算子入棧
        while (end($op_arr) && $this->priority($val) <= $this->priority(end($op_arr))){
            $this->calc($num_arr, $op_arr);
        }
        }
        array_push($op_arr, $val);
        }
        }
        //echo '<pre>';
        //print_r($num_arr);
        //print_r($op_arr);
        // 計算棧裡剩餘的
        while(count($num_arr)>0){
            $this->calc($num_arr, $op_arr);
        if (count($num_arr)==1){
        $result = array_pop($num_arr);
        break;
        }
        }
        dd($str,' = ', $result);

    }

    function calc(&$num_arr, &$op_arr){
        if (count($num_arr)>0){
        $num1 = array_pop($num_arr);
        $num2 = array_pop($num_arr);
        $op = array_pop($op_arr);

        if ($op=='*') $re = $num1*$num2;
        if ($op=='/') $re = $num2/$num1;// 這裡注意順序，棧是先進後出，所以$num2是被除數
        if ($op=='+') $re = $num2+$num1;
        if ($op=='-') $re = $num2-$num1;
        
        array_push($num_arr, $re);
        }
        }
        // 獲取優先順序
        function priority($str){
        if ($str == '*' || $str == '/'){
        return 1;
        } else {
        return 0;
        }
        }
        // 判斷是否是運算子
        function isOper($oper){
        $oper_array = array('+','-','*','/');
        if (in_array($oper, $oper_array)){
        return true;
        }
        return false;
        }



        
    
}
