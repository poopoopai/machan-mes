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

    public function getOEEperformance()
    {
        $sum =  [];

        // work ( date, day, weekend, work_name, standard_working_hours, total_hours )
        $work = $this->OEErepo->work($sum);
        $sum = array_merge($sum, $work);
        

        //機台加工時間  machine_processing_time 
        // ( mass_production_time, total_downtime, standard_processing_seconds, actual_processing_seconds, updown_time )
        $sum['mass_production_time'] = $this->OEErepo->mass_production_time($sum); //因為真的太長所以分開寫...
        $machine_processing_time = $this->OEErepo->machine_processing_time($sum);
        $sum = array_merge($sum, $machine_processing_time);
        

        //機檯作業數量  machine_works_number ( total_completion_that_day, machine_processing, actual_production_quantity, 
        //                                    standard_completion, total_input_that_day, adverse_number )
        $machine_works_number = $this->OEErepo->machine_works_number($sum);
        $sum = array_merge($sum, $machine_works_number);
        dd($sum);
        
        //機台嫁動除外工時   machinee_work_except_hours
        $sum['correction_time'] =  '';   //空白 
        $sum['hanging_time'] =  $this->OEErepo->hanging_time($sum); 
        $sum['aggregate_time'] =  $this->OEErepo->aggregate_time($sum);  
        $sum['break_time'] =  $this->OEErepo->break_time($sum);  
        $sum['chang_model_and_line'] =  ''; 
        $sum['machine_downtime'] =  $this->OEErepo->machine_downtime($sum); 
        $sum['bad_disposal_time'] =  '';
        $sum['model_damge_change_line_time'] =  '';
        $sum['program_modify_time'] =  '';
        $sum['machine_maintain_time'] =  '';
        $sum['excluded_working_hours'] =  $this->OEErepo->excluded_working_hours($sum);
        $sum['machine_utilization_rate'] = $this->OEErepo->machine_utilization_rate($sum);
        $sum['performance_rate'] = $this->OEErepo->performance_rate($sum);
        $sum['yield'] = $this->OEErepo->yield($sum);
        $sum['OEE'] = $this->OEErepo->OEE($sum);
        
        
        OEEperformance::create($sum);
    }
}
