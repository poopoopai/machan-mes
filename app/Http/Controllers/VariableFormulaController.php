<?php

namespace App\Http\Controllers;

use App\Repositories\VariableFormulaRepository;

class VariableFormulaController extends Controller
{
    protected $variable;

    public function __construct(VariableFormulaRepository $variableformula)
    {
        $this->variable = $variableformula;
    }
    
    public function index()
    {
        $data =  $this->variable->page();

        return view('uptime/variableformula' , [ 'datas' => $data]);
    }

   
    public function create()
    {
        return view('uptime/createvariableformula');
    }


    public function store()
    {
        $data = $this->variable->create(request()->only('variable', 'variablename', 'remark'));
    
        return redirect()->route('variable-formula.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $data = $this->variable->find($id);

        if (!$data) {
            return redirect()->route('variable-formula.index');
        }
        
        return view('uptime/editvariableformula', ['datas' => $data]);
    }

    public function update($id)
    {    
        $variable = $this->variable->update($id, request()->only('variable','variablename','remark'));
       
        return redirect()->route('variable-formula.index');
    }

    public function destroy($id)
    {
        $variable = $this->variable->destroy($id);

        if($variable){
            return redirect()->route('variable-formula.index');
        }

        return back();
    }
}
