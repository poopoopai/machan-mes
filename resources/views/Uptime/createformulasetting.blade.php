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
        <form class="form-horizontal" action="" method="GET">
            <div class="form-group">
                <label class="col-md-2 control-label">公式名稱</label>
                <div class="col-md-5">
                    <input type="text" name='variable' class="form-control" required>
                </div>
                <label class="col-md-2 control-label">公式類別</label>
                <div class="col-md-3">
                    <select name="" id="" class="form-control">
                        <option value="">---請選擇公式類別---</option>
                        <option value="">機台稼動率</option>
                        <option value="">性能稼動率</option>
                        <option value="">良率類</option>
                    </select>
                </div>
            </div>

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
                                    <input type="text" name='variable' class="form-control" required >
                                </div>
                                    <label class="col-md-1 control-label">=</label>
                                <div class="col-md-2" style="padding-top:3px;">
                                        <input type="text" name='variable' class="form-control" required>
                                </div>
                            <div id="aa" ></div> 
                            
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">子公式設定</div>
                        <div class="panel-body">
                                <label class="col-md-2 control-label">運算符號</label>
                                    <button class="btn btn-default" onclick="addFormula()" type="button">+</button>
                                    <button class="btn btn-default" onclick="subtractFormula()" type="button">-</button>
                                    <button class="btn btn-default" onclick="multiplyFormula()" type="button">x</button>
                                    <button class="btn btn-default" onclick="divisionFormula()" type="button">÷</button>
                                    <button class="btn btn-default" >()</button>
                                    <button class="btn btn-default" >Σ</button>
                                    <button class="btn btn-default" >變</button>
                                    <button class="btn btn-success" onclick="addFormularow()" style="float:right;" type="button">+</button>
                                    <button class="btn btn-default" onclick="" style="float:right;margin-right:1em;">+</button>
                                    <button class="btn btn-default" onclick="" style="float:right;margin-right:1em;">+</button>
                                <hr>
                            <div class="col-md-12">
                                    <div class="col-md-2" style="padding-top:3px;">
                                        <input type="text" name='variable' class="form-control" required >
                                    </div>
                                        <label class="col-md-1 control-label">=</label>
                                    <div class="col-md-2" style="padding-top:3px;">
                                            <input type="text" name='variable' class="form-control" required>
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
        </form>
    </div>
</div>
<script>
   
   let mulaId = 1;
   
    const addFormula = () => {
                $('#formula').append(`      
                <div id="addFormula${mulaId}">      
                    <label class="col-md-1 control-label">+</label>
                    <div class="col-md-2" id="add${mulaId}" style="padding-top:3px;">
                        <input type="text" name='variable' class="form-control" required>
                    </div>
                </div>
            `);      
    }
    const subtractFormula = () => {
                $('#formula').append(`    
                <div id="subtractFormula${mulaId}">    
                    <label class="col-md-1 control-label">-</label>
                    <div class="col-md-2" id="subtract${mulaId}" style="padding-top:3px;">
                        <input type="text" name='variable' class="form-control" required>
                    </div>
                </div>
            `);      
    }
    const multiplyFormula = () => {
                $('#formula').append(`   
                <div id="multiplyFormula${mulaId}">     
                    <label class="col-md-1 control-label">x</label>
                    <div class="col-md-2" id="multiply${mulaId}" style="padding-top:3px;">
                        <input type="text" name='variable' class="form-control" required>
                    </div>
                </div>
            `);      
    }
    const divisionFormula = () => {
                $('#formula').append(` 
                <div id="divisionFormula${mulaId}">       
                    <label class="col-md-1 control-label">÷</label>
                    <div class="col-md-2" id="division${mulaId}" style="padding-top:3px;">
                        <input type="text" name='variable' class="form-control" required>
                    </div>
                </div>   
            `);      
    }
    mulaId++;

    const addFormularow = () => {
                $('#formula').removeAttr('id');
                
                $('#formularow').append(`   
                <div id="mulaId${mulaId}" class="col-md-12">
                <hr> 
                    <div class="col-md-2" style="padding-top:3px;">
                        <input type="text" name='variable' class="form-control" required >
                    </div>
                        <label class="col-md-1 control-label">=</label>
                    <div class="col-md-2" style="padding-top:3px;">
                        <input type="text" name='variable' class="form-control" required>
                    </div>
                    <div id="formula" ></div>
                <div>
            `);      
    }
    
  
</script>
@endsection