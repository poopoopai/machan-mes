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
        <h2>加工時間表</h2>
        <ol class="breadcrumb">
            <img src="{{ asset('img/u12.png') }}">
            <span class="space-item">系統設定</span>
            <span class="space-item">></span>
            <span class="space-item">加工時間表<span>
        </ol>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">資料編輯</div>
                    <div class="panel-body">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-2 control-label">機台</label>
                                <div class="col-md-10">
                                    <select class="form-control" required>
                                        <option value=""></option>
                                        <option value="">一群雷射機</option>
                                        <option value="">一群CNC轉塔沖孔機</option>
                                        <option value="">一群自動拆床機</option>
                                        <option value="">五群CNC轉塔沖孔機</option>
                                        <option value="">五群自動拆床機</option> 
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">物料代碼</label>
                                <div class="col-md-10">
                                    <input class="clearable form-control" required>
                                </div>
                            </div>
                            <hr>
                            <div style="text-align:center">
                                    
                                <button type="submit" onclick="window.location='{{ route('processing-time-result') }}'" id="sendBtn" class="btn btn-success btn-lg" style="width:45%">確認</button>
                            
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