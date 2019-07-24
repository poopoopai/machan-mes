<?php

namespace App\Http\Controllers;
use App\entities\VariableFormula;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class VariableFormulaController extends Controller
{
    
    public function index()
    {
        $data = VariableFormula::select('id','variable', 'variablename', 'remark')->paginate(100);
        return view('uptime/variableformula' , [ 'datas' => $data]);
    }

   
    public function create()
    {
        return view('uptime/createvariableformula');
    }


    public function store(Request $request)
    {
        $avx = request()->only('variable','variablename','remark');
       
       $store = VariableFormula::create(request()->only('variable','variablename','remark'));
    
       return redirect()->route('variable-formula.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $result = VariableFormula::find($id);
        
        return view('uptime/editvariableformula', ['result' => $result]);
    }

    public function update(Request $request, $id)
    {
        
        $variable = VariableFormula::find($id);

        $variable->update(request()->only('variable','variablename','remark'));

        return redirect()->route('variable-formula.index');
    }

    public function destroy($id)
    {
        $variable = VariableFormula::find($id)->delete();

        return back();
    }
}
