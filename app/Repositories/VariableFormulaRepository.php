<?php

namespace App\Repositories;

use App\Entities\VariableFormula;

class VariableFormulaRepository 
{
    public function page()
    {
        return  VariableFormula::select('id', 'variable', 'variablename', 'remark')->paginate(100);
    }

    public function create($data)
    {
        return  VariableFormula::create($data);
    }

    public function find($id)
    {
        return VariableFormula::find($id);
    }

    public function update($id , array $data)
    {
        $varible = VariableFormula::find($id);
        
        return $varible ? $varible->update($data) : false ;
    }
    
    public function destroy($id)
    {
        return  VariableFormula::destroy($id);
    }
} 