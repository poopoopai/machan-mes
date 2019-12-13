<?php

namespace App\Services;

use App\Entities\ErrorCode;
use App\Repositories\RollerDataRepository;
use App\Repositories\MainProgramRepository;
use App\Repositories\MachinePerformanceRepository;

class RollerDataService
{
    protected $machinePerformanceRepo;
    protected $rollerDataRepo;
    protected $mainProgramRepo;
    
    public function __construct(MachinePerformanceRepository $machinePerformanceRepository, RollerDataRepository $rollerDataRepository,
                                MainProgramRepository $mainProgramRepository)
    {
        $this->machinePerformanceRepo = $machinePerformanceRepository;
        $this->rollerDataRepo = $rollerDataRepository;
        $this->mainProgramRepo = $mainProgramRepository;
    }

    public function machine($data)
    {
        $machine = '';

        if ($data->orderno === null) {
            $findFirstOpen = $this->machinePerformanceRepo->findFirstOpen();
            if ($findFirstOpen) {
                $findFirstOpenId = $this->rollerDataRepo->findFirstOpenId($findFirstOpen);
                $order = $this->mainProgramRepo->findOrderno($findFirstOpenId);
                $machine = $order->MachineDefinition->machine_name;
            }
        } else {
            $order = $this->mainProgramRepo->findOrderno($data);
            if ($order) {
                $machine = $order->MachineDefinition->machine_name;
            } else {
                $machine = null; // 如果沒有找到料號
            }
        }

        return $machine;
    }

    public function abnormal($data)
    {
        $findPreviousId = $this->rollerDataRepo->findPreviousId($data);
        $rollerStatus = $this->mainProgramRepo->description($data);
        $summary = '0';
       
        if ($data['status_id'] == '9' || $data['status_id'] == '10' || $data['status_id'] == '3' || $data['status_id'] == '15' || $data['status_id'] == '16') {
            
            if ($data['orderno'] != $findPreviousId['orderno'] && $findPreviousId['id'] != null) {
                $summary = "換線";
            } else {
                $summary = '0';
            }
        } elseif ($data['code'] == 0) {
            $summary = $rollerStatus->description;
        } elseif ($data['code'] != 0) {
            $summary = ErrorCode::where('machine_type', $rollerStatus->type)->where('code', $data['code'])->pluck('message')->first();
        } else {
            $summary = '0';
        }
        $rollerStatus->abnormal = $summary;
        
        return $rollerStatus;
    }

    public function message($data)
    {
        $rollerAbnormal = $this->abnormal($data);
        $message = '0';

        $rollerAbnormal->abnormal == '0' ? $message = $rollerAbnormal->description : $message = $rollerAbnormal->abnormal;

        if ($data['status_id'] == '3') {
            $message = '開機';
        } elseif ($data['status_id'] == '4') {
            $message = '關機';
        } elseif ($data['status_id'] == '20' || $data['status_id'] == '21') {
            $message = '換料';
        }

        $rollerAbnormal->message_status = $message;

        return  $rollerAbnormal;
    }

    public function completion($data)
    {
        $status = $this->message($data);
        $machine = $this->machine($data);
        $Statusid = $this->rollerDataRepo->findId($data);
        
        $completion = 0;

        if ($Statusid) {
            if ($machine == '捲料機1') {
                if ($data['status_id'] == 9 || $data['status_id'] == 10 || $data['status_id'] == 15 || $data['status_id'] == 16) {

                    if ($data['status_id'] == 9) {
                        $Statusid->status_id - $data['status_id'] == 1 ? $completion = '正常生產' : $completion = '不正常';
                    } else {
                        if ($data['status_id'] == 10) {
                            $Statusid->status_id - $data['status_id'] == 5 ? $completion = '正常生產' : $completion = '不正常';
                        } else {
                            if ($data['status_id'] == 16) {
                                $Statusid->status_id - $data['status_id'] == 6 ? $completion = '正常生產' : $completion = '不正常';
                            } else {
                                if ($data['status_id'] == 15) {
                                    $Statusid->status_id - $data['status_id'] == 6 ? $completion = '正常生產' : $completion = '不正常';
                                } else {
                                    if ($data['status_id'] == 3 || $data['status_id'] == 4 || $data['status_id'] == 20 || $data['status_id'] == 21) {
                                        $completion = $status->message_status;
                                    } else {
                                        $completion = '異常';
                                    }
                                }
                            }
                        }
                    }
                } else {
                    $completion = '異常';
                }
            } else {
                if ($data['status_id'] == 10) {
                    $completion = '正常生產';
                } else {
                    $completion = '異常';
                }
            }
        } else { // 最後一筆
            $completion = '異常';
        }

        $status->completion_status = $completion;

        return $status;
    }
}
