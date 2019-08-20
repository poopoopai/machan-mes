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
        <h2>APS製程碼</h2>
        <ol class="breadcrumb">
            <img src="{{ asset('img/u12.png') }}">
            <span class="space-item">系統設定</span>
            <span class="space-item">></span>
            <span class="space-item">APS製程碼<span>
            <span class="space-item">></span>
            <span class="space-item">資料編輯頁</span>
        </ol>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">資料編輯</div>
                    <div class="panel-body">
                        <form class="form-horizontal" action="{{ route('aps-processcode.update' , $datas->id)}}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label class="col-md-2 control-label">APS製程碼</label>
                                <div class="col-md-4">
                                    <input name="aps_process_code"  value="{{$datas->aps_process_code}}" class="clearable form-control" onkeyup="value=value.replace(/[^\d]/g,'')"  maxlength="4"required>
                                </div>
                                <div class="col-md-6">
                                    <span>APS 製程碼編碼規則：製程別(2碼)+廠別(1碼)+轉廠別(1碼)，共四碼
                                        製程別：11,雷射、12,NCT、13,P2、20,沖床、30,點焊、40,塗裝、50,裝配
                                        </span>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">製程碼名稱</label>
                                <div class="col-md-10">
                                    <input name="process_description" value="{{$datas->process_description}}" class="clearable form-control" required>
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
@endsection