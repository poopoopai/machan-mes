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
        <h2>機台定義</h2>
        <ol class="breadcrumb">
            <img src="{{ asset('img/u12.png') }}">
            <span class="space-item">系統設定</span>
            <span class="space-item">></span>
            <span class="space-item">機台定義<span>
            <span class="space-item">></span>
            <span class="space-item">資料編輯頁</span>
        </ol>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">資料編輯</div>
                    <div class="panel-body">
                    <form class="form-horizontal" action="{{ route('machine-definition.store') }}" method="POST">
                        @csrf
                            <div class="form-group">
                                <label class="col-md-2 control-label">機台名稱</label>
                                <div class="col-md-10">
                                    <input name="machine_name" class="clearable form-control" required>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">機台類別</label>
                                <div class="col-md-10" >
                                        <select name="machine_category" class="form-control" id="machine-name"   required>
                                                <option disabled selected value="">--- 請選擇機台類型 ---</option>
                                        </select>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">製程別</label>
                                <div class="col-md-10">
                                    <select id="ApsProcessCode"  name = "aps_process_code"  class="form-control" required>
                                        <option disabled selected value="">--- 請選擇製程別 ---</option>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">班別</label>
                                <div class="col-md-10">
                                    <select name="group_setting" class="form-control" id="work-type" onchange="getRestId()" required>
                                        <option disabled selected value="">--- 請選擇班別類型 ---</option>
                                        <option value="正常班">正常班</option>
                                        <option value="早班">早班</option>
                                        <option value="中班">中班</option>
                                        <option value="晚班">晚班</option>
                                        <option value="大夜班">大夜班</option>
                                        <option value="混合型">混合型</option>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                    <label class="col-md-2 control-label">標準換線(分)</label>
                                    <div class="col-md-10">
                                        <input name="change_line_time" class="clearable form-control" required>
                                    </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">休息類別</label>
                                <div class="col-md-10">
                                    <select name="class_assign" class="form-control"  id="rest-id" required>
                                        <option disabled selected value="">--- 請選擇 ---</option>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">適用OEE</label>
                                <div class="col-md-10">
                                    <select name="oee_assign" class="form-control" required>
                                        <option disabled selected value="">--- 請選擇OEE類型 ---</option>
                                        <option value="雷射專用">雷射專用</option>
                                        <option value="P1&NCT專用">P1&NCT專用</option>   
                                </select>
                                </div>
                            </div>
                            <hr> 
                            <div style="text-align:center">
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

<script>
    const getRestId = () => {
        axios.get('{{ route('rest-group') }}', {
            params: {
                value: $('#work-type').val()
            }
        })
        .then(({ data }) => {
            $('#rest-id').empty();
            $('#rest-id').append(`
                <option disabled selected value="">--- 請選擇 ---</option>
            `)
            data.forEach(data => {
                $('#rest-id').append(`
                    <option value="${data.id}">${data.rest_name}</option>
                `);
            })
        });
    }
   
    const getMachineId = () => {
        axios.get('{{ route('getMachineData') }}', {
        })
        .then(({ data }) => {
            data.forEach(data => {
                $('#machine-name').append(`
                    <option value="${data.id}"> ${data.machine_name}</option>
                `);  
            })
        });  
    }

    const getApsProcessCode = () => {
        axios.get('{{ route('getApsData') }}', {
        })
        .then(({ data }) => {
            data.forEach(data => {
                $('#ApsProcessCode').append(`
                    <option value="${data.aps_id}"> ${data.process_routing_name}</option>
                `);
            })
        });  
    }
    
    getMachineId();
    getApsProcessCode();
</script>
@endsection