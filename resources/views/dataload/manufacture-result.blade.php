@extends('layouts.myapp')

@section('css')
<style>
    .space-item {
        margin-left: 10px;
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
        <h2>載入製令訂單</h2>
        <ol class="breadcrumb">
            <img src="{{ asset('img/u12.png') }}">
            <span class="space-item">資料載入</span>
            <span class="space-item">></span>
            <span class="space-item">載入製令訂單<span>
        </ol>
        <div class="breadcrumb-custom">
            <span>資料列表</span>
        </div>
        <div class="total-data">
            載入筆數 |
            <span id="data-num"></span>
        </div>
        <div style="margin-top:15px;">
            <table class="table table-striped table-pos" id="saleorder-data">
                <thead class="thead-color">
                    <tr>
                        <th scope="col">序</th>
                        <th scope="col">制令單號</th>
                        <th scope="col">來源訂單號</th>
                        <th scope="col">母件</th>
                        <th scope="col">客戶單號</th>
                        <th scope="col">數量</th>
                        <th scope="col">需求開始日期</th>
                        <th scope="col">排單狀態</th>
                    </tr>
                </thead>
                <tbody>
                    {{$mo_id}}
                </tbody>
            </table>
        </div>
       
        <div style="text-align:right">
            <span style="display: inline-block; margin-top: 27px;">
                    <span>每頁顯示筆數</span>
                    <select id="amount" onchange="getSaleOrderData();$('#pagination-demo').twbsPagination('destroy');">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
            </span>
            <ul id="pagination-demo" class="pagination-sm" style="vertical-align: top;"></ul>
        </div>
    </div>
</div>
<script>
    let lastPage;
    const getSaleOrderData = (page = 1) => {
        const amount = $('#amount').val();
        axios.get('{{ route('current-data') }}' + location.search, {
            params: {
                mo_id : '{{ $mo_id }}',
                amount,
                page,
            }
        }).then(({ data }) => {
            lastPage = data.last_page;
            const orders = data.data;
            $('#data-num').text(`共 ${data.total} 筆`);
            $('#saleorder-data tbody').empty();
            orders.forEach((order, key) => {
                $('#saleorder-data tbody').append(`
                    <tr>
                        <th scope="row">${key + 1 + (page - 1) * amount}</th>
                        <td>${order.mo_id}</td>
                        <td>${order.so_id}</td>
                        <td>${order.item_id}</td>
                        <td>${order.customer}</td>
                        <td>${order.qty}</td>
                        <td>${order.online_date}</td>
                        <td>已生效</td>
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
                    getSaleOrderData(page)
                }
            });
        });
    }
    getSaleOrderData();
</script>
@endsection
