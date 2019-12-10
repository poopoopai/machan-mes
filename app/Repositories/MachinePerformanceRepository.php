<?php
    
    namespace App\Repositories;

    use App\Entities\Summary;

    class MachinePerformanceRepository 
    {
        public function findFirstOpen()
        {
            return Summary::where('open', '1')->first();
        }
       
        public function getBeforeData()
        {
            
            $count = Summary::with('resource')->whereRaw('id = (select max(`id`) from summaries)')->first(); //前一筆資料

            if ($count == null) {
                return Summary::create(['resources_id' => 0, 'description' => '', 'processing_start_time' => '00:00:00', 'processing_completion_time' => '00:00:00']);
            }
            
            return $count;
        }

        public function getLastOpen()
        {
            return Summary::where('open', '!=', '')->orderby('id', 'desc')->first();
        }

        public function getLastTurn()
        {
            return Summary::where('turn_off', '!=', '')->orderby('id', 'desc')->first();
        }

        public function getPreviousCompletion($count)
        {
            return Summary::where('machine_completion_day', $count->machine_completion_day - 1)->first();
        }

        public function findPreviousFirstStartCount($count)
        {
            return Summary::where('start_count', $count->start_count)->first(); //建一個累加開或關的
        }

        public function findPreviousMachineInput($data, $status)
        {
            return Summary::where('machine_inputs_day', $status['machine_inputs_day'] - 1)
            ->where('resources_id', '>', 0)->whereHas(
                'resource',
                function ($query) use ($data) {
                    $query->where('date', $data['date']);
                }
            )->first();
        }

        public function findCompletionDay($data, $status)
        {
            return Summary::where('machine_completion_day', $status['machine_inputs_day'])->where('resources_id', '>', 0)->whereHas(
                'resource',
                function ($query) use ($data) {
                    $query->where('date', $data['date']);
                }
            )->first();
        }

        public function findCompletion($status)
        {
            return Summary::where('machine_completion', $status['machine_completion'])->where('resources_id', '>', 0)->first();
        }

        public function findPreviousCompletion($status)
        {
            return Summary::where('machine_completion', $status['machine_completion'] - 1)->where('resources_id', '>', 0)->first();
        }

        public function findPreviousCompletionDay($status)
        {
            return Summary::where('machine_completion_day', $status['machine_completion_day'] - 1)->where('resources_id', '>', 0)->first();
        }

    }
?>