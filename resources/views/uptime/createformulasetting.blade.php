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
    .top {
        padding-top:3px;
    }
    input[readonly].top{
        background-color:transparent;
        border: 0;
        font-size: 1em;
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
                                <div class="col-md-2 top">
                                    <input type="text" name='variable' class="form-control " required >
                                </div>
                                    <label class="col-md-1 control-label" style="padding-top:10px;padding-left:35px;" >=</label>
                                <div class="col-md-2 top">
                                      <select name="" id="" class="form-control ">
                                          
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
                                <button id="a" class="btn btn-default" onclick = "addFormula()" type="button">+</button>
                                <button id="b" class="btn btn-default" onclick = "subtractFormula()" type="button">-</button>
                                <button id="c" class="btn btn-default" onclick = "multiplyFormula()" type="button">x</button>
                                <button id="d" class="btn btn-default" onclick = "divisionFormula()" type="button">÷</button>
                                <button id="e" class="btn btn-default" onclick = "leftFormula()" type="button" disabled=true >(</button>
                                <button id="f" class="btn btn-default" onclick = "rightFormula()" type="button" disabled=true>)</button>
                                <button class="btn btn-default" disabled=true>Σ</button>
                                <button id="g"class="btn btn-default"   onclick = "varFormula()" type="button" disabled=true>變</button>
                                <button class="btn btn-default"   onclick = "clearFormula()" type="button">清</button>
                                <button class="btn btn-default" type="submit" style="float:right;margin-right:1em;">儲存</button>
                                <button class="btn btn-default" onclick="" style="float:right;margin-right:1em;">+</button>
                            <br><br>
                            <div class="col-md-12">
                                <div id="formula" class="row">
                                    <div class="col-md-2 top">
                                        <input type="text"   name='variable' class="form-control " required >
                                        <input type="hidden" name='variable_type' value="2"  class="form-control " required >
                                    </div>
                                    <div class="col-md-1 top">
                                        <label class="col-md-1 control-label">=</label>
                                    </div>
                                    <div class="col-md-2 top">
                                        <select name="first" id="sum" class="form-control ">
                                        
                                        </select>
                                    </div>
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
   var optiona =  document.getElementById('a');
   var optionb =  document.getElementById('b');
   var optionc =  document.getElementById('c');
   var optiond =  document.getElementById('d');
   var optione =  document.getElementById('e');
   var optionf =  document.getElementById('f');
   var optiong =  document.getElementById('g');
   
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
   
    }    

    Formula();

    const addFormula = () => {
        optiona.disabled = true;
        optionb.disabled = true;
        optionc.disabled = true;
        optiond.disabled = true;
        optione.disabled = false;
        optionf.disabled = true;
        optiong.disabled = false;
            $('#formula').append(`      
                <div class="col-md-1 top" id ="sign${++mulaId}">      
                    <input  type="text" id="signs${mulaId}" value="+" class="form-control top" required readonly>
                </div>
            `);  
                  
    }
                
    const subtractFormula = () => {  
        optiona.disabled = true;
        optionb.disabled = true;
        optionc.disabled = true;
        optiond.disabled = true;
        optione.disabled = false;
        optionf.disabled = true;
        optiong.disabled = false;
            $('#formula').append(`      
                <div class="col-md-1 top" id ="sign${++mulaId}">      
                    <input  type="button"  id="signs${mulaId}" value="-" class="form-control top" required readonly>
                </div>
            `);   
    }
    const multiplyFormula = () => {      
        optiona.disabled = true;
        optionb.disabled = true;
        optionc.disabled = true;
        optiond.disabled = true;
        optione.disabled = false;
        optionf.disabled = true;
        optiong.disabled = false;
            $('#formula').append(`      
                <div class="col-md-1 top" id ="sign${++mulaId}">      
                    <input  type="text"  id="signs${mulaId}" value="*" class="form-control top" required readonly>
                </div>
            `);    
    }
    const divisionFormula = () => {
        optiona.disabled = true;
        optionb.disabled = true;
        optionc.disabled = true;
        optiond.disabled = true;
        optione.disabled = false;
        optionf.disabled = true;
        optiong.disabled = false;
            $('#formula').append(`      
                <div class="col-md-1 top" id ="sign${++mulaId}">      
                    <input  type="text"  id="signs${mulaId}" value="/" class="form-control top" required readonly>
                </div>
            `);   
    }

    const leftFormula = () => {
        optiona.disabled = true;
        optionb.disabled = true;
        optionc.disabled = true;
        optiond.disabled = true;
        optione.disabled = true;
        optionf.disabled = true;
        optiong.disabled = false;
        $('#formula').append(`      
            <div class="col-md-1 top" id ="sign${++mulaId}">      
                <input  type="text"  id="signs${mulaId}" value="(" class="form-control top" required readonly>
            </div>
        `);   
    }

    const rightFormula = () => {
        optiona.disabled = false;
        optionb.disabled = false;
        optionc.disabled = false;
        optiond.disabled = false;
        optione.disabled = true;
        optione.disabled = true;
        optiong.disabled = true;
        $('#formula').append(`      
            <div class="col-md-1 top" id ="sign${++mulaId}">      
                <input  type="text"  id="signs${mulaId}" value=")" class="form-control top" required readonly>
            </div>
        `);   

        right();
    }
    const right = () =>{

        let count = 0;
        var countArrary = [];
        
        for(i = mulaId ; i > 0 ; i--){

            let rightsign = $(`#signs${i}`).val();

            if(rightsign != null){
                countArrary.unshift(rightsign);
            }
        }
        
        for(j = 0 ; j < countArrary.length ; j++ ){
            
            if(countArrary[j] == '('){
                count++;
            } else if (countArrary[j] == ')'){
                count--;
            } else {
                count;
            }

        }
        
        if(count>0){
            optionf.disabled = false;
        }else{
            optionf.disabled = true;
        }

        
    }

    const clearFormula = () => {
        
        if(mulaId >1) {
            $(`#sign${mulaId}`).remove();
            mulaId--;
           console.log($(`#signs${mulaId}`).val());

           switch ($(`#signs${mulaId}`).val()) {
            case '+':
            case '-':
            case '*':
            case '/':
                optiona.disabled = true;
                optionb.disabled = true;
                optionc.disabled = true;
                optiond.disabled = true;
                optione.disabled = false;
                optionf.disabled = true;
                optiong.disabled = false;
                break;
            case '(':
                optiona.disabled = true;
                optionb.disabled = true;
                optionc.disabled = true;
                optiond.disabled = true;
                optione.disabled = true;
                optionf.disabled = true;
                optiong.disabled = false;
                break;
            case ')':
                optiona.disabled = false;
                optionb.disabled = false;
                optionc.disabled = false;
                optiond.disabled = false;
                optione.disabled = true;
                optione.disabled = true;
                optiong.disabled = true;
                break;
            default:
                optiona.disabled = false;
                optionb.disabled = false;
                optionc.disabled = false;
                optiond.disabled = false;
                optione.disabled = true;
                optionf.disabled = false;
                optiong.disabled = true;

                let count = 0;
                var countArrary = [];
                
                for(i = mulaId ; i > 0 ; i--){

                    let aaa = $(`#signs${i}`).val();
                    
                    if(aaa != null){
                        countArrary.unshift(aaa);
                    }
                }
                
                for(j = 0 ; j < countArrary.length ; j++ ){
                    
                    if(countArrary[j] == '('){
                        count++;
                    }else if (countArrary[j] == ')'){
                        count--;
                    }else{
                        count;
                    }

                }

                if(count>0 && ($(`#signs${mulaId-1}`).val() != '(')){
                    optionf.disabled = false;
                } else{
                    optionf.disabled = true;
                }
                break;
            }

        } else{
            alert("已經不能在刪除了");
        }   
        
    }
    
    const varFormula = () => {
        axios.get('{{ route('getdatabase') }}', {

        })
        .then(function ({ data }) {      
            optiona.disabled = false;
            optionb.disabled = false;
            optionc.disabled = false;
            optiond.disabled = false;
            optione.disabled = true;
            optionf.disabled = false;
            optiong.disabled = true;
            $('#formula').append(`           
                <div class="col-md-2 top" id="sign${++mulaId}">
                    <select name="var${mulaId}"  class="form-control "> 
                            <option value="${data.data.machine_completion}">機台累積完工數</option>
                            <option value="${data.data.machine_inputs}">機台累計投入數</option>
                            <option value="${data.data.machine_completion_day}">當天完工數</option>
                            <option value="${data.data.machine_inputs_day}">當天投入數</option>
                            <option value="${data.data.sensro_inputs}">Sensor投入</option>
                            <option value="28800">28800</option>
                            <option value="10">10</option>
                    </select>
                </div>
            `);   

          
           
            let count = 0;
            var countArrary = [];
            
            for(i = mulaId ; i > 0 ; i--){

                let aaa = $(`#signs${i}`).val();
                
                if(aaa != null){
                    countArrary.unshift(aaa);
                }
            }
            
            for(j = 0 ; j < countArrary.length ; j++ ){
                
                if(countArrary[j] == '('){
                    count++;
                }else if (countArrary[j] == ')'){
                    count--;
                }else{
                    count;
                }

            }

            if(count>0 && ($(`#signs${mulaId-1}`).val() != '(')){
                optionf.disabled = false;
            }else{
                optionf.disabled = true;
            }
        })
        .catch(function (error) {
            console.log(error);
        });
          
        
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
                                <div id="formula" class="row">
                                    <div class="col-md-2 top">
                                        <input type="text"   name='variable' class="form-control " required >
                                        <input type="hidden" name='variable_type' value="2"  class="form-control " required >
                                    </div>
                                    <div class="col-md-1 top">
                                        <label class="col-md-1 control-label">=</label>
                                    </div>
                                    <div class="col-md-2 top">
                                        <select name="first" id="sum" class="form-control ">
                                        
                                        </select>
                                    </div>
                                </div>
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