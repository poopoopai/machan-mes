<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\SummaryRepository;
use App\Repositories\ResourceRepository;
use App\Entities\DayPerformanceStatistics;
use App\Entities\Summary;
use App\Entities\Resource;
use Carbon\Carbon;

set_time_limit(0);
class DayPerformanceStatisticsController extends Controller
{
    protected $SummaryRepo;
    public function __construct(SummaryRepository $SummaryRepo)
    {
        $this->SumRepo = $SummaryRepo;
    }

    public function show(){
        $datas = DayPerformanceStatistics::paginate(100);
        return view('dayperformance', ['datas' => $datas]);
    }

    public function searchdate()
    {
        $datas = DayPerformanceStatistics::whereBetween('report_work_date' , [request()->date_start, request()->date_end])->paginate(100)->appends(request()->query());
        $date = request()->only('date_start' , 'date_end');
            if($datas[0]){
                return view('searchdayperformance', ['datas' => $datas, 'date' => $date]);
            }
            return view('dayperformance', ['datas' => $datas, 'date' => $date]);
    }

    public function getmachineperformance()
    {
        $today = Carbon::today()->format("Y-m-d");
        // $orderno = Resource::where('date', '2019-11-27')->select('orderno')->distinct()->get();
        $orderno = Resource::where('date', $today)->select('orderno')->distinct()->get();
        // dd($orderno);
        foreach($orderno as $key =>$datas){
            if($datas->orderno !== ''){
                $dayPerfor =  [];

                //製令資訊
                $dayPerfor['order_number'] = '';  // 製令單號 空白??
                $dayPerfor['material_name'] = $datas->orderno;   
                $dayPerfor['production_quantity'] = 1;   // 生產數量 空白??
                
                $dayPerfor['report_work_date'] = $today; //$today
                $dayPerfor['work_name'] = $this->SumRepo->work_name($dayPerfor);     //跟oee一樣去抓是否有加班?setup_shifts->name, process_calendars
                $dayPerfor['standard_working_hours'] = $this->SumRepo->standard_working_hours($dayPerfor);
                $dayPerfor['total_hours'] = $this->SumRepo->total_hours($dayPerfor);   

                //機台代碼 機台名稱
                $dayPerfor['machine_code'] = '5010R01';  //???????????????????????
                $dayPerfor['machine_name'] = '五廠抽屜捲料自動裁切機';
                
                $dayPerfor['production_category'] = '量產';
                
                
                //標準ct
                $dayPerfor['standard_processing'] = $this->SumRepo->standard_processing($dayPerfor); 
                $dayPerfor['standard_updown'] =  $this->SumRepo->standard_updown($dayPerfor); 
                
                //機檯作業數量      machine_works_number
                $machine_works_number = $this->SumRepo->machine_works_number($dayPerfor);
                $dayPerfor = array_merge($dayPerfor, $machine_works_number);
                
                //機台加工時間      machine_processing_time
                $dayPerfor['mass_production_time'] = $hanging_time = $this->SumRepo->mass_production_time($dayPerfor); //因為真的太長所以分開寫...
                $machine_processing_time = $this->SumRepo->machine_processing_time($dayPerfor);
                $dayPerfor = array_merge($dayPerfor, $machine_processing_time);

                //機台嫁動除外工時   machinee_work_except_hours
                $machinee_work_except_hours = $this->SumRepo->machinee_work_except_hours($dayPerfor);
                $dayPerfor = array_merge($dayPerfor, $machinee_work_except_hours);

                //機台性能除外工時      performance_exclusion_time
                $performance_exclusion_time = $this->SumRepo->performance_exclusion_time($dayPerfor);
                $dayPerfor = array_merge($dayPerfor, $performance_exclusion_time);
        

                DayPerformanceStatistics::create($dayPerfor);
            }
            
        }
    }


    
    
}