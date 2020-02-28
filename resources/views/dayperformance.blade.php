@extends('layouts.myapp')

@section('css')
<style>
    tr th{
        text-align: center;
    }
    tr td{
        text-align: center;
    }
    div .a{
        text-align: right;
        padding-right:25%;
    }
    .space-item {
            margin-left: 10px;
    }

    .slectbtn{
        width:5%;
    }
    
    .breadcrumb-custom {
        background-color: #3D404C;
        width: 99%;
        margin:0px auto;
        padding: 15px 15px;
        margin-bottom: 20px;
        list-style: none;
        border-radius: 4px;
        color: #fff;
    }
    .total-data {
        width: 98%;
        margin:0px auto;
        /* padding: 0px 10px; */
    }
    .total-page {
        width: 98%;
        margin:2% 2% ;
    }
    .table-pos {
        margin: 0px auto;
        width: 98%;
    }
    .thead-color {
        background-color: #E85726;
        color: #fff;
        height: 10px;
    }
    .panel-default {
        border-color: #000000;
    }
    .panel-default > .panel-heading {
        color: #fff;
        background-color: #000000;
        border-color: #000000;
    }
    .btn-secondary {
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
    }
    .textcenter{
        text-align:center;
    }
</style>
@endsection

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <h2>機台日績效統計</h2>
        <ol class="breadcrumb">
            <img src="{{ asset('img/u12.png') }}">
            <span class="space-item">系統設定</span>
            <span class="space-item">></span>
            <span class="space-item">機台日績效統計<span>
        </ol>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">資料編輯</div>
                    <div class="panel-body">
                        <form class="form-horizontal" action="{{ route('search_dayperformance_date')}}"  method="GET">
                                <div class="form-group">
                                    <div class="col-md-2 textcenter">
                                        <label class="control-label">機台日績效統計日期查詢</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="date" name="date_start" class="clearable form-control" required>
                                    </div>
                                    <div class="col-md-1 textcenter">
                                        <label class="control-label"> ~ </label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="date" name="date_end" class="clearable form-control" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-2 textcenter">
                                            <label class="control-label">機台</label>
                                    </div>
                                    <div class="col-md-10 textcenter">
                                        <select name="machine" id="machines"  class="clearable form-control">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-2 textcenter">
                                            <label class="control-label">物料代碼</label>
                                    </div>
                                    <div class="col-md-10 textcenter">
                                        <input type="text" name="" class="clearable form-control">
                                    </div>
                                </div>
                                <hr>
                            <div class="textcenter">
                                <button type="submit" onclick="" id="sendBtn" class="btn btn-success btn-lg" style="width:45%">確認</button>
                                <button type="reset" onclick="" class="btn btn-secondary btn-lg" style="width:45%">清除資料</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>    
    </div> 
</div>
<script>
    const getMachineDefiniton = () => {
        axios.get("{{ route('getMachineDefinition') }}")
        .then(({ data }) => {
            $('#machines').empty();
            $('#machines').append(`
                <option disabled selected value="">--- 請選擇 ---</option>
            `)
            data.forEach(data => {
                $('#machines').append(`
                    <option value="${data.machine_name}">${data.machine_name}</option>
                `);
            })
        });
    }
    getMachineDefiniton();
</script>
@endsection