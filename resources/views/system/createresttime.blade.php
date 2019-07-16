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
        <h2>休息時間設定</h2>
        <ol class="breadcrumb">
            <img src="{{ asset('img/u12.png') }}">
            <span class="space-item">系統管理</span>
            <span class="space-item">></span>
            <span class="space-item">休息時間設定<span>
            <span class="space-item">></span>
            <span class="space-item">新增休息時間</span>
        </ol>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">資料新增</div>
                    <div class="panel-body">
                        <form class="form-horizontal" action="{{ route('rest-time.store') }}" method="POST" onsubmit="return judgeTime()">
                            @csrf
                            <div class="form-group">
                                <label class="col-md-2 control-label">班別名稱</label>
                                <div class="col-md-10">
                                    <input class="form-control" name="work_name" required>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">班別類型</label>
                                <div class="col-md-10">
                                    <select name="work_type" class="form-control" required>
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
                            <div id="restRow"></div>
                            <hr>
                            <div style="text-align:center">
                                <button onclick="addRestRow()" class="btn btn-success btn" type="button">+</button>
                                <button onclick="minusRestRow()" class="btn btn-secondary btn" style="width:36px" type="button">-</button>
                            </div>
                            <hr>
                            <div style="text-align:center">
                                <button type="submit" id="sendBtn" class="btn btn-success btn-lg" style="width:45%">確認</button>
                                <button type="reset" class="btn btn-secondary btn-lg" style="width:45%">清除資料</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let restId = 1;
    const addRestRow = () => {
        $('#restRow').append(`
            <div id="restId${restId}">
                <hr>
                <div class="form-group">
                    <label class="col-md-2 control-label">第${restId}段休息</label>
                    <div class="col-md-5">
                        <input id="restRemark${restId}" name="rest_remark[]" class="form-control" required>
                    </div>
                    <div class="col-md-5">
                        <select name="type[]" class="form-control" required>
                            <option value="休息">休息</option>
                            <option value="用餐">用餐</option>
                        </select>
                    </div>
                    <div style="margin-top: 3.5%"></div>
                    <label class="col-md-2 control-label"></label>
                    <div class="col-md-4">
                        <input id='start${restId}' name="rest_time_start[]" type="time" class="form-control" required>
                    </div>
                    <div class="col-md-2" style="text-align:center;">
                        <label style="margin:7px;"> ~ </label>
                    </div>
                    <div class="col-md-4">
                        <input id='end${restId}' name="rest_time_end[]" type="time" class="form-control" required>
                    </div>
                </div>
            </div>
        `);
        restId++;
    }
    const minusRestRow = () => {
        $(`#restId${restId - 1}`).remove();
        (restId == 1) ? restId = 1 : restId--;
    }
    const judgeTime = () => {
        for (let max = 1; max <= restId - 1; max++) {
            let firstCondition = $(`#start${max}`).val() >= $(`#end${max}`).val();
            let secondCondition = $(`#start${max + 1}`).val() <= $(`#end${max}`).val()
            let thirdCondition = $(`#start${max + 1}`).val() >= $(`#end${max + 1}`).val();
            if ( firstCondition || secondCondition || thirdCondition) {
                alert('時間錯誤');
                return false;
            }
        }
    }
</script>
@endsection
