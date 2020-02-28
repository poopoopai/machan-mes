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
        return view('OEEperformance');
    }

    public function searchdate()
    {
        $datas = OEEperformance::whereBetween('date' , [request()->date_start, request()->date_end])->paginate(100)->appends(request()->query());
        $date = request()->only('date_start' , 'date_end');
            if($datas){
                return view('searchOEEperformance', ['datas' => $datas, 'date' => $date]);
            }
            return view('OEEperformance'); 
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

        OEEperformance::updateOrCreate( ['date' => $sum['date'] ] , 
            [
                'day' => $sum['day'],
                'weekend' => $sum['weekend'],
                'work_name' => $sum['work_name'],
                'standard_working_hours' => $sum['standard_working_hours'],
                'total_hours' => $sum['total_hours'],
                'machine_processing' => $sum['machine_processing'],
                'actual_production_quantity' => $sum['actual_production_quantity'],
                'standard_completion' => $sum['standard_completion'],
                'total_input_that_day' => $sum['total_input_that_day'],
                'total_completion_that_day' => $sum['total_completion_that_day'],
                'adverse_number' => $sum['adverse_number'],
                'mass_production_time' => $sum['mass_production_time'],
                'total_downtime' => $sum['total_downtime'],
                'standard_processing_seconds' => $sum['standard_processing_seconds'],
                'actual_processing_seconds' => $sum['actual_processing_seconds'],
                'updown_time' => $sum['updown_time'],
                'correction_time' => $sum['correction_time'],
                'hanging_time' => $sum['hanging_time'],
                'aggregate_time' => $sum['aggregate_time'],
                'break_time' => $sum['break_time'],
                'chang_model_and_line' => $sum['chang_model_and_line'],
                'machine_downtime' => $sum['machine_downtime'],
                'bad_disposal_time' => $sum['bad_disposal_time'],
                'model_damge_change_line_time' => $sum['model_damge_change_line_time'],
                'program_modify_time' => $sum['program_modify_time'],
                'machine_maintain_time' => $sum['machine_maintain_time'],
                'excluded_working_hours' => $sum['excluded_working_hours'],
                'machine_utilization_rate' => $sum['machine_utilization_rate'],
                'performance_rate' => $sum['performance_rate'],
                'yield' => $sum['yield'],
                'OEE' => $sum['OEE'],
            ] );
    }
}
