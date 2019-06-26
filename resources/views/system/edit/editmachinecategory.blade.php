@extends('layouts.myapp')

@section('css')

<style>
    .space-item {
        margin-left: 10px;
    }
    .panel-default {
        border-color: #000000;
    }
    .panel-default > .panel-heading {
        color: #fff;
        background-color: #000000;
        border-color: #000000;
    }
    .form-horizontal .control-label {
        text-align: center;
    }
    hr {
        border-top: 1px solid #ccc;
    }
    .btn-secondary {
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
    }
    .btn-secondary:hover {
        color: #fff;
        background-color: #5a6268;
        border-color: #545b62;
    }
    .btn.focus, .btn:focus, .btn:hover {
        color: #fff;
    }
    

    .btn-box {
        display:inline-block;
        vertical-align:middle;
        width: 39px;
        height: 25x;
        border-radius:100px;
       
        background-color: #ccc;
        box-shadow: 0px 3px 0px rgba(0,0,0,.13) inset;
    }
    .btn-box .btn {
        margin-left:-1px;
        display:inline-block;
        width: 25px;
        height: 25px;
        border-radius:99em;
        background-color: #fff;
        border:1px solid #000;
        transition: .5s;
        box-shadow:1px 2px 5px rgba(0,0,0,.3);
    }
    .checkbox {
        position:absolute;
        opacity:0;
    }
    .checkbox:checked + .btn-box {
        background-color: #8f8;
        border:none;
    }
    .checkbox:checked + .btn-box .btn {
        margin-left: 12px;
        border:1px solid #ccc;
    }
</style>

@endsection

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <h2>機台類別定義</h2>
        <ol class="breadcrumb">
            <img src="{{ asset('img/u12.png') }}">
            <span class="space-item">系統設定</span>
            <span class="space-item">></span>
            <span class="space-item">機台類別定義<span>
            <span class="space-item">></span>
            <span class="space-item">資料編輯頁</span>
        </ol>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">資料編輯</div>
                    <div class="panel-body">
                    <form class="form-horizontal" action="{{ route('machine-category.update',$data->id)}}" method="POST">
                            @csrf
                            @method("PUT")
                            <div class="form-group">
                                <label class="col-md-2 control-label">機台類別</label>
                                <div class="col-md-10">
                                <input  name="machine_name" type="text" value="{{$data->machine_name}}" class="clearable form-control" required>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">單機 ／ 多機</label>
                                <div class="col-md-10">
                                        <select name="type"class="form-control" required >  
                                            <option value=""></option>          
                                            <option value="SS" {{$data->type=="SS"?'selected':""}}>單工序單機</option>
                                            <option value="SM" {{$data->type=="SM"?'selected':""}}>單工序多機</option>
                                            <option value="MS" {{$data->type=="MS"?'selected':""}}>多工序單機</option>
                                            <option value="MM" {{$data->type=="MM"?'selected':""}}>多工序多機</option>
                                        </select>
                                    </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">自動化</label>
                                <div class="col-md-10">
                                        <select name="auto" class="form-control" required >
                                            <option value=""></option>
                                            <option value="FA"{{$data->auto=="FA"?'selected':""}}>無人全自動化</option>
                                            <option value="HA"{{$data->auto=="HA"?'selected':""}}>人機同步全自動化</option>
                                            <option value="SH"{{$data->auto=="SH"?'selected':""}}>人機同步半自動化</option>
                                            <option value="SA"{{$data->auto=="SA"?'selected':""}}>人機半自動</option>
                                            <option value="MN"{{$data->auto=="MN"?'selected':""}}>手動數控</option>
                                            <option value="MM"{{$data->auto=="MM"?'selected':""}}>手動機械</option>
                                        </select>
                                    </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">人機介面</label>
                                <div class="col-md-10">
                                    <select name="interface"class="form-control" required>
                                            
                                            <option value="可離線生產"{{$data->interface=="可離線生產"?'selected':""}}>可離線生產</option>
                                            <option value="人機同步生產"{{$data->interface=="人機同步生產"?'selected':""}}>人機同步生產</option>
                                            <option value="遠端遙控生產"{{$data->interface=="遠端遙控生產"?'selected':""}}>遠端遙控生產</option>
                                            <option value="無人化自動生產"{{$data->interface=="無人化自動生產"?'selected':""}}>無人化自動生產</option>
                                            <option value="人機手動"{{$data->interface=="人機手動"?'selected':""}}>人機手動</option>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">休息時間可生產</label>
                                    <div class="col-md-10">
                                        <select  name="break_time"class="form-control" required >
                                            <option value=""></option>
                                            <option value="是"{{$data->break_time=="是"?'selected':""}}>是</option>
                                            <option value="否"{{$data->break_time=="否"?'selected':""}}>否</option>
                                            <option value="部分"{{$data->break_time=="部分"?'selected':""}}>部分</option>
                                            <option value="強制"{{$data->break_time=="強制"?'selected':""}}>強制</option>
                                        </select>
                                    </div>
                            </div>
                            <hr>
                             <div class="form-group">
                                <label class="col-md-2 control-label">休息時間可生產</label>
                                <div class="col-md-10">
                                <label class="col-md-3 ">
                                     <span class="text">自動上料&nbsp;
                                         
                                        <input type="hidden" name="auto_up" value="0">
                                        <input type="checkbox" name="auto_up" value="1" class="checkbox" {{$data->auto_up=='1'?'checked':'0'}}>
                                            <span class="btn-box">
                                                <span class="btn"></span>  
                                            </span> 
                                     </span>
                                </label> 
                                
                                <label class="col-md-3 ">
                                        <span class="text">自動下料&nbsp;
                                           <input type="hidden" name="auto_down" value="0">
                                           <input type="checkbox" name="auto_down" value="1" class="checkbox"{{$data->auto_down=='1'?'checked':'0'}} >
                                               <span class="btn-box">
                                                   <span class="btn"></span>  
                                               </span> 
                                        </span>
                                </label> 

                                <label class="col-md-3 ">
                                        <span class="text">排板系統　　&nbsp;
                                            <input type="hidden" name="arrange" value="0">
                                           <input type="checkbox" name="arrange" value="1" class="checkbox" {{$data->arrange=='1'?'checked':'0'}}>
                                               <span class="btn-box">
                                                   <span class="btn"></span>  
                                               </span> 
                                        </span>
                                </label> 

                                <label class="col-md-3 ">
                                        <span class="text">自動加工排程&nbsp;
                                            <input type="hidden" name="auto_arrange" value="0">
                                           <input type="checkbox" name="auto_arrange" value="1" class="checkbox" {{$data->auto_arrange=='1'?'checked':'0'}}>
                                               <span class="btn-box">
                                                   <span class="btn"></span>  
                                               </span> 
                                        </span>
                                </label> 
                                </div>
                            <br>
                            <label class="col-md-2 control-label"></label>
                                <div class="col-md-10">
                                    <label class="col-md-3 ">
                                         <span class="text">自動換模&nbsp;
                                            <input type="hidden" name="auto_change" value="0">
                                            <input type="checkbox" name="auto_change" value="1" class="checkbox" {{$data->auto_change=='1'?'checked':'0'}}>
                                                <span class="btn-box">
                                                    <span class="btn"></span>  
                                                </span> 
                                         </span>
                                    </label> 
                                    
                                    <label class="col-md-3 ">
                                            <span class="text">自動給料&nbsp;
                                                <input type="hidden" name="auto_pay" value="0">
                                               <input type="checkbox" name="auto_pay" value="1" class="checkbox" {{$data->auto_pay=='1'?'checked':'0'}}>
                                                   <span class="btn-box">
                                                       <span class="btn"></span>  
                                                   </span> 
                                            </span>
                                    </label> 
    
                                    <label class="col-md-3 ">
                                            <span class="text">自動完工取料&nbsp;
                                               <input type="hidden" name="auto_finish" value="0">
                                               <input type="checkbox" name="auto_finish" value="1" class="checkbox" {{$data->auto_finish=='1'?'checked':'0'}}>
                                                   <span class="btn-box">
                                                       <span class="btn"></span>  
                                                   </span> 
                                            </span>
                                    </label> 
                                </div>
                                                 
                            </div> 
                            <hr>   
                            <div style="text-align:center">
                             @csrf
                                <button type="submit" id="sendBtn" class="btn btn-success btn-lg" style="width:45%">確認</button>
                                <a class="btn btn-secondary btn-lg" href="{{ route('machine-category.index') }}" style="width:45%">返回</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection