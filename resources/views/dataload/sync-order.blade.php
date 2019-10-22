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
        <h2>載入製令訂單</h2>
        <ol class="breadcrumb">
            <img src="{{ asset('img/u12.png') }}">
            <span class="space-item">資料載入</span>
            <span class="space-item">></span>
            <span class="space-item">載入製令訂單<span>
        </ol>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">載入條件</div>
                    <div class="panel-body">
                        <form class="form-horizontal" action="{{ route('save-sale-order') }}" method="GET">
                            <div class="form-group">
                                <label class="col-md-2 control-label">組織</label>
                                <div class="col-md-10" id="org">
                                    <select class="form-control" id="sel1" name="org_id" required>
                                        <option disabled selected value="">--- 請選擇廠別 ---</option>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">單據日期</label>
                                <div class="col-md-4">
                                    <input class="form-control" type="date" id="bill_date_start" name="bill_date_start" required>
                                </div>
                                <div class="col-md-2" style="text-align:center;">
                                    <label style="margin:7px;"> ~ </label>
                                </div>
                                <div class="col-md-4">
                                    <input class="form-control" type="date" id="bill_date_end" name="bill_date_end" required>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">訂單單號</label>
                                <div class="col-md-10">
                                    <input class="form-control" id="so_id" name="so_id">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">客戶名稱</label>
                                <div class="col-md-10">
                                    <input class="form-control" id="customer_name" name="customer_name">
                                </div>
                                <div id="session-data" hidden>
                                    {{ session('comResults') ?? '' }}
                                </div>
                            </div>
                            <hr>
                            <div style="text-align:center">
                                <button type="submit" id="sendBtn" class="btn btn-success btn-lg" style="width:45%">載入資料</button>
                                <button type="reset" onclick="resetOption()" class="btn btn-secondary btn-lg" style="width:45%">清除資料</button>
                            </div>
                        </form>
                    </div>

                    <div class="panel-heading">載入條件</div>
                    <div class="panel-body">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('sale-order-result-form') }}" method="GET">
                                    <div class="form-group row">
                                        <div class="col-sm-10">
                                            <select name="date" id="date" class="form-control" required>
                                                <option value="">---請選擇同步日期---</option>

                                                @foreach ($dateInfo as $key => $date)
                                                    <option value="{{ $date }}">{{ $date }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-2">
                                            <button class="btn btn-success form-control">搜尋</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const getOrganization = async () => {
        await axios.get('{{ route('getorganization') }}')
            .then(({ data }) => {
                data.forEach(data => {
                    $('#sel1').append(`
                        <option value="${data.factory_id}">${data.name}</option>
                    `);
                });
            });
            if ($('#session-data').text().trim() != '') {
                loadSession();
            }
    }
    getOrganization();

    const loadSession = async () => {
        const saleOrder = JSON.parse('{{ session('comResults') }}'.replace(/&quot;/g,'"'));
        const orgId = '{{ session('org_id') }}';
        let date = new Date();
        let day = date.getDate();
        let month = date.getMonth() + 1;
        let year = date.getFullYear();
        if (month < 10) month = "0" + month;
        if (day < 10) day = "0" + day;
        let today = year + "-" + month + "-" + day;
        let minusHalfMonth = date.setMonth(month - 7);
        let halfDate = new Date(minusHalfMonth);
        let halfDay = halfDate.getDate();
        let halfMonth = halfDate.getMonth() + 1;
        let halfYear = halfDate.getFullYear();
        if (halfMonth < 10) halfMonth = "0" + halfMonth;
        if (halfDay < 10) halfDay = "0" + halfDay;
        let halfToday = halfYear + "-" + halfMonth + "-" + halfDay;
        $('#sel1').val(orgId);
        $('#bill_date_start').val(halfToday);
        $('#bill_date_end').val(today);
        saleOrder.forEach((element, key) => {
            if (key < saleOrder.length - 1) {
                $("#so_id").val(function() {
                    return this.value + element + ',';
                });
            } else {
                $("#so_id").val(function() {
                    return this.value + element;
                });
            }
        });
    }
</script>
@endsection
