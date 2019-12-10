<?php
namespace App\Services;
use App\Entities\ErrorCode;
use App\Entities\StandardCt;
use App\Repositories\RollerDataRepository;
use App\Repositories\MainProgramRepository;
use App\Repositories\MachinePerformanceRepository;

class RollerDataService
{
    protected $machinePerformanceRepo;
    protected $rollerDataRepo;
    protected $mainProgramRepo;
    
    public function __construct(MachinePerformanceRepository $machinePerformanceRepository, RollerDataRepository $rollerDataRepository
                                , MainProgramRepository $mainProgramRepository)
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
                $order = StandardCt::where('orderno', $findFirstOpenId->orderno)->with('MachineDefinition')->first();
                $machine = $order->MachineDefinition->machine_name;
            }
        } else {
            $order = StandardCt::where('orderno', $data->orderno)->with('MachineDefinition')->first();
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
            $summary = ErrorCode::where('machine_type', $rollerStatus->type)->where('code', $data['code'])->first();
            return $summary->message;
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

}
        
    
?>