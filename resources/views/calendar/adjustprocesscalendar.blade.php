@extends('layouts.myapp')
@section('css')
<style>
    .panel-default {
        border-color: #000000;
    }
    .panel-default > .panel-heading {
        color: #fff;
        background-color: #000000;
        border-color: #000000;
    }
    .space-item {
        margin-left: 10px;
    }
    .date-style {
        height: 150px;
    }
    .month-container {
        margin-bottom: 12px;
    }
    #calendar-title {
        text-align: center;
        height: 45px;
        font-size: 25px;
        position: relative;
    }
    .week-style {
        text-align: center;
        background-color: #E5F3FA;
    }
    .date-text {
        vertical-align: text-top;
    }
    .calendar td, .calendar th {
		border:1px solid #ddd;
		width:16em;
	}
    .date-text > span > div {
        text-align: right;
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
    .modal-title {
        text-align: center;
        font-size: 30px;
    }
    #work-time {
        text-align: left;
        font-family: Geneva, Arial, Helvetica, sans-serif;
        font-weight: bold;
    }
    .modal-pos {
        text-align: center;
        font-size: 20px;
    }
    .modal-title-pos {
        margin-top: 15px;
    }
    .time-text-color {
        color: red;
    }
    hr {
        border-top: 1px solid #ccc;
    }
    .item-attr {
        font-family: Geneva, Arial, Helvetica, sans-serif;
        font-weight: bold;
    }
</style>
@endsection

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <h2>製程行事曆</h2>
        <ol class="breadcrumb">
            <img src="{{ asset('img/u12.png') }}">
            <span class="space-item">系統管理</span>
            <span class="space-item">></span>
            <span class="space-item">
                <a href="{{ route('process-calendar') }}">製程行事曆</a>
            <span>
            <span class="space-item">></span>
            <span class="space-item">調整製程行事曆</span>    
        </ol>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        製程行事曆 |
                        <span id="panel-title"></span>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-2"></label>
                                <div class="col-md-8">
                                    <select class="form-control" id="sel-org" onchange="changeResourceData()" required>
                                    </select>
                                </div>
                            </div>
                        </form>
                        <div style="text-align:center;">
                            <span class="space-item item-attr">|</span>
                            <span class="space-item item-attr">當月上班 26 天</span>
                            <span class="space-item item-attr">|</span>
                            <span class="space-item item-attr">當月加班 3 天</span>
                            <span class="space-item item-attr">|</span>
                            <span class="space-item item-attr">調休天數 1 天</span>
                            <span class="space-item item-attr">|</span>
                            <span class="space-item item-attr">累計上班時數 217 小時</span>
                            <span class="space-item item-attr">|</span>
                            <span class="space-item item-attr">累計加班時數 25 小時</span>
                            <span class="space-item item-attr">|</span>
                        </div>
                        <hr>
                        <div id="calendar" hidden></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="adjustWork" tabindex="-1" role="dialog" aria-labelledby="adjustWork" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="adjustWork">【班別調整】</h5>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                        <button type="button" class="btn btn-primary" id="sendBtn" onclick="sendWork()" data-dismiss="modal" disabled>確定</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script>
        const arrWeekdays = ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'];
        const arrEnddays = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        const divCalendar = document.getElementById('calendar');
        const isLeapYear = (year) => {
            return !(year % (year % 100 ? 4 : 400));
        }

        const makeYearCalendar = (calendarYear, month) => {
            const monthText = ~~month + 1;
            const start_date = new Date(calendarYear, month, 1);
            const start_weekday = start_date.getDay();
            let endDay = arrEnddays[month];
            let day = 1;
            let count = 0;
            $('#workMonthTitle').append(`公司行事曆(${monthText}月)`);
            let strYearTable = "";
            (month == 1 && isLeapYear(calendarYear)) ? endDay = 29 : endDay;
            strYearTable = `
                <div id='calendar-title'>
                    <span id="calendar-year">${calendarYear} 年 ${monthText} 月</span>
                </div>
            `;
            strYearTable += "<div class='month-container'>";
            strYearTable += "<table class='calendar'>";
            strYearTable += "<tr>";
            arrWeekdays.forEach((weekday) => {
                strYearTable += `<td class="week-style"><span>${weekday}</span></td>`;
            });
            strYearTable += "</tr>"

            while (day <= endDay) {
                strYearTable += "<tr class='date-style'>";
                for (let i = 0; i < 7; i++) {
                    if ((count >= start_weekday) && (day <= endDay)) {
                        strYearTable += `
                            <td class="date-text">
                                <span id='${calendarYear}-${monthText}-${day}'>${day}日
                                    <div>
                                        ${(i == 0 || i == 6)
                                        ? `<button type="button" class="btn btn-secondary btn-xs holiday-num" onclick="workDate()">休假</button>
                                            <div id="work-time"></div>`
                                        : `<button type="button" class="btn btn-success btn-xs default-num" onclick="workDate()">標準班別</button>
                                            <div id="work-time">08:00 ~ 17:20</div>`}
                                    </div>
                                </span>
                            </td>
                        `;
                        day++;
                    } else {
                        strYearTable += "<td></td>";
                    }
                    count++;
                }
                strYearTable += "</tr>";
            }
            strYearTable += "</table>";
            strYearTable += "</div>";
            divCalendar.innerHTML = strYearTable;
        }
        makeYearCalendar('{{ $year }}', '{{ $month -1}}');

        const getCalendarData = (year, month, resourceId) => {
            const textMonth = ~~month + 1;
            axios.get('{{ route('getprocesscalendar') }}', {
                params: {
                    year,
                    month: textMonth,
                    resourceId
                }
            })
            .then(({ data }) => {
                data.forEach((date) => {
                    renderCalendarDate(date);
                });
                $('#calendar').removeAttr('hidden');
            });
        }
        getCalendarData('{{ $year }}', '{{ $month -1 }}', '{{ $resourceId }}');

        const getResourceData = () => {
            axios.get('{{ route('adjust-process-calendar') }}' + location.search)
                .then(({ data }) => {
                    data.forEach(data => {
                        $('#sel-org').append(`
                            <option value="${data.id}">${data.resource_name}</option>
                        `);
                    });
                    $('#sel-org').val('{{ $resourceId }}');
                    const { resource_name }  = _.find(data, ['id', ~~'{{ $resourceId }}']);
                    $('#panel-title').text(resource_name);
                });
        }
        getResourceData();

        const workDate = () => {
            const clickDate = $(event.target).parent().parent().attr('id');
            const weekDays = new Date(clickDate).getDay();
            $('#sendBtn').attr('disabled', '');
            $('#adjustWork').modal('show');
            $(".modal-body").html("");
            $('.modal-body').append(`
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4 modal-pos">日期</div>
                        <div class="col-md-4 modal-pos" id="date">${clickDate} ${arrWeekdays[weekDays]}</div>
                    </div>
                    <div class="row modal-title-pos">
                        <div class="col-md-4 modal-pos">調整班別</div>
                        <div class="col-md-6 modal-pos">
                            <select class="form-control" id="sel1">
                                <option disabled selected>--- 請選擇班別 ---</option>
                                <option value="1">調整班別時間</option>
                                <option value="2">休假不上班</option>
                                <option value="3">國定假日</option>
                            </select>
                        </div>
                    </div>
                    <div class="row modal-title-pos">
                        <div class="col-md-4"></div>
                        <div class="col-md-6 modal-pos">
                            <select class="form-control" id="sel2" disabled>
                                <option disabled selected value="0">--- 請選擇班別時間 ---</option>
                                <option value="1">08:00 ~ 12:00</option>
                                <option value="2">08:00 ~ 17:20</option>
                                <option value="3">08:00 ~ 20:50</option>
                                <option value="4">08:00 ~ 21:20</option>
                            </select>
                        </div>
                    </div>
                    <div class="row modal-title-pos">
                        <div class="col-md-4"></div>
                        <div class="col-md-6">
                            <p style="color:red;">* 當班別調整選擇「修改班別時間」，請再選擇班別時間！</p>
                        </div>
                    </div>
                </div>
            `);

            $('#sel1').change(() => {
                const { value } = event.target;
                if (value === '1') {
                    $('#sel2').removeAttr('disabled');
                    $('#sendBtn').attr('disabled', '');
                } else {
                    $('#sendBtn').removeAttr('disabled');
                    $('#sel2').attr('disabled', '');
                    $('#sel2').val('0');
                }
            });

            $('#sel2').change(() => {
                ~~event.target.value ? $('#sendBtn').removeAttr('disabled') : $('#sendBtn').attr('disabled', '');
            });
        }

        const sendWork = () => {
            const date = $('#date').text().split(" ", 2)[0];
            const workType = $('#sel1')[0].value;
            const workTime = $('#sel2')[0].value;
            const timeperiod = ['12:00', '17:20', '20:50', '21:20'];
            const orgId = $('#sel-org').val();

            axios.post('{{ route('process-calendar-data') }}', {
                resource_id: orgId,
                date,
                start: workType == '1' ? '08:00' : '',
                end: timeperiod[workTime - 1],
                status: workType,
            })
            .then(function ({ data }) {
                renderCalendarDate(data.data);
            })
            .catch(function (error) {
                console.log(error);
            });
        }

        const renderCalendarDate = (data) => {
            const date = data.date.split('-').map((val) => ~~val).join('-');
            const element = $(`#${date} .btn`);
            switch (~~data.status) {
                case 1:
                    let { start, end } = data;
                    start = start.split(':').slice(0, 2).join(':');
                    end = end.split(':').slice(0, 2).join(':');
                    element.attr('class', 'btn btn-danger btn-xs');
                    element.text('調整班別');
                    element.next().html(`${start} ~ ${end}`);
                    element.next().addClass('time-text-color');
                    break;
                case 2:
                    element.attr('class', 'btn btn-secondary btn-xs');
                    element.text('休假');
                    element.next().empty();
                    break;
                case 3:
                    element.attr('class', 'btn btn-primary btn-xs');
                    element.text('國定假日');
                    element.next().empty();
                    break;
            }
        }
        const changeResourceData = () => {
            axios.get('{{ route('getprocesscalendar') }}', {
                params: {
                    year: '{{ $year }}',
                    month: '{{ $month }}',
                    resourceId: $('#sel-org').val(),
                }
            })
            .then(({ data }) => {
                makeYearCalendar('{{ $year }}', '{{ $month -1}}');
                data.forEach((date) => {
                    renderCalendarDate(date);
                });
                const resource_name = $("#sel-org option:selected").text();
                $('#panel-title').text(resource_name);
            });
        }
        console.log($('.default-num').length);
        console.log($('.holiday-num').length);
    </script>
@endsection