@extends('layouts.myapp')

@section('css')
<style>
    .bg {
        background-image: url("/img/u13.jpg");
        height: 100%;
        background-position: absolute;
        background-repeat: no-repeat;
        background-size: cover;
    }
    .title-text {
        text-align: center;
        position: absolute;
        top: 50%;
        left: 55%;
        transform: translate(-50%, -50%);
        border: 4px solid rgb(0, 115, 230, 0.3);
        border-radius: 25px;
        width: 25%;
        color: #0072e3;
        font-family: Geneva, Arial, Helvetica, sans-serif, Microsoft JhengHei;
    }
</style>
@endsection

@section('content')
<div id="page-wrapper" class="bg">
    
</div>    
@endsection