<?php

namespace App\Services;

use App\Repositories\MachinePerformanceRepository;
use App\Repositories\RollerDataRepository;
use App\Services\RollerDataService;
use Carbon\Carbon;

class MachinePerformanceService
{
    protected $machinePerformanceRepo;
    protected $rollerDataService;
    protected $rollerDataRepo;

    public function __construct(MachinePerformanceRepository $machinePerformanceRepository, RollerDataService $rollerDataService
                                , RollerDataRepository $rollerDataRepository)
    {
        $this->machinePerformanceRepo = $machinePerformanceRepository; 
        $this->rollerDataService = $rollerDataService;
        $this->rollerDataRepo = $rollerDataRepository;
    }

    public function counts($data)
    {
        $count = $this->machinePerformanceRepo->getBeforeData();
        
        $count->id = $count->id + 1;
        
        if ($count->resources_id == 0 || $data['date'] != $count['date']) {  //如果為第一筆資料 或者 不是同一天 (就要重頭計算)
            $count->open = 0;
            $count->turn_off = 0;   //關機
            $count->start_count = 0;
            $count->stop_count = 0;
            $data['status_id'] == 3 ? $count->open++ : ($count->open == 0 ? $count->open : $count->open = '');
            $data['status_id'] == 4 ? $count->turn_off++ : ($count->turn_off == 0 ? $count->turn_off : $count->turn_off = '');
            $data['status_id'] == 3 ? $count->start_count++ : $count->start_count;
            $data['status_id'] == 4 ? $count->stop_count++ : $count->stop_count;
        } else {
            $oldopen = $this->machinePerformanceRepo->getLastOpen();
            $oldturn = $this->machinePerformanceRepo->getLastTurn();
            $data['status_id'] == 3 ? $count->open = $oldopen->open + 1 : $count->open = '';
            $data['status_id'] == 4 ? $count->turn_off = $oldturn->turn_off + 1 : $count->turn_off = '';
            $data['status_id'] == 3 ? $count->start_count++ : $count->start_count;
            $data['status_id'] == 4 ? $count->stop_count++ : $count->stop_count;
        }

        $data['status_id'] == 15 ? $count->sensro_inputs++ : $count->sensro_inputs;     //Sensor投入累計數
     
        $machine = $this->rollerDataService->machine($data);
      
        if ($machine == '捲料機1') {
            $data['status_id'] == 9 ? $count->second_completion++ : $count->second_completion;
        } else {
            $data['status_id'] == 10 ? $count->second_completion++ : $count->second_completion;
        }
 
        $count->serial_number++;    //資料數列順序

        if ($count->resources_id == 0 || $data['orderno'] != $count->resource->orderno) { //料號不相同 或者是 第一筆資料
            $count->machine_completion = 0;
            $count->machine_inputs = 0;
            if ($machine == '捲料機1') {   //相同料號機台累計完工數machine_completion 相同料號機台累計投入數machine_inputs
                $data['status_id'] == 9 ? $count->machine_completion++ : $count->machine_completion;
            } else {
                $data['status_id'] == 10 ? $count->machine_completion++ : $count->machine_completion;
            }
            $data['status_id'] == 10 ? $count->machine_inputs++ : $count->machine_inputs;
        } else {

            $data['status_id'] == 10 ? $count->machine_inputs++ : $count->machine_inputs;

            if ($machine == '捲料機1') {   //相同料號機台累計完工數machine_completion  相同料號機台累計投入數machine_inputs
                $data['status_id'] == 9 ? $count->machine_completion++ : $count->machine_completion;
                $data['status_id'] == 9 ? $count->processing_completion_time = $data['time'] : $count->processing_completion_time = "";
            } else {
                $data['status_id'] == 10 ? $count->machine_completion++ : $count->machine_completion;
                $data['status_id'] == 10 ? $count->processing_completion_time = $data['time'] : $count->processing_completion_time = "";
            }

            if ($machine == '捲料機1') {
                $data['status_id'] == 10 ? $count->processing_start_time = $data['time'] : $count->processing_start_time = "00:00:00";
            } else {
                if ($data['status_id'] == 10) {
                    $count->processing_start_time = $data['time'];
                } else {
                    $completion = $this->machinePerformanceRepo->getLastOpen($count);
                    $restart = $this->machinePerformanceRepo->findPreviousFirstStartCount($count);
                    if ($completion) {
                        if ($completion->machine_completion_day > 0) {
                            $count->processing_start_time = $completion->time;
                        } else {
                            if ($restart) {
                                $count->processing_start_time = $restart->time;
                            }
                        }
                    }
                }
            }
        }

        if ($count->resources_id == 0 || $data['date'] != $count->resource->date) { //累積當天數量
            $count->machine_completion_day = 0;     //同一天累計完工數
            $count->machine_inputs_day = 0;         //同一天累計投入數
            if ($machine == '捲料機1') {
                $data['status_id'] == 9 ? $count->machine_completion_day++ : $count->machine_completion_day;
            } else {
                $data['status_id'] == 10 ? $count->machine_completion_day++ : $count->machine_completion_day;
            }
            $data['status_id'] == 10 ? $count->machine_inputs_day++ : $count->machine_inputs_day;
        } else {
            if ($machine == '捲料機1') {
                $data['status_id'] == 9 ? $count->machine_completion_day++ : $count->machine_completion_day;
            } else {
                $data['status_id'] == 10 ? $count->machine_completion_day++ : $count->machine_completion_day;
            }
            $data['status_id'] == 10 ? $count->machine_inputs_day++ : $count->machine_inputs_day;
            $data['status_id'] == 20 ? $count->refueling_start++ : $count->refueling_start = 0;
            $data['status_id'] == 21 ? $count->refueling_end++ : $count->refueling_end = 0;
            $data['status_id'] == 22 ? $count->aggregate_start++ : $count->aggregate_start = 0;
            $data['status_id'] == 23 ? $count->aggregate_end++ : $count->aggregate_end = 0;
        }

        if ($count->resource) {
            if (($data['orderno'] == '' && $data['date'] != $count->resource->date) || $count->resources_id == 0) { //最初$count->resources_id
                $count->serial_number_day = 1;
            } else {
                if ($data['orderno'] == '' && $data['date'] == $count->resource->date) {
                    if ($count) {

                        $count->serial_number_day++;
                    } else {
                        $count->serial_number_day = 1; //最開始的那筆沒有資料
                    }
                } else {  //料號不為空同日期，前面加總料號數量+1 
                    if ($count) {
                        $count->serial_number_day++;
                    } else {
                        $count->serial_number_day = 1;
                    }
                }
            }
        }
        
        $count->date = $data->date;
        $count->time = $data->time;
        $count->machine = $machine;
       
        return $count;
    }

    public function restart($data)
    {
        $status = $this->counts($data);
        
        $findPreviousId = $this->rollerDataRepo->findPreviousId($data);
        
        is_null($findPreviousId) ? $findPreviousId['status_id'] = 0 : $findPreviousId->status_id;

        if ($status['open'] == '') {
            $status["restart_count"] = '';
        } else {
            if ($status['open'] == '1') {
                $status["restart_count"] = '';
            } else {
                
                if (($status->status_id == 3) && ($findPreviousId['status_id'] == 3)) {
                    $status["restart_count"] = ++$status->restart_count;
                } else {
                    $status["restart_count"] = '';
                }
            }
        }
        if ($status['turn_off'] == '') {
            $status['restop_count'] = '';
        } else {
            if ($status['turn_off'] == '1') {
                $status['restop_count'] = '';
            } else {
                if (($status->status_id == 4) && ($findPreviousId['status_id'] == 4)) {
                    $status['restop_count'] = ++$status->restop_count;
                } else {
                    $status['restop_count'] = '';
                }
            }
        }
       
        return $status;
    }

    public function machineT($data)
    {
        $status = $this->restart($data);
        $machinetime = $this->machinePerformanceRepo->findPreviousMachineInput($data, $status);
        $completionday = $this->machinePerformanceRepo->findCompletionDay($data, $status);
    
        $machineT = 0;
        $secondT = 0;

        if ($status->machine == '捲料機1') {

            if ($data['status_id'] == 10) {
                if ($status->machine_inputs_day >= 2) {
                    if ($machinetime->processing_start_time) {
                        $machineT = strtotime($status->processing_start_time) - strtotime($machinetime->processing_start_time);
                    } else {
                        $machineT = 0;
                    }
                } else {
                    $machineT = 0;
                }
            } else {
                $machineT = 0;
            }
        } else {
            if ($data['status_id'] == 10) {
                $machineT = strtotime($status->processing_completion_time) - strtotime($status->processing_start_time);
            } else {
                $machineT = 0;
            }
        }
        
        $machintime = $this->machinePerformanceRepo->findCompletion($status);
        $previousmachinetime = $this->machinePerformanceRepo->findPreviousCompletion($status);
        $previousmachineday = $this->machinePerformanceRepo->findPreviousCompletionDay($status);
        
        if ($data['status_id'] == 9) {

            if ($status->machine_inputs_day >= 2) {
                if ($completionday == null) {
                    if ($previousmachinetime && $machintime) {
                        $secondT = strtotime($machintime->processing_completion_time) - strtotime($previousmachinetime->processing_completion_time);
                    } else {
                        if ($machintime == null) {
                            $secondT = strtotime($status->processing_completion_time) - strtotime($previousmachinetime->processing_completion_time);
                        }
                    }
                } else {
                    if ($previousmachineday) {
                        $secondT = strtotime($completionday->processing_completion_time) - strtotime($previousmachineday->processing_completion_time);
                    } else {
                        $secondT = strtotime($completionday->processing_completion_time) - strtotime(Carbon::today());
                    }
                }
            } else {
                $secondT = 0;
            }
        } else {
            $secondT = 0;
        }

        $status->roll_t = $machineT;
        $status->second_t = $secondT;
        dd($status);
        return $status;
    }
}