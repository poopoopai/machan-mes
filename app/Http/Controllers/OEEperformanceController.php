<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\SummaryRepository;
use App\Repositories\ResourceRepository;
use App\Repositories\OEEperformanceRepository;
use App\Entities\OEEperformance;
use App\Entities\Summary;
use App\Entities\Resource;
use Carbon\Carbon;

class OEEperformanceController extends Controller
{
    protected $OEErepo;
    public function __construct(OEEperformanceRepository $OEErepo)
    {
        $this->OEErepo = $OEErepo;
    }

    public function show(){
        $datas = OEEperformance::paginate(100);
        return view('OEEperformance', ['datas' => $datas]);
    }

    public function searchdate()
    {
        $datas = OEEperformance::whereBetween('date' , [request()->date_start, request()->date_end])->paginate(100)->appends(request()->query());
        $date = request()->only('date_start' , 'date_end');
            if($datas[0]){
                return view('searchOEEperformance', ['datas' => $datas, 'date' => $date]);
            }
            return view('OEEperformance', ['datas' => $datas, 'date' => $date]); 
    }

    public function getOEEperformance(){

        $sum =  [];

        // work ( date, day, weekend, work_name, standard_working_hours, total_hours )
        $work = $this->OEErepo->work($sum);
        $sum = array_merge($sum, $work);
        

        // 機台加工時間  machine_processing_time 
        // ( mass_production_time, total_downtime, standard_processing_seconds, actual_processing_seconds, updown_time )
        $sum['mass_production_time'] = $this->OEErepo->mass_production_time($sum); //因為真的太長所以分開寫...
        $machine_processing_time = $this->OEErepo->machine_processing_time($sum);
        $sum = array_merge($sum, $machine_processing_time);
        

        // 機檯作業數量  machine_works_number ( total_completion_that_day, machine_processing, actual_production_quantity, 
        //                                    standard_completion, total_input_that_day, adverse_number )
        $machine_works_number = $this->OEErepo->machine_works_number($sum);
        $sum = array_merge($sum, $machine_works_number);
        
        // 機台嫁動除外工時   machinee_work_except_hours 
        // ( correction_time, hanging_time, aggregate_time, break_time, chang_model_and_line, machine_downtime,
        //   bad_disposal_time, model_damge_change_line_time, program_modify_time, machine_maintain_time, excluded_working_hours )                              )
        $machinee_work_except_hours = $this->OEErepo->machinee_work_except_hours($sum);
        $sum = array_merge($sum, $machinee_work_except_hours);
    
        // 機檯表現    machine_performance ( machine_utilization_rate, performance_rate, yield, OEE )
        $machine_performance = $this->OEErepo->machine_performance($sum);
        $sum = array_merge($sum, $machine_performance);
        // dd($sum);

        OEEperformance::create($sum);
    }
}
