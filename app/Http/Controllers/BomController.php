<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\SaleOrder;

class BomController extends Controller
{
    public function index()
    {
        return view('dataload/search-bom');
    }
    public function resultBom()
    {
        $data = request()->only('condition', 'parent');
        return view('dataload/result-bom', ['condition' => $data['condition']], ['parent' => $data['parent']]);
    }
    public function getBomData()
    {
        $data = request()->only('condition', 'parent');
        if ($data['condition'] == 2) {
            $result = SaleOrder::where('so_id', $data['parent']);
        } else {
            return redirect()->route('search-bom')->with('message', 'The parameter undefine');
        }
        return response()->json($result->paginate(request()->amount));
    }

    
}
