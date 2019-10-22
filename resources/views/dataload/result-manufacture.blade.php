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
        <h2>訂單查詢</h2>
        <ol class="breadcrumb">
            <img src="{{ asset('img/u12.png') }}">
            <span class="space-item">資料載入</span>
            <span class="space-item">></span>
            <span class="space-item">訂單查詢<span>
            <span class="space-item">></span>
            <span class="space-item">資料列表</span>
        </ol>
         <div class="breadcrumb-custom">
            <span>資料列表</span>
        </div>
        <div class="total-data">
            載入筆數 |
            <span id="data-num"></span>
        </div>
        <div style="margin-top:15px;">
            <table class="table table-striped table-pos" id="bom-tabel">
                <thead class="thead-color">
                    <tr>
                        <th scope="col">序</th>
                        <th scope="col" id='th1' hidden>訂單單號</th>
                        <th scope="col" id='th2' hidden>制令單號</th>
                        <th scope="col">母件代碼</th>
                        <th scope="col" id="th3">預設工藝路線</th>
                        <th scope="col">明細</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
             <div style="text-align:right">
                <span style="display: inline-block; margin-top: 27px;">
                        <span>每頁顯示筆數</span>
                        <select id="amount" onchange="getBomData();$('#pagination-demo').twbsPagination('destroy');">
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
    const getBomData = (page = 1) => {
        const amount = $('#amount').val();
        axios.get('{{ route('get-bom-data') }}' + location.search, {
            params: {
                condition: '{{ $condition }}',
                parent: '{{ $parent }}',
                amount,
                page,
            }
        }).then(({ data }) => {
            lastPage = data.last_page;
            const bomDatas = data.data;
            $('#data-num').text(`共 ${data.total} 筆`);
            $('#bom-tabel tbody').empty();
            bomDatas.forEach((bomData, key) => {
                switch ('{{ $condition }}') {
                    case '1':
                        $('#bom-tabel tbody').append(`
                            <tr>
                                <th scope="row">${key + 1 + (page - 1) * amount}</th>
                                <td>${bomData.material_id}</td>
                                <td>${bomData.bomkey_name}</td>
                                <td>${bomData.techroutekey_id}</td>
                                <td><a href="/list-bom/${bomData.material_id}" class="btn btn-success btn">查詢</a></td>
                            </tr>
                        `)
                        break;
                    case '2':
                        $('#th1').removeAttr('hidden');
                        $('#th3').hide();
                        $('#bom-tabel tbody').append(`
                            <tr>
                                <th scope="row">${key + 1 + (page - 1) * amount}</th>
                                <td>${bomData.so_id}</td>
                                <td>${bomData.item}</td>
                              
                                <td><a href="/list-bom/${bomData.item}" class="btn btn-success btn">查詢</a></td>
                            </tr>
                        `)
                        break;
                    case '3':
                        $('#th2').removeAttr('hidden');
                        $('#bom-tabel tbody').append(`
                            <tr>
                                <th scope="row">${key + 1 + (page - 1) * amount}</th>
                                <td>${bomData.mo_id}</td>
                                <td>${bomData.item_id}</td>
                                <td>${bomData.item_name}</td>
                                <td>${bomData.techroutekey_id}</td>
                                <td><a href="/list-bom/${bomData.item_id}" class="btn btn-success btn">查詢</a></td>
                            </tr>
                        `)
                        break;
                    default:
                        break;
                }
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
                    getBomData(page)
                }
            });
        });
    }
    getBomData();
</script>
@endsection
