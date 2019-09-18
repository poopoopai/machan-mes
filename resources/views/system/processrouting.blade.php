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
        <h2>工藝路線</h2>
        <ol class="breadcrumb">
            <img src="{{ asset('img/u12.png') }}">
            <span class="space-item">系統設定</span>
            <span class="space-item">></span>
            <span class="space-item">工藝路線<span>
        </ol>
        <div class="breadcrumb-custom">
            <span>資料列表</span>
            <div style="float:right; margin-top:-7px">
                <a href="{{ route('syncProcessRouting') }}" class="btn btn-success">同步更新</a>
            </div> 
        </div>
        <div class="total-data">
            載入筆數 |
            <span id="data-num"></span>
        </div>
        <div style="margin-top:15px;">
            <table class="table table-striped table-pos" id="tech-routing-table">
                <thead class="thead-color">
                    <tr>
                        <th scope="col">序</th>
                        <th scope="col">製程名稱</th>
                        <th scope="col">代碼</th>
                        <th scope="col">APS 製程碼</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <div style="text-align:right">
                <span style="display: inline-block; margin-top: 27px;">
                        <span>每頁顯示筆數</span>
                        <select id="amount" onchange="getTechRoutingIndex();$('#pagination-demo').twbsPagination('destroy');">
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                </span>
                <ul id="pagination-demo" class="pagination-sm" style="vertical-align: top;"></ul>
            </div>
        </div>
    </div>
</div>
<script>

    let lastPage;
    const getTechRoutingIndex = (page = 1) => {
        const amount = $('#amount').val();
        axios.get('{{ route('ProcessRoutingIndex') }}',{
            params: {
                amount,
                page,
            }
        }).then(({ data }) => {
            lastPage = data.last_page;

            const orders = data.data;
            $('#data-num').text(`共 ${data.total} 筆`);
            $('#tech-routing-table tbody').empty();
            orders.forEach((order, key) => {
                $('#tech-routing-table tbody').append(`
                    <tr>
                        <th scope="row">${key + 1 + (page - 1) * amount}</th>
                        <td>${order.process_routing_name}</td>
                        <td>${order.process_routing_id}</td>
                        <td>${order.aps_id}</td>
                    </tr>
                `)
            });
            $('#pagination-demo').twbsPagination({
                totalPages: lastPage,
                visiblePages: 5,
                first:'頁首',
                last:'頁尾',
                prev:'<',
                next:'>',
                initiateStartPageClick: false,
                onPageClick: function (event, page) {
                    getTechRoutingIndex(page)
                }
            });
        });
    }
    getTechRoutingIndex();
</script>
@endsection