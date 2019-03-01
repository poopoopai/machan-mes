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
        <h2>班別設定</h2>
        <ol class="breadcrumb">
            <img src="{{ asset('img/u12.png') }}">
            <span class="space-item">系統設定</span>
            <span class="space-item">></span>
            <span class="space-item">班別設定<span>
            <span class="space-item">></span>
            <span class="space-item">資料編輯頁</span>
        </ol>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">資料編輯</div>
                    <div class="panel-body">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-2 control-label">班別名稱</label>
                                <div class="col-md-10">
                                    <input class="form-control" required>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">班別類型</label>
                                <div class="col-md-10">
                                    <input class="form-control" required>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">上班時間</label>
                                    <div class="col-md-4">
                                        <input class="clearable form-control" required>
                                    </div>
                                    <div class="col-md-2" style="text-align:center;">
                                        <label style="margin:7px;"> ~ </label>
                                    </div>
                                    <div class="col-md-4">
                                        <input class="clearable form-control" required>
                                    </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">休息類別一</label>
                                <div class="col-md-10">
                                    <select class="form-control" required>
                                            <option value="">- - - 請選擇休息時段 - - -</option>
                                            <option value="">正常班之休息時段</option>
                                            <option value="">早班之休息時段</option>
                                            <option value="">中班之休息時段</option>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">休息類別二</label>
                                <div class="col-md-10">
                                    <select class="form-control" required >
                                            <option value="">- - - 請選擇休息時段 - - -</option>
                                        <option value="">正常班之休息時段</option>
                                        <option value="">早班之休息時段</option>
                                        <option value="">中班之休息時段</option>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">休息類別三</label>
                                <div class="col-md-10">
                                    <select class="form-control" required>
                                            <option value="">- - - 請選擇休息時段 - - -</option>
                                            <option value="">正常班之休息時段</option>
                                            <option value="">早班之休息時段</option>
                                            <option value="">中班之休息時段</option>
                                    </select>
                                </div>
                            </div>

                           
                               
                            
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