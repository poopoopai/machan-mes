<?php

namespace App\Repositories;

use App\Entities\RestGroup;
use App\Entities\SetupShift;
use Carbon\Carbon;

class WorkTypeRepository
{
    public function create(array $data)
    {
        // $checkExists = RestGroup::find($data['rest_id'])
        //     ->restSetup
        //     ->filter(function ($item) use ($data) {
        //         return $item->start < $data['work_time_start'] || $item->end > $data['work_time_end'];
        //     })->count();
        // $total = RestGroup::find($id)->restSetup()->selectRaw('SUM(TIME_TO_SEC(end - start))/60 as total')->get();

        $checkExists = RestGroup::find($data['rest_id'])
            ->restSetup()->where(function ($query) use ($data) {
                $query->where('start', '<', $data['work_time_start'])
                    ->orwhere('end', '>', $data['work_time_end']);
            })->exists();

        
        return SetupShift::create([
            'name' => $data['work_name'],
            'type' => $data['work_type'],
            'work_on' => $data['work_time_start'],
            'work_off' => $data['work_time_end'],
            'rest_group' => $data['rest_id'],
        ]);
    }

    public function getRestGroup($data)
    {
        return RestGroup::where('work_type', $data)->get();
    }

    public function getWorkTypeData($amount)
    {
        return SetupShift::paginate($amount);
    }

    public function destroy($id)
    {
        SetupShift::find($id)->relatedCompany()->delete();
        SetupShift::find($id)->relatedProcess()->delete();
        return SetupShift::destroy($id);
    }

    public function find($id)
    {
        return SetupShift::find($id);
    }

    public function update(array $data, $id)
    {
        $workType = SetupShift::find($id);
        return $workType->update([
            'name' => $data['name'],
            'type' => $data['work_type'],
            'work_on' => $data['work_on'],
            'work_off' => $data['work_off'],
            'rest_group' => $data['rest_id'],
        ]);
    }

    public function getWokrTime()
    {
        return SetupShift::get();
    }
}
