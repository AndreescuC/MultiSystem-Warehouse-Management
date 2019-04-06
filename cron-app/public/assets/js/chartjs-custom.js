$(document).ready(function() {

    $('#archive_shipments').click(function(){
        $.ajax({
            url: "/archive/shipments",
            success: function(result){
                alert("Done Archiving");
            }});
    });

    $('#archive_orders').click(function(){
        $.ajax({
            url: "/archive/orders",
            success: function(result){
                alert("Done Archiving");
            }});
    });
    var shipmentsData = document.getElementById("doughnut2").dataset;
    doughnutDataShipments = [
        {
            value: parseInt(shipmentsData.statsUnderWay),
            color: "#F7464A"
        },
        {
            value: parseInt(shipmentsData.statsDone),
            color: "#46BFBD"
        },
        {
            value: parseInt(shipmentsData.statsArchived),
            color: "#4D5360"
        }

    ];

    var ordersData = document.getElementById("doughnut1").dataset;
    doughnutDataOrders = [
        {
            value: parseInt(ordersData.statsUnderWay),
            color: "#F7464A"
        },
        {
            value: parseInt(ordersData.statsDone),
            color: "#46BFBD"
        },
        {
            value: parseInt(ordersData.statsArchived),
            color: "#4D5360"
        }

    ];

    new Chart(document.getElementById("doughnut1").getContext("2d")).Doughnut(doughnutDataShipments);
    new Chart(document.getElementById("doughnut2").getContext("2d")).Doughnut(doughnutDataOrders);
});
