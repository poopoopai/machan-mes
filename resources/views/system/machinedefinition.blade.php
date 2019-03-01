@extends('layouts.myapp')

@section('css')
<style>
    tr th{
        text-align: center;
    }
    tr td{
        text-align: center;
    }
    div .a{
        text-align: right;
        padding-right:25%;
    }
    .space-item {
            margin-left: 10px;
    }

    .slectbtn{
        width:5%;
    }
    
    .breadcrumb-custom {
        background-color: #3D404C;
        width: 99%;
        margin:0px auto;
        padding: 15px 15px;
        margin-bottom: 20px;
        list-style: none;
        border-radius: 4px;
        color: #fff;
    }
    .total-data {
        width: 98%;
        margin:0px auto;
        /* padding: 0px 10px; */
    }
    .total-page {
        width: 98%;
        margin:2% 2% ;
    }
    .table-pos {
        margin: 0px auto;
        width: 98%;
    }
    .thead-color {
        background-color: #E85726;
        color: #fff;
        height: 10px;
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
        </ol>
        <div class="breadcrumb-custom">
            <span>資料列表</span>
            <div style="float:right; margin-top:-7px">
                <button class="btn btn-success">新增</button>
            </div> 
        </div>
        <div class="total-data">載入筆數 | 共 5 筆</div>
        <div style="margin-top:15px;">
            <table class="table table-striped table-pos">
                <thead class="thead-color">
                    <tr>
                        <th scope="col">序</th>
                        <th scope="co1">機台代碼</th>
                        <th scope="col">機台名稱</th>
                        <th scope="col">製程化</th>
                        <th scope="col" >操作</th>

                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>1011L01</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                        <td>
                            <a  href="{{ route('edit-machine-definition') }}">
                                <button class="btn btn-primary" >
                                 編輯
                                </button>
                            </a>
                            &nbsp
                            <button class="btn btn-danger">刪除</button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">2</th>
                        <td>1011L01</td>
                        <td>Thornton</td>
                        <td>@fat</td>
                        <td>
                            <button class="btn btn-primary">編輯</button>
                            &nbsp
                            <button class="btn btn-danger">刪除</button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">3</th>
                        <td>1011L01</td>
                        <td>the Bird</td>
                        <td>@twitter</td>
                        <td>
                            <button class="btn btn-primary">編輯</button>
                            &nbsp
                            <button class="btn btn-danger">刪除</button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">4</th>
                        <td>1011L01</td>
                        <td>the Bird</td>
                        <td>@twitter</td>
                        <td>
                            <button class="btn btn-primary" >編輯</button>
                            &nbsp
                            <button class="btn btn-danger">刪除</button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">5</th>
                        <td>1011L01</td>
                        <td>the Bird</td>
                        <td>@twitter</td>
                        <td>
                            <button class="btn btn-primary">編輯</button>
                            &nbsp
                            <button class="btn btn-danger">刪除</button>
                        </td>
                    </tr>
                </tbody>
            </table>
    </div>
    <form action="">
            <div class="total-page a">每頁顯示筆數
                <select class ="slectbtn" name="" id="">
                        <option value="">10 </option>
                        <option value="">25</option>
                        <option value="">50</option>
                </select>
            </div>
    </form>
</div>
<script>

</script>
@endsection