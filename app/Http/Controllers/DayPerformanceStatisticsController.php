<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\SummaryRepository;
use App\Repositories\ResourceRepository;
use App\Entities\DayPerformanceStatistics;
use App\Entities\Summary;
use App\Entities\Resource;
use Carbon\Carbon;

class DayPerformanceStatisticsController extends Controller
{
    protected $SummaryRepo;
    public function __construct(SummaryRepository $SummaryRepo)
    {
        $this->SumRepo = $SummaryRepo;
    }

    public function getmachineperformance()
    {
        // $parme = $this->SumRepo->data();
        // foreach($parme as $parmas) {
            $sum =  [];

            $sum['report_work_date'] = '2019-10-2';   //Carbon::today()->format("Y-m-d")
            $sum['work_name'] = '正常班';     //無運算??
            $sum['standard_working_hours'] = $this->SumRepo->standard_working_hours($sum);
            $sum['total_hours'] = $this->SumRepo->total_hours($sum);   

            //機台代碼 機台名稱
            $sum['machine_code'] = '5010R01';  //???????????????????????
            $sum['machine_name'] = '五廠抽屜捲料自動裁切機';   //無運算??
            
            $sum['production_category'] = '量產';   //無運算??
            
            //製令資訊
            $sum['order_number'] = '';  //空白??
            $sum['material_name'] = 'UAT-H-26-161';   //無運算??
            $sum['production_quantity'] = 1;   //空白??
            
            //標準ct
            $sum['standard_processing'] = $this->SumRepo->standard_processing($sum); 
            $sum['standard_updown'] =  $this->SumRepo->standard_updown($sum); 
            
            //機檯作業數量      machine_works_number
            $machine_works_number = $this->SumRepo->machine_works_number($sum);
            $sum = array_merge($sum, $machine_works_number);
            
            //機台加工時間      machine_processing_time
            $sum['mass_production_time'] = $hanging_time = $this->SumRepo->mass_production_time($sum); //因為真的太長所以分開寫...
            $machine_processing_time = $this->SumRepo->machine_processing_time($sum);
            $sum = array_merge($sum, $machine_processing_time);

            //機台嫁動除外工時   machinee_work_except_hours
            $machinee_work_except_hours = $this->SumRepo->machinee_work_except_hours($sum);
            $sum = array_merge($sum, $machinee_work_except_hours);
                        
            //機台性能除外工時      performance_exclusion_time
            $performance_exclusion_time = $this->SumRepo->performance_exclusion_time($sum);
            $sum = array_merge($sum, $performance_exclusion_time);
            
            DayPerformanceStatistics::create($sum);
    }


    
    
}