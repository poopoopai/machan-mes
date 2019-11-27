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
    .panel-default {
        border-color: #000000;
    }
    .panel-default > .panel-heading {
        color: #fff;
        background-color: #000000;
        border-color: #000000;
    }
    .btn-secondary {
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
    }
</style>
@endsection

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <h2>機台績效</h2>
        <ol class="breadcrumb">
            <img src="{{ asset('img/u12.png') }}">
            <span class="space-item">系統設定</span>
            <span class="space-item">></span>
            <a href="{{ route('show_machineperformance') }}">機台績效</a><span>
            <span class="space-item">></span>
            <span class="space-item">{{$datas[0]->date}}<span>
        </ol>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">資料編輯</div>
                    <div class="panel-body">
                        <form class="form-horizontal" action="{{ route('search_machineperformance')}}"  method="GET">    
                                <div class="form-group">
                                    <label class="col-md-2 control-label">機台績效日期查詢</label>
                                        <div class="col-md-10">
                                            <input type="date" name="date" class="clearable form-control" required>
                                        </div>
                                </div>
                                <hr>
                            <div style="text-align:center">
                                <button type="submit" onclick="" id="sendBtn" class="btn btn-success btn-lg" style="width:45%">確認</button>
                                <button type="reset" onclick="" class="btn btn-secondary btn-lg" style="width:45%">清除資料</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="total-data">載入筆數 | 共 {{$datas->total()}}  筆</div>
        <div style="margin-top:15px;">
            <table class="table table-striped table-pos">
                <thead class="thead-color">
                    <tr>
                        <th scope="col">序</th>
                        <th scope="col">機台</th>
                        <th scope="col">程序說明</th>
                        <th scope="col">單元</th>
                        <th scope="col">異常狀態</th>
                        <th scope="col">No</th>
                        <th scope="col">當天序號</th>
                        <th scope="col">開機</th>
                        <th scope="co1">關機</th>
                        <th scope="col">當天日期</th>
                        <th scope="col">時間</th>
                        <th scope="col">機台累計完工數</th>
                        <th scope="col">機台累計投入數</th>
                        <th scope="col">當天完工數</th>
                        <th scope="col">當天投入數</th>
                        <th scope="col" >Sensor投入</th>
                        <th scope="col">休息</th>
                        <th scope="co1">休息時間</th>
                        <th scope="col">訊息狀態</th>
                        <th scope="col">停機時間</th>
                        <th scope="col">完工狀態</th>
                        <th scope="col">加工總工時參數</th>
                        <th scope="col">二次元完工數</th>
                        <th scope="col">生產狀態</th>
                        <th scope="col">加工工件開始時間</th>
                        <th scope="co1">加工工件完工時間</th>
                        <th scope="col">作業時間</th>
                        <th scope="col">捲料T/T</th>
                        <th scope="col">二次元T/T</th>
                        <th scope="col">CT(加工時間)秒</th>
                        <th scope="col">實際加工時間</th>
                        <th scope="col">重複開始次數</th>
                        <th scope="col">重複停機次數</th>
                        <th scope="co1">累計開始</th>
                        <th scope="col">累計結束</th>
                        <th scope="col">換料開始</th>
                        <th scope="col">換料結束</th>
                        <th scope="col">換料時間</th>
                        <th scope="col">換料器時間</th>
                        <th scope="col">集料開始</th>
                        <th scope="col">集料結束</th>
                        <th scope="co1">集料時間</th>
                        <th scope="col">集料器時間</th>
                    </tr>
                </thead>
                <tbody>  
                        @foreach ($datas as $key =>$data)
                    <tr>     
                    @if ($data->id>1)
                        <td>{{ $key + ($datas->currentPage() - 1) * 100 }}</td>
                        <td>{{ $data->machine }}</td>
                        <td>{{ $data->description }}</td>
                        <td>{{ $data->type }}</td>
                        <td>{{ $data->abnormal }}</td>
                        <td>{{ $data->serial_number }}</td>
                        <td>{{ $data->serial_number_day }}</td>
                        <td>{{ $data->open }}</td>
                        <td>{{ $data->turn_off }}</td>
                        <td>{{ $data->date }}</td>
                        <td>{{ $data->time }}</td>
                        <td>{{ $data->machine_completion }}</td>
                        <td>{{ $data->machine_inputs }}</td>
                        <td>{{ $data->machine_completion_day }}</td>
                        <td>{{ $data->machine_inputs_day }}</td>
                        <td>{{ $data->sensro_inputs }}</td>
                        <td>{{ $data->break }}</td>
                        <td>{{ $data->break_time }}</td>
                        <td>{{ $data->message_status }}</td>
                        <td>{{ $data->down_time }}</td>
                        <td>{{ $data->completion_status }}</td>
                        <td>{{ $data->total_processing_time }}</td>
                        <td>{{ $data->second_completion }}</td>
                        <td>{{ $data->manufacturing_status }}</td>
                        <td>{{ $data->processing_start_time }}</td>
                        <td>{{ $data->processing_completion_time }}</td>
                        <td>{{ $data->working_time }}</td>
                        <td>{{ $data->roll_t }}</td>
                        <td>{{ $data->second_t }}</td>
                        <td>{{ $data->ct_processing_time }}</td>
                        <td>{{ $data->actual_processing }}</td>
                        <td>{{ $data->restart_count }}</td>
                        <td>{{ $data->restop_count }}</td>
                        <td>{{ $data->start_count }}</td>
                        <td>{{ $data->stop_count }}</td>
                        <td>{{ $data->refueling_start }}</td>
                        <td>{{ $data->refueling_end }}</td>
                        <td>{{ $data->refueling_time }}</td>
                        <td>{{ $data->refueler_time }}</td>
                        <td>{{ $data->aggregate_start }}</td>
                        <td>{{ $data->aggregate_end }}</td>
                        <td>{{ $data->aggregate_time }}</td>
                        <td>{{ $data->collector_time }}</td>                       
                    </tr>
                    @endif
                    @endforeach
                    

                    {{!! $datas->appends(request()->query())->links() !!}}
                </tbody>
            </table>
            
    </div>
    
</div>
<script>
    
</script>
@endsection