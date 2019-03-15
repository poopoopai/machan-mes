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
                    <form class="form-horizontal" action="{{ route('store-machine-category')}}" method="post">
                        {{ csrf_field() }}
                            <div class="form-group">
                                <label class="col-md-2 control-label">機台類別</label>
                                <div class="col-md-10">
                                    <input  name="type"type="text" value="" class="clearable form-control" required>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">單機 ／ 多機</label>
                                <div class="col-md-10">
                                        <select name="type2"class="form-control" required >
                                            <option value="SS">單工序單機</option>
                                            <option value="SM">單工序多機</option>
                                            <option value="MS">多工序單機</option>
                                            <option value="MM">多工序多機</option>
                                        </select>
                                    </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">自動化</label>
                                <div class="col-md-10">
                                        <select name="auto" class="form-control" required >
                                            <option value="FA">無人全自動化</option>
                                            <option value="HA">人機同步全自動化</option>
                                            <option value="SH">人機同步半自動化</option>
                                            <option value="SA">人機半自動</option>
                                            <option value="MN">手動數控</option>
                                            <option value="MM">手動機械</option>
                                        </select>
                                    </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">人機介面</label>
                                <div class="col-md-10">
                                    <select name="interface"class="form-control" required>
                                            <option value="A">可離線生產</option>
                                            <option value="B">人機同步生產</option>
                                            <option value="C">遠端遙控生產</option>
                                            <option value="D">無人化自動生產</option>
                                            <option value="E">人機手動</option>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">休息時間可生產</label>
                                    <div class="col-md-10">
                                        <select  name="rest"class="form-control" required >
                                            <option value="1">是</option>
                                            <option value="0">否</option>
                                            <option value="2">部分</option>
                                            <option value="3">強制</option>
                                        </select>
                                    </div>
                            </div>
                            <hr>
                             <div class="form-group">
                                <label class="col-md-2 control-label">休息時間可生產</label>
                                <div class="col-md-10">
                                <label class="col-md-3 ">
                                     <span class="text">自動上料&nbsp;
                                        <input type="hidden" name="up" value="0">
                                        <input type="checkbox" name="up" value="1" class="checkbox">
                                            <span class="btn-box">
                                                <span class="btn"></span>  
                                            </span> 
                                     </span>
                                </label> 
                                
                                <label class="col-md-3 ">
                                        <span class="text">自動下料&nbsp;
                                           <input type="hidden" name="down" value="0">
                                           <input type="checkbox" name="down" value="1" class="checkbox" >
                                               <span class="btn-box">
                                                   <span class="btn"></span>  
                                               </span> 
                                        </span>
                                </label> 

                                <label class="col-md-3 ">
                                        <span class="text">排板系統　　&nbsp;
                                            <input type="hidden" name="arrange" value="0">
                                           <input type="checkbox" name="arrange" value="1" class="checkbox" >
                                               <span class="btn-box">
                                                   <span class="btn"></span>  
                                               </span> 
                                        </span>
                                </label> 

                                <label class="col-md-3 ">
                                        <span class="text">自動加工排程&nbsp;
                                            <input type="hidden" name="auto arrange" value="0">
                                           <input type="checkbox" name="auto arrange" value="1" class="checkbox" >
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
                                            <input type="hidden" name="auto change" value="0">
                                            <input type="checkbox" name="auto change" value="1" class="checkbox" >
                                                <span class="btn-box">
                                                    <span class="btn"></span>  
                                                </span> 
                                         </span>
                                    </label> 
                                    
                                    <label class="col-md-3 ">
                                            <span class="text">自動給料&nbsp;
                                                <input type="hidden" name="auto pay" value="0">
                                               <input type="checkbox" name="auto pay" value="1" class="checkbox" >
                                                   <span class="btn-box">
                                                       <span class="btn"></span>  
                                                   </span> 
                                            </span>
                                    </label> 
    
                                    <label class="col-md-3 ">
                                            <span class="text">自動完工取料&nbsp;
                                               <input type="hidden" name="auto finish" value="0">
                                               <input type="checkbox" name="auto finish" value="1" class="checkbox" >
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
                                <button type="reset" onclick="" class="btn btn-secondary btn-lg" style="width:45%">清除資料</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection