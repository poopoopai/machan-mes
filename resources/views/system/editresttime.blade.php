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
                    <div class="panel-heading">編輯休息類別</div>
                    <div class="panel-body">
                        <div class="form-horizontal">
                            <form action="{{ route('rest-time.update', $result->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                            <div class="form-group">
                                <label class="col-md-2 control-label">休息名稱</label>
                                <div class="col-md-10">
                                    <input class="form-control" name="rest_name" value="{{ $result->rest_name }}" required>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">休息類型</label>
                                <div class="col-md-10">
                                    <select name="work_type" class="form-control" required>
                                        <option disabled selected value="">--- 請選擇班別類型 ---</option>
                                        <option value="正常班" {{ $result->work_type === '正常班' ? 'selected' : '' }}>正常班</option>
                                        <option value="早班" {{ $result->work_type === '早班' ? 'selected' : '' }}>早班</option>
                                        <option value="中班" {{ $result->work_type === '中班' ? 'selected' : '' }}>中班</option>
                                        <option value="晚班" {{ $result->work_type === '晚班' ? 'selected' : '' }}>晚班</option>
                                        <option value="大夜班" {{ $result->work_type === '大夜班' ? 'selected' : '' }}>大夜班</option>
                                        <option value="混合型" {{ $result->work_type === '混合型' ? 'selected' : '' }}>混合型</option>
                                    </select>
                                </div>
                            </div>

                                <div style="text-align:center">
                                    <button type="submit" id="sendBtn" class="btn btn-primary">更新</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">編輯休息時間</div>
                    <div class="panel-body">
                        <div class="form-horizontal">
                            <div id="restRow">
                                @foreach ($result->restSetup as $key => $data)
                                    <div id="restId">
                                        <hr>
                                        <div class="form-group">
                                            <form action="{{ route('update-data', [$result->id, $data->id]) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <label class="col-md-2 control-label">第{{ ++$key }}段休息</label>
                                                <div class="col-md-5">
                                                    <input id="restRemark" name="remark" class="form-control" value="{{ $data->remark }}" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <select name="type" class="form-control" required>
                                                        <option disabled selected value="">--- 請選擇類型 ---</option>
                                                        <option value="休息" {{ $data->type === '休息' ? 'selected' : '' }}>休息</option>
                                                        <option value="用餐" {{ $data->type === '用餐' ? 'selected' : '' }}>用餐</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-1">
                                                    <button onclick="" class="btn btn-primary">更新</button>
                                                </div>
                                                <br>
                                                <br>
                                                <label class="col-md-2 control-label"></label>
                                                <div class="col-md-4">
                                                    <input name="start" type="time" class="form-control" value="{{ $data->start }}" required>
                                                </div>
                                                <div class="col-md-1" style="text-align:center;">
                                                    <label style="margin:7px;"> ~ </label>
                                                </div>
                                                <div class="col-md-4">
                                                    <input name="end" type="time" class="form-control" value="{{ $data->end }}" required>
                                                </div>
                                            </form>
                                            <form action="{{ route('delete-data', [$result->id, $data->id]) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <div class="col-md-1">
                                                    <button class="btn btn-danger">刪除</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <hr>
                            <div style="text-align:center">
                                <button onclick="addRestRow()" class="btn btn-success" type="button">+</button>
                                <button onclick="minusRestRow()" class="btn btn-secondary" style="width:36px" type="button">-</button>
                            </div>
                            <hr>
                            <div style="text-align:center">
                                <a class="btn btn-success btn-lg" href="{{ route('rest-time.index') }}" style="width:45%">返回</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    if ("{{ session('message') }}") {
        alert("{{ session('message') }}");
    }
    const restData = '{{!! $result->restSetup !!}}';
    const restGroupData = '{{!! $result->id !!}}'
    const restGroupId = ~~restGroupData.match(/\d+/)[0];
    let qty = JSON.parse('{"restData":' + restData.slice(1)).restData.length;
    let restId = qty;
    const addRestRow = () => {
        $('#restRow').append(`
            <div id="restId${++restId}">
                <hr>
                <div class="form-group">
                    <form action="/rest-time/${restGroupId}" method="POST">
                        @csrf
                        <label class="col-md-2 control-label">第${restId}段休息</label>
                        <div class="col-md-5">
                            <input id="restRemark${restId}" name="remark" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <select name="type" class="form-control" required>
                                <option disabled selected value="">--- 請選擇類型 ---</option>
                                <option value="休息">休息</option>
                                <option value="用餐">用餐</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-success">新增</button>
                        </div>
                        <br>
                        <br>
                        <label class="col-md-2 control-label"></label>
                        <div class="col-md-4">
                            <input name="start" type="time" class="form-control" required>
                        </div>
                        <div class="col-md-1" style="text-align:center;">
                            <label style="margin:7px;"> ~ </label>
                        </div>
                        <div class="col-md-4">
                            <input name="end" type="time" class="form-control" required>
                        </div>
                    </form>
                </div>
            </div>
        `);
    }
    const minusRestRow = () => {
        $(`#restId${restId}`).remove();
        (restId == qty) ? restId = qty : restId--;
    }
</script>
@endsection
