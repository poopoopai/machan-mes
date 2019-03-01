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
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-2 control-label">機台名稱</label>
                                <div class="col-md-10">
                                    <input class="clearable form-control" required>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">機台類別</label>
                                <div class="col-md-10">
                                    <select class="form-control" required>
                                        <option value="">全自動化上下料單機</option>
                                        <option value="">全自動化手動上下料單機</option>
                                        <option value="">半自動化手動上下料單機</option>    
                                        <option value="">全自動化給料多機</option>  
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">製程別</label>
                                <div class="col-md-10">
                                    <select class="form-control" required>
                                        <option value="">一群雷射</option>
                                        <option value="">一群NCT</option>
                                        <option value="">五群NCT</option>    
                                        <option value="">五群P1</option>  
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">班別</label>
                                <div class="col-md-10">
                                    <select class="form-control" required>
                                        <option value="">正常班 _ 標準</option>
                                        <option value="">正常班 _ 加班3小時</option>
                                        <option value="">正常班 _ 加班3.5小時</option>
                                        <option value="">中班 _ 標準</option>
                                        <option value="">晚班 _ 標準</option> 
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                    <label class="col-md-2 control-label">標準換線(分)</label>
                                    <div class="col-md-10">
                                        <input class="clearable form-control" required>
                                    </div>
                                </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">休息類別</label>
                                <div class="col-md-3">
                                    <select class="form-control" required>
                                        <option value="">正常班之休息時段</option>
                                        <option value="">中班之休息時段</option>
                                        <option value="">晚班之休息時段</option>      
                                     </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control" required>
                                        <option value="">正常班之休息時段</option>
                                        <option value="">中班之休息時段</option>
                                        <option value="">晚班之休息時段</option>      
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control" required>
                                        <option value="">正常班之休息時段</option>
                                        <option value="">中班之休息時段</option>
                                        <option value="">晚班之休息時段</option>    
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">適用OEE</label>
                                <div class="col-md-10">
                                    <select class="form-control" required>
                                        <option value="">雷射專用</option>
                                        <option value="">P1&NCT專用</option>   
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
@endsection