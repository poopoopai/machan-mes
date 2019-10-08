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
        <h2>加工時間</h2>
        <ol class="breadcrumb">
            <img src="{{ asset('img/u12.png') }}">
            <span class="space-item">系統設定</span>
            <span class="space-item">></span>
            <span class="space-item">加工時間<span>
            <span class="space-item">></span>
            <span class="space-item">加工時間表<span>
            <span class="space-item">></span>
            <span class="space-item">資料編輯頁<span>
        </ol>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">資料編輯</div>
                    <div class="panel-body">
                        <form class="form-horizontal" action="{{ route('processing-time.store')}}"  method="POST">
                            @csrf
                                <div class="form-group">
                                    <label class="col-md-2 control-label">物料代碼</label>
                                        <div class="col-md-10">
                                            <input name="orderno" class="clearable form-control" required>
                                        </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">適用機台</label>
                                    <div class="col-md-10">
                                        <select name="machinedefinition_id" class="form-control" required id ="machine-definition">
                                        
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">標準加工秒速</label>
                                        <div class="col-md-10">
                                            <input name="standard_ct" class="clearable form-control"  required>
                                        </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">標準上下料時間</label>
                                        <div class="col-md-10">
                                            <input name="standard_updown" class="clearable form-control" required>
                                        </div>
                                </div>
                                <hr>
                            <div style="text-align:center">
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
        const getMachineDefinition = () => {
                axios.get('{{ route('getMachineDefinition') }}', {
                })
                .then(({ data }) => {
                    $('#machine-definition').empty();
                    $('#machine-definition').append(`
                        <option disabled selected value="">--- 請選擇 ---</option>
                    `)
                    data.forEach(data => {
                        $('#machine-definition').append(`
                            <option value="${data.id}">${data.machine_name}</option>
                        `);
                    })
                });
        }
        getMachineDefinition();

</script>
@endsection