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
                                    <button id="a" class="btn btn-default" onclick = "addFormula()" type="button">+</button>
                                    <button id="b" class="btn btn-default" onclick = "subtractFormula()" type="button">-</button>
                                    <button id="c" class="btn btn-default" onclick = "multiplyFormula()" type="button">x</button>
                                    <button id="d" class="btn btn-default" onclick = "divisionFormula()" type="button">÷</button>
                                    <button id="e" class="btn btn-default" onclick = "leftFormula()" type="button" style="visibility: hidden;">(</button>
                                    <button id="f" class="btn btn-default" onclick = "rightFormula()" type="button" style="visibility: hidden;">)</button>
                                    <button class="btn btn-default" >Σ</button>
                                    <button id="g"class="btn btn-default"   onclick = "varFormula()" type="button" style="visibility: hidden;">變</button>
                                    {{-- <button id="g"class="btn btn-default"   onclick = "clearFormula()" type="button">清</button> --}}
                                    <button class="btn btn-default" type="submit" style="float:right;margin-right:1em;">儲存</button>
                                    <button class="btn btn-default" onclick="" style="float:right;margin-right:1em;">+</button>
                                <br><br>
                            <div class="col-md-12">
                                    <div class="col-md-2" style="padding-top:3px;">
                                        <input type="text"   name='variable' class="form-control adjustment" required >
                                        <input type="hidden" name='variable_type' value="2"  class="form-control adjustment" required >
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
   
   let mulaId = 0;
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

        mulaId++; 
        document.getElementById('a').style.visibility = 'hidden'
        document.getElementById('b').style.visibility = 'hidden'
        document.getElementById('c').style.visibility = 'hidden'
        document.getElementById('d').style.visibility = 'hidden'
        document.getElementById('e').style.visibility = ''
        document.getElementById('f').style.visibility = 'hidden'
        document.getElementById('g').style.visibility = ''
            $('#formula').append(`      
                <div>      
                    <label class="col-md-1 control-label">+</label>
                    <input id ="sign${mulaId}" type="hidden" name="sign${mulaId}" value="+" class="form-control" required >
                </div>
            `);  
                  
    }
                
    const subtractFormula = () => {  
        mulaId++; 
        document.getElementById('a').style.visibility = 'hidden'
        document.getElementById('b').style.visibility = 'hidden'
        document.getElementById('c').style.visibility = 'hidden'
        document.getElementById('d').style.visibility = 'hidden'
        document.getElementById('e').style.visibility = ''
        document.getElementById('f').style.visibility = 'hidden'
        document.getElementById('g').style.visibility = ''
            $('#formula').append(`      
                <div>      
                    <label class="col-md-1 control-label">-</label>
                    <input id ="sign${mulaId}" type="hidden"  name="sign${mulaId}" value="-" class="form-control" required >
                </div>
            `);   
    }
    const multiplyFormula = () => {      
        mulaId++;
        document.getElementById('a').style.visibility = 'hidden'
        document.getElementById('b').style.visibility = 'hidden'
        document.getElementById('c').style.visibility = 'hidden'
        document.getElementById('d').style.visibility = 'hidden'
        document.getElementById('e').style.visibility = ''
        document.getElementById('f').style.visibility = 'hidden'
        document.getElementById('g').style.visibility = ''
            $('#formula').append(`      
                <div>      
                    <label class="col-md-1 control-label">x</label>
                    <input id ="sign${mulaId}" type="hidden"  name="sign${mulaId}" value="*" class="form-control" required >
                </div>
            `);    
    }
    const divisionFormula = () => {
        mulaId++;
        document.getElementById('a').style.visibility = 'hidden'
        document.getElementById('b').style.visibility = 'hidden'
        document.getElementById('c').style.visibility = 'hidden'
        document.getElementById('d').style.visibility = 'hidden'
        document.getElementById('e').style.visibility = ''
        document.getElementById('f').style.visibility = 'hidden'
        document.getElementById('g').style.visibility = ''
            $('#formula').append(`      
                <div>      
                    <label class="col-md-1 control-label">÷</label>
                    <input id ="sign${mulaId}" type="hidden"  name="sign${mulaId}" value="/" class="form-control" required >
                </div>
            `);   
    }

    const leftFormula = () => {
        mulaId++;
        document.getElementById('a').style.visibility = 'hidden'
        document.getElementById('b').style.visibility = 'hidden'
        document.getElementById('c').style.visibility = 'hidden'
        document.getElementById('d').style.visibility = 'hidden'
        document.getElementById('e').style.visibility = 'hidden'
        document.getElementById('f').style.visibility = 'hidden'
        document.getElementById('g').style.visibility = ''
        $('#formula').append(`      
            <div>      
                <label class="col-md-1 control-label">(</label>
                <input id ="sign${mulaId}" type="hidden"  name="sign${mulaId}" value="(" class="form-control" required >
            </div>
        `);   
    }

    const rightFormula = () => {
        mulaId++;
        document.getElementById('a').style.visibility = ''
        document.getElementById('b').style.visibility = ''
        document.getElementById('c').style.visibility = ''
        document.getElementById('d').style.visibility = ''
        document.getElementById('e').style.visibility = 'hidden'
        
        document.getElementById('g').style.visibility = 'hidden'
        $('#formula').append(`      
            <div >      
                <label class="col-md-1 control-label">)</label>
                <input id ="sign${mulaId}" type="hidden"  name="sign${mulaId}" value=")" class="form-control" required >
            </div>
        `);   


        let count = 0;
        var countArrary = [];
        
        for(bbb = mulaId ; bbb > 0 ; bbb--){

            let aaa = $(`#sign${bbb}`).val();

            if(aaa != null){
                countArrary.unshift(aaa);
            }
        }
        // console.log(countArrary,mulaId);
        for(i = 0 ; i < countArrary.length ; i++ ){
            
            if(countArrary[i] == '('){
                count++;
            }else if (countArrary[i] == ')'){
                count--;
            }else{
                count;
            }

        }
        
        if(count>0){

        document.getElementById('f').style.visibility = ''
        }else{
        document.getElementById('f').style.visibility = 'hidden'
        }
    }

    const clearFormula = () => {
        
        mulaId--;
        $(`#sign${mulaId}`).remove();   
        document.getElementById('a').style.visibility = ''
        document.getElementById('b').style.visibility = ''
        document.getElementById('c').style.visibility = ''
        document.getElementById('d').style.visibility = ''
        document.getElementById('e').style.visibility = ''
        document.getElementById('f').style.visibility = ''
    }
    
    const varFormula = () => {
        axios.get('{{ route('getdatabase') }}', {

        })
        .then(function ({ data }) {     
            mulaId++; 
            document.getElementById('a').style.visibility = ''
            document.getElementById('b').style.visibility = ''
            document.getElementById('c').style.visibility = ''
            document.getElementById('d').style.visibility = ''
            document.getElementById('e').style.visibility = 'hidden'
            
            document.getElementById('g').style.visibility = 'hidden'
            $('#formula').append(`      
                <div>      
                    <div class="col-md-2" style="padding-top:3px;">
                        <select name="var${mulaId}" id="sum${mulaId}" class="form-control adjustment"> 
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

                 
            let count = 0;
            var countArrary = [];
            
            for(bbb = mulaId ; bbb > 0 ; bbb--){

                let aaa = $(`#sign${bbb}`).val();

                if(aaa != null){
                    countArrary.unshift(aaa);
                }
            }
            // console.log(countArrary,mulaId);
            for(i = 0 ; i < countArrary.length ; i++ ){
                
                if(countArrary[i] == '('){
                    count++;
                }else if (countArrary[i] == ')'){
                    count--;
                }else{
                    count;
                }

            }
            console.log(countArrary,count);
            
            if(count>0 && ($(`#sign${mulaId-1}`).val() != '(')){

                document.getElementById('f').style.visibility = ''
            }else{
                document.getElementById('f').style.visibility = 'hidden'
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