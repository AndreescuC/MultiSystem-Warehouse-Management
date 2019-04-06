/*Sparklines */

/* Sparkline plugin Section starts */

var mValue, tuValue, wValue, thValue, fValue, saValue, suValue;

$("#status1").sparkline([5,6,7,9,9,5,9,6,5,6,6,7,7,6,7,8,9,5 ], {
    type: 'line',
    width: '80',
    height: '20',
    lineColor: '#436B91',
    fillColor: '#fff'});

$("#status2").sparkline([5,6,7,4,9,5,9,6,4,6,6,7,8,6,7,4,9,5 ], {
    type: 'line',
    width: '80',
    height: '20',
    lineColor: '#436B91',
    fillColor: '#fff'});

$("#status3").sparkline([9,6,7,5,9,5,9,7,5,6,3,7,7,6,7,8,8,5 ], {
    type: 'line',
    width: '80',
    height: '20',
    lineColor: '#436B91',
    fillColor: '#fff'});

$("#status4").sparkline([5,2,7,9,9,4,9,6,5,9,6,7,5,6,7,8,4,5 ], {
    type: 'line',
    width: '80',
    height: '20',
    lineColor: '#436B91',
    fillColor: '#fff'});

$("#status5").sparkline([7,6,3,9,9,5,4,6,6,9,6,7,7,6,4,8,9,5 ], {
    type: 'line',
    width: '80',
    height: '20',
    lineColor: '#436B91',
    fillColor: '#fff'});

$("#status6").sparkline([4,6,7,8,9,5,3,6,5,6,7,5,7,2,7,8,7,5 ], {
    type: 'line',
    width: '80',
    height: '20',
    lineColor: '#436B91',
    fillColor: '#fff'});

$("#status7").sparkline([3,6,3,9,9,5,4,6,5,6,4,9,7,6,7,8,8,5 ], {
    type: 'line',
    width: '80',
    height: '20',
    lineColor: '#436B91',
    fillColor: '#fff'});

mValue = $("#todayspark1-data1").html();
tuValue = $("#todayspark1-data2").html();
wValue = $("#todayspark1-data3").html();
thValue = $("#todayspark1-data4").html();
fValue = $("#todayspark1-data5").html();
saValue = $("#todayspark1-data6").html();
suValue = $("#todayspark1-data7").html();

$("#todayspark1").sparkline([mValue, tuValue, wValue, thValue, fValue, saValue, suValue], {
    type: 'bar',
    height: '60',
    barWidth: 40,
    barColor: '#999'});

mValue = $("#todayspark2-data1").html();
tuValue = $("#todayspark2-data2").html();
wValue = $("#todayspark2-data3").html();
thValue = $("#todayspark2-data4").html();
fValue = $("#todayspark2-data5").html();
saValue = $("#todayspark2-data6").html();
suValue = $("#todayspark2-data7").html();
$("#todayspark2").sparkline([mValue, tuValue, wValue, thValue, fValue, saValue, suValue], {
    type: 'bar',
    height: '60',
    barWidth: 40,
    barColor: '#999'});

mValue = $("#todayspark3-data1").html();
tuValue = $("#todayspark3-data2").html();
wValue = $("#todayspark3-data3").html();
thValue = $("#todayspark3-data4").html();
fValue = $("#todayspark3-data5").html();
saValue = $("#todayspark3-data6").html();
suValue = $("#todayspark3-data7").html();
$("#todayspark3").sparkline([mValue, tuValue, wValue, thValue, fValue, saValue, suValue], {
    type: 'bar',
    height: '60',
    barWidth: 40,
    barColor: '#999'});

$("#todayspark4").sparkline([1,2,3,4,5,6,7], {
    type: 'bar',
    height: '60',
    barWidth: 40,
    barColor: '#999'});

$("#todayspark5").sparkline([1,2,3,4,5,6,7], {
    type: 'bar',
    height: '60',
    barWidth: 40,
    barColor: '#999'});


/* Sparkline plugin section ends */

