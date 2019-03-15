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

    .btn-box {
        display:inline-block;
        vertical-align:middle;
        width: 39px;
        height: 25x;
        border-radius:100px;
        border:1px solid #000;
        background-color: #ccc;
        box-shadow: 0px 3px 0px rgba(0,0,0,.13) inset;
    }
    .btn-box .btn {
        margin-left:-1px;
        display:inline-block;
        width: 25px;
        height: 25px;
        border-radius:99em;
        background-color: #fff;
        border:1px solid #000;
        transition: .5s;
        box-shadow:1px 2px 5px rgba(0,0,0,.3);
    }
    .checkbox {
        position:absolute;
        opacity:0;
    }
    .checkbox:checked + .btn-box {
        background-color: #8f8;
        border:none;
    }
    .checkbox:checked + .btn-box .btn {
        margin-left: 12px;
        border:1px solid #ccc;
    }
</style>

@endsection

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <h2>人員帳號設定</h2>
        <ol class="breadcrumb">
            <img src="{{ asset('img/u12.png') }}">
            <span class="space-item">帳號管理</span>
            <span class="space-item">></span>
            <span class="space-item">人員帳號設定<span>
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
                                        <label class="col-md-2 control-label">人員名稱</label>
                                        <div class="col-md-10">
                                            <input class="clearable form-control" required>
                                        </div>
                                </div>
                                 <hr>
                                 <div class="form-group">
                                        <label class="col-md-2 control-label">帳號</label>
                                        <div class="col-md-10">
                                            <input class="clearable form-control" required>
                                        </div>
                                </div>
                                 <hr>
                                 <div class="form-group">
                                        <label class="col-md-2 control-label">密碼</label>
                                        <div class="col-md-10">
                                            <input class="clearable form-control" required>
                                        </div>
                                </div>
                                 <hr>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label"> 所屬組織</label>
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
                                <div class="form-group" style="margin-top:2%">
                                        <label class="col-md-2 control-label">啟用</label>
                                        <div class="col-md-10">
                                            <label class="col-md-3 ">
                                             <span class="text">自動上料&nbsp;
                                                <input type="checkbox" name="" id="" class="checkbox">
                                                    <span class="btn-box">
                                                        <span class="btn"></span>  
                                                    </span> 
                                             </span>
                                           
                                            </label>
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