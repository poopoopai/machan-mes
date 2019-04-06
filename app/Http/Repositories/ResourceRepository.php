<?php

namespace App\Http\Repositories;
use App\Entities\Resource;
use App\Entities\MainProgram;

class ResourceRepository
{
    public function index($data)
    {
        return Resource::select('id','machine_name','type', 'auto', 'auto_up','interface')->paginate(10);
    }

    public function store($data)
    {

         $mdata = MainProgram::select('status','description','type','codeX')->where('status',$data['status'])->get();

        dd($mdata);


        //  $data = MainProgram::find(10)->resources;
        // $data = Resource::find(10)->mainprogram()->first();

           return $mdata;
    }

}