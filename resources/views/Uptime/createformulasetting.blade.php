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
    .adjustment {
        font-size:3px;
        width: 120%;
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
</style>
@endsection

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <h2>公式設定</h2>
        <ol class="breadcrumb">
            <img src="{{ asset('img/u12.png') }}">
            <span class="space-item">稼動參數</span>
            <span class="space-item">></span>
            <span class="space-item">公式設定</span>
            <span class="space-item">></span>
            <span class="space-item">資料編輯頁<span>
        </ol>
        
            <div class="form-group">
                <label class="col-md-2 control-label">公式名稱</label>
                <div class="col-md-5">
                    <input type="text" name='variable' class="form-control" required>
                </div>
                <label class="col-md-2 control-label">公式類別</label>
                <div class="col-md-3">
                    <select name="" id="gettype" class="form-control">
                        <option value="">---請選擇公式類別---</option>
                        <option value="111">機台稼動率</option>
                        <option value="222">性能稼動率</option>
                        <option value="333">良率類</option>
                    </select>
                </div>
            </div>
        <br><br><br>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">主公式設定</div>
                        <div class="panel-body">
                            <label class="col-md-2 control-label">運算符號</label>
                                <button class="btn btn-default" onchange="">+</button>
                                <button class="btn btn-default" >-</button>
                                <button class="btn btn-default" >x</button>
                                <button class="btn btn-default" >÷</button>
                                <button class="btn btn-default" >()</button>
                                <button class="btn btn-default" >Σ</button>
                                <button class="btn btn-default" >公</button>
                                <button class="btn btn-default" >變</button>
                            <hr>
                                <div class="col-md-2" style="padding-top:3px;">
                                    <input type="text" name='variable' class="form-control adjustment" required >
                                </div>
                                    <label class="col-md-1 control-label" style="padding-top:10px;padding-left:35px;" >=</label>
                                <div class="col-md-2" style="padding-top:3px;">
                                      <select name="" id="" class="form-control adjustment">
                                          
                                      </select>
                                </div>
                             
                            
                        </div>
                    </div>
                </div>
            <form class="form-horizontal" action="{{route('inform')}}" method="POST">
                @csrf
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">子公式設定
                            <button class="btn btn-success" onclick="addFormularow()" style="float:right;margin-top: -6px;" type="button">+</button>
                        </div>
                        <div class="panel-body">
                                <label class="col-md-2 control-label">運算符號</label>
                                    <button class="btn btn-default" onclick = "addFormula()" type="button">+</button>
                                    <button class="btn btn-default" onclick = "subtractFormula()" type="button">-</button>
                                    <button class="btn btn-default" onclick = "multiplyFormula()" type="button">x</button>
                                    <button class="btn btn-default" onclick = "divisionFormula()" type="button">÷</button>
                                    <button class="btn btn-default" >()</button>
                                    <button class="btn btn-default" >Σ</button>
                                    <button class="btn btn-default" >變</button>
                                    
                                    <button class="btn btn-default" type="submit" style="float:right;margin-right:1em;">儲存</button>
                                    <button class="btn btn-default" onclick="" style="float:right;margin-right:1em;">+</button>
                                <br><br>
                            <div class="col-md-12">
                                    <div class="col-md-2" style="padding-top:3px;">
                                        <input type="text" name='variable' class="form-control adjustment" required >
                                    </div>
                                        <label class="col-md-1 control-label">=</label>
                                    <div class="col-md-2" style="padding-top:3px;">
                                        <select name="first" id="sum" class="form-control adjustment">
                                          
                                        </select>
                                    </div>
                                    <div id="formula" >
                                        
                                    </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                
                <div id="formularow" >
                                
                                
                </div>
            </div>
        </form>
    </div>
</div>
<script>
   
   let mulaId = 1;
   let rowId = 1;

   const Formula = () => {
    axios.get('{{ route('getdatabase') }}', {

    })
    .then(function ({ data }) {
        $(`#sum`).append(` 
            <option value="${data.data.machine_completion}">機台累積完工數</option>
            <option value="${data.data.machine_inputs}">機台累計投入數</option>
            <option value="${data.data.machine_completion_day}">當天完工數</option>
            <option value="${data.data.machine_inputs_day}">當天投入數</option>
            <option value="${data.data.sensro_inputs}">Sensor投入</option>
            <option value="28800">28800</option>
            <option value="10">10</option>
        `);
    })
    .catch(function (error) {
        console.log(error);
    });
    mulaId++;   
    }         
    Formula();

    const addFormula = () => {
        axios.get('{{ route('getdatabase') }}', {

        })
        .then(function ({ data }) {     
            console.log(data.data);
              
            $('#formula').append(`      
                <div>      
                    <label class="col-md-1 control-label">+</label>
                    <div class="col-md-2" style="padding-top:3px;">
                        <input type="hidden" name="sign${mulaId}" value="+" class="form-control" required >
                        <select name="add${mulaId}" id="sum${mulaId-1}" class="form-control adjustment"> 
                                <option value="${data.data.machine_completion}">機台累積完工數</option>
                                <option value="${data.data.machine_inputs}">機台累計投入數</option>
                                <option value="${data.data.machine_completion_day}">當天完工數</option>
                                <option value="${data.data.machine_inputs_day}">當天投入數</option>
                                <option value="${data.data.sensro_inputs}">Sensor投入</option>
                                <option value="28800">28800</option>
                                <option value="10">10</option>
                        </select>
                    </div>
                </div>
            `);   
        })
        .catch(function ({ data }) {
            alert('今天的資料沒有抓到');
        });
        mulaId++;   
    }
                
             
    
    const subtractFormula = () => {
        axios.get('{{ route('getdatabase') }}', {

        })
        .then(function ({ data }) {        
            $('#formula').append(`      
                <div>      
                    <label class="col-md-1 control-label">-</label>
                    <div class="col-md-2" style="padding-top:3px;">
                        <input type="hidden" name="sign${mulaId}" value="-" class="form-control" required >
                        <select name="subtract${mulaId}" id="sum${mulaId-1}" class="form-control adjustment"> 
                                <option value="${data.data.machine_completion}">機台累積完工數</option>
                                <option value="${data.data.machine_inputs}">機台累計投入數</option>
                                <option value="${data.data.machine_completion_day}">當天完工數</option>
                                <option value="${data.data.machine_inputs_day}">當天投入數</option>
                                <option value="${data.data.sensro_inputs}">Sensor投入</option>
                                <option value="28800">28800</option>
                                <option value="10">10</option>
                        </select>
                    </div>
                </div>
            `);   
        })
        .catch(function (error) {
            console.log(error);
        });
        mulaId++;   
    }
    const multiplyFormula = () => {
        axios.get('{{ route('getdatabase') }}', {

        })
        .then(function ({ data }) {      
                
            $('#formula').append(`      
                <div>      
                    <label class="col-md-1 control-label">x</label>
                    <div class="col-md-2" style="padding-top:3px;">
                        <input type="hidden" name="sign${mulaId}" value="*" class="form-control" required >
                        <select name="multiply${mulaId}" id="sum${mulaId-1}" class="form-control adjustment"> 
                                <option value="${data.data.machine_completion}">機台累積完工數</option>
                                <option value="${data.data.machine_inputs}">機台累計投入數</option>
                                <option value="${data.data.machine_completion_day}">當天完工數</option>
                                <option value="${data.data.machine_inputs_day}">當天投入數</option>
                                <option value="${data.data.sensro_inputs}">Sensor投入</option>
                                <option value="28800">28800</option>
                                <option value="10">10</option>
                        </select>
                    </div>
                </div>
            `);   
        })
        .catch(function (error) {
            console.log(error);
        });
        mulaId++;   
    }
    const divisionFormula = () => {
        axios.get('{{ route('getdatabase') }}', {

        })
        .then(function ({ data }) {   
               
            $('#formula').append(`      
                <div>      
                    <label class="col-md-1 control-label">÷</label>
                    <div class="col-md-2" style="padding-top:3px;">
                        <input type="hidden" name="sign${mulaId}" value="/" class="form-control" required >
                        <select name="division${mulaId}" id="sum${mulaId-1}" class="form-control adjustment"> 
                                <option value="${data.data.machine_completion}">機台累積完工數</option>
                                <option value="${data.data.machine_inputs}">機台累計投入數</option>
                                <option value="${data.data.machine_completion_day}">當天完工數</option>
                                <option value="${data.data.machine_inputs_day}">當天投入數</option>
                                <option value="${data.data.sensro_inputs}">Sensor投入</option>
                                <option value="28800">28800</option>
                                <option value="10">10</option>
                        </select>
                    </div>
                </div>
            `);   
        })
        .catch(function (error) {
            console.log(error);
        });
        mulaId++;   
    }
 

    const addFormularow = () => {
                $('#formula').removeAttr('id');
                
                $('#formularow').append(`   
                    <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">第${rowId+1}子公式設定
                            <button class="btn btn-success" onclick="addFormularow()" style="float:right;margin-top: -6px;" type="button">+</button>
                        </div>
                        <div class="panel-body">
                                <label class="col-md-2 control-label">運算符號</label>
                                    <button class="btn btn-default" onclick="addFormula()" type="button">+</button>
                                    <button class="btn btn-default" onclick="subtractFormula()" type="button">-</button>
                                    <button class="btn btn-default" onclick="multiplyFormula()" type="button">x</button>
                                    <button class="btn btn-default" onclick="divisionFormula()" type="button">÷</button>
                                    <button class="btn btn-default" >()</button>
                                    <button class="btn btn-default" >Σ</button>
                                    <button class="btn btn-default" >變</button>
                                    
                                    <button class="btn btn-default" onclick="" style="float:right;margin-right:1em;">儲存</button>
                                    <button class="btn btn-default" onclick="" style="float:right;margin-right:1em;">+</button>
                                <br><br>
                            <div class="col-md-12">
                                    <div class="col-md-2" style="padding-top:3px;">
                                        <input type="text" name='variable' class="form-control adjustment" required >
                                    </div>
                                        <label class="col-md-1 control-label">=</label>
                                    <div class="col-md-2" style="padding-top:3px;">
                                        <select name="first" id="sum" class="form-control adjustment">
                                          
                                        </select>
                                    </div>
                                    <div id="formula" >
                                        
                                    </div>
                            </div>
                            <div id="formularow" >
                                             
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            `);   
            rowId++;   
    }
  
</script>
@endsection