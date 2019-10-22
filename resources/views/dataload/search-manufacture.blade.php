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
        <h2>製令查詢</h2>
        <ol class="breadcrumb">
            <img src="{{ asset('img/u12.png') }}">
            <span class="space-item">資料載入</span>
            <span class="space-item">></span>
            <span class="space-item">製令查詢<span>
        </ol>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">資料搜尋</div>
                    <div class="panel-body">
                        <form class="form-horizontal" action="{{ route('get-manufacture') }}" method="GET">
                            <div class="form-group">
                                <div class="col-md-3">
                                    <select class="form-control" id="sel1" name="condition" disabled>
                                        <option value="3">製令單號</option>
                                    </select>
                                </div>

                                <div class="col-md-9">
                                    <input id="sel2" name="mo_id" class="form-control" required>
                                </div>

                            </div>
                            <hr>
                            <div style="text-align:center">
                                <button type="submit" id="sendBtn" class="btn btn-success btn-lg" style="width:45%">查詢</button>
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
    if ('{{ session('message') }}') {
        alert('{{ session('message') }}');
    }
</script>
@endsection
