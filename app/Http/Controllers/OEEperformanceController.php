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

        $sum['date'] = Carbon::today()->format("Y-m-d");
        $sum['day'] = $this->OEErepo->day($sum);
        $sum['weekend'] = $this->OEErepo->weekend($sum);
        $sum['work_name'] = $this->OEErepo->work_name($sum);   
        $sum['standard_working_hours'] = $this->OEErepo->standard_working_hours($sum);
        $sum['total_hours'] = $this->OEErepo->total_hours($sum);
        
        
        //機台加工時間      machine_processing_time
        $sum['mass_production_time'] = $this->OEErepo->mass_production_time($sum); //因為真的太長所以分開寫...
        $sum['total_downtime'] = $this->OEErepo->total_downtime($sum);
        $sum['standard_processing_seconds'] = $this->OEErepo->standard_processing_seconds($sum);
        $sum['actual_processing_seconds'] = $this->OEErepo->actual_processing_seconds($sum);
        $sum['updown_time'] =  '';  //空白 

        //機檯作業數量      machine_works_number
        $sum['total_completion_that_day'] = $this->OEErepo->total_completion_that_day($sum); //必需先做

        $sum['machine_processing'] = $this->OEErepo->machine_processing($sum);  
        $sum['actual_production_quantity'] = $this->OEErepo->actual_production_quantity($sum); 
        $sum['standard_completion'] = $this->OEErepo->standard_completion($sum);   //?????
        $sum['total_input_that_day'] = $this->OEErepo->total_input_that_day($sum);//同上上
        $sum['adverse_number'] = $this->OEErepo->adverse_number($sum); 

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
        dd($sum);
        
        OEEperformance::create($sum);
    }
}
