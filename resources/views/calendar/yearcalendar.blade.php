@extends('layouts.myapp')

@section('css')
<style>
th {
    text-align: center;
}
td {
    text-align: center;
}
.calendar {
    margin-bottom: 1%;
    margin-left: 4%;
    width: 20%;
    height: 320px;
    background: #fff;
    float: left;
    box-shadow: 0px 0px 0px 1px rgba(0,0,0,0.1);
}
.border-title-color {
    background-color: #00e3e3;
    height: 7px;
}
.space-item {
    margin-left: 10px;
}
.month-title {
    font-size: 20px
}
.week-color {
    color: red;
}
#calendar-title {
    text-align: center;
    height: 55px;
    font-size: 25px;
    position: relative;
}
</style>
@endsection
@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <h2>公司行事曆</h2>
        <ol class="breadcrumb">
            <img src="{{ asset('img/u12.png') }}">
            <span class="space-item">系統管理</span>
            <span class="space-item">></span>
            <span class="space-item">公司行事曆<span>
        </ol>
        <div id="calendar"></div>
    </div>
</div>
@endsection

@section('js')
<script>
    const today = new Date();//現在時間
    let currentYear = today.getFullYear();//今年
    const arrWeekdays = ['日', '一', '二', '三', '四', '五', '六'];
    const arrEnddays = [31,28,31,30,31,30,31,31,30,31,30,31];
    const divCalendar = document.getElementById('calendar');
    const isLeapYear = (year) => {
       return !(year % (year % 100 ? 4 : 400));//判斷閏年
    }

    const makeMonthTable = (calendarYear, month) => {
        const monthText = ~~month + 1;// ~~用來取整數
        const start_date = new Date(calendarYear, month, 1);
        const start_weekday = start_date.getDay();
        let endDay = arrEnddays[month];
        let day = 1;
        let count = 0;
        let strMonthTable = "";
        (month == 1 && isLeapYear(calendarYear)) ? endDay = 29 : endDay;
        strMonthTable = "<table class='calendar'>";
        strMonthTable += "<tr><th colspan='7' class='border-title-color'></th></tr>"
        strMonthTable += `
            <tr>
                <th colspan='7' class='month-title'>
                    <a href='/calendar/full-calendar/?year=${calendarYear}&month=${monthText}'>${monthText}月</a>
                </th>
            </tr>
        `;
        for (let i = 0; i < 7; i++) {
            if (i == 0 || i == 6) {
                strMonthTable += `<td class="week-color">${arrWeekdays[i]}</td>`;
            } else {
                strMonthTable += `<td>${arrWeekdays[i]}</td>`;
            }
        }
        strMonthTable += "</tr>"

        while (day <= endDay) {
            strMonthTable += "<tr>";
            for (let i = 0; i < 7; i++) {
                let strId = '';
                let strDayNumber = '';
                if ((count >= start_weekday) && (day <= endDay)) {
                    strDayNumber = day;
                    strId = `'id=${calendarYear}/${monthText}/${day}'`
                    day++;
                }
                if (i == 0 || i == 6) {
                    strMonthTable += `<td class='week-color' ${strId}>${strDayNumber}</td>`;
                } else {
                    strMonthTable += `<td ${strId}>${strDayNumber}</td>`;
                }
                count++;
            }
            strMonthTable += "</tr>";
        }
        strMonthTable += "</table>";
        return strMonthTable;
    }

    const makeYearCalendar = (calendarYear) => {
        let strYearCalendar = `
            <div id='calendar-title'>
                <img src="{{ asset('img/prev.png') }}" alt="" id="prev" onclick="prev()">
                <span id="calendar-year">${calendarYear} 年</span>
                <img src="{{ asset('img/next.png') }}" alt="" id="next" onclick="next()">
            </div>
        `;
        for (let i = 0; i < 12; i++) {
            strYearCalendar += "<div class='month-container'>";
            strYearCalendar += makeMonthTable(calendarYear, i);
            strYearCalendar += "</div>";
        }
        divCalendar.innerHTML = strYearCalendar;
    }
    makeYearCalendar(currentYear);

    const prev = () => {
        currentYear--;
        makeYearCalendar(currentYear);
    }

    const next = () => {
        currentYear++;
        makeYearCalendar(currentYear);
    }
</script>
@endsection