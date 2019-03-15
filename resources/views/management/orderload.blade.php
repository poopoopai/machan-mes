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
        <h2>製令單載入</h2>
        <ol class="breadcrumb">
            <img src="{{ asset('img/u12.png') }}">
            <span class="space-item">MES管理</span>
            <span class="space-item">></span>
            <span class="space-item">製令單載入<span>
        </ol>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">資料編輯</div>
                    <div class="panel-body">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-2 control-label">組織</label>
                                <div class="col-md-10">
                                    <select class="form-control" required>
                                            <option value="">一群</option>
                                            <option value="">二群</option>
                                            <option value="">三群</option>
                                            <option value="">四群</option>
                                            <option value="">五群</option>
                                            <option value="">六群</option> 
                                    </select>
                                </div>   
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">工藝路線</label>                       
                                <div class="col-md-10">
                                    <select class="form-control" required>
                                        <option value="">一群-雷射</option>
                                        <option value="">一群-P2</option>
                                        <option value="">一群-NC2</option>  
                                        <option value="">一群-捲料</option>      
                                </select>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">需求開始日期</label>
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
                                <label class="col-md-2 control-label">來源訂單號</label>
                                <div class="col-md-10">
                                    <input class="clearable form-control" required>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">客戶</label>
                                <div class="col-md-10">
                                    <input class="clearable form-control" required>
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