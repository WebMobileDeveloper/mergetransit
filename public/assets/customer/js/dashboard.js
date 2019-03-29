$(document).ready(function () {
   


});

var fromdate = "",
    todate = "";

//Total infor
function get_detail_info(type) {
    get_period(type);

    // url = url + "/" + fromdate + "/" + todate;

    $.get(
        total_get_url, { fromdate: fromdate, todate: todate },
        function (result) {
          
            if (result.status == "ok") {
                var data = result.detail_info;
                $(".statistic h3.detail_number").html(data.detail_num);
                $(".statistic h3.total_revenue").html("$" + data.total_revenue);
                $(".statistic h3.avg_num").html("$" + data.avg_dhrpm);
                $(".statistic h3.total_mile").html(data.total_mile + "KM");
            }
        }
    );

}

//Total infor by employee
function get_detail_bybroker(type) {
    get_period(type);
    $.get(
        total_get_employee_url, { fromdate: fromdate, todate: todate },
        function (result) {
            if (result.status == "ok") {
                var data = result.detail_info_broker;
                console.log("data", data)
                $(".period_total .state_table_emp tr").remove();

                var html = "";
                for (var i = 0; i < data.length; i++) {
                    console.log(data[i])
                    html += "<tr>" +
                        "<td><a href='#'>" + data[i].employee_name + "</a></td>" +
                        "<td>" + data[i].total_miles + "</td>" +
                        "<td>" + data[i].detail_num + "</td>" +
                        "<td>" + data[i].total_revenue + "</td>" +
                        "<td>" + data[i].avg_revenue + "</td>" +
                        "<td>" + data[i].avg_dhrpm + "</td>" +
                        "</tr>";
                }
                $(".period_total .state_table_emp").append(html);
            }
        }
    );

}

function get_period(type) {
    var curr = new Date();
    switch (type) {
        case 'today':

            var firstday = curr;
            var lastday = curr;

            break;
        case 'yesterday': //
            // var firstday = curr;
            // firstday.setDate(firstday.getDate() - 1);
            var firstday = new Date(Date.now() - 86400000);

            // var lastday = curr;
            // lastday.setDate(firstday.getDate() - 1);
            var lastday = new Date(Date.now() - 86400000);
            break;
        case 'n_week': // Current Week
            var currentWeekDay = curr.getDay();
            var lessDays = currentWeekDay == 0 ? 7 : currentWeekDay
            var from = new Date(new Date(curr).setDate(curr.getDate() - lessDays));
            var to = new Date(new Date(from).setDate(from.getDate() + 6));

            var firstday = new Date(from);
            var lastday = new Date(to);

            break;
        case 'l_week': //Last Week

            var to = curr.setTime(curr.getTime() - ((curr.getDay() ? curr.getDay() : 6) + 1) * 24 * 60 * 60 * 1000);
            var from = curr.setTime(curr.getTime() - 6 * 24 * 60 * 60 * 1000);
            var firstday = new Date(from);
            var lastday = new Date(to);

            break;

        case 'n_month': // Current Month

            var firstday = new Date(curr.getFullYear(), curr.getMonth(), 1);
            var lastday = new Date(curr.getFullYear(), curr.getMonth() + 1, 0);

            break;

        case 'l_month': //Last Month

            var firstday = new Date(curr.getFullYear(), curr.getMonth() - 1, 1);
            var lastday = new Date(curr.getFullYear(), curr.getMonth(), 0);
            break;
        case 'total': //select on calendar

            break;
    }

    if (type != 'total') {
        fromdate = firstday.DateFormat();
        todate = lastday.DateFormat();
    } else {
        fromdate = "";
        todate = "";
    }
    console.log(fromdate, todate);

}

//
Date.prototype.DateFormat = function () {
    var mm = this.getMonth() + 1; // getMonth() is zero-based
    var dd = this.getDate();
    var yyyy = this.getFullYear();
    return [this.getFullYear(), "-", (mm > 9 ? '' : '0') + mm, "-", (dd > 9 ? '' : '0') + dd].join('');
    // return [this.getFullYear(),  (mm > 9 ? '' : '0') + mm,    (dd > 9 ? '' : '0') + dd ].join('');
};

function loadBillings_chart() {
    //get yearly data with ajax
    $.get(
        yearly_get_url,
        function (result) {
            console.log(result)
            draw_chart(result);

        }
    );
}

function draw_chart(billing_data) {


    var billing_ctx = $("#billings").get(0).getContext("2d");

    var billingChart = new Chart(billing_ctx, {
        type: 'bar',
        data: billing_data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            //  scaleShowGridLines: true,
            scales: {
                yAxes: [{
                    ticks: { beginAtZero: true },
                }],
                xAxes: [{

                    categoryPercentage: 0.5,
                    barPercentage: 0.5,
                    gridLines: { display: false }
                }]
            },

            tooltips: {

                callbacks: {
                    label: function (tooltipItem, data) {
                        var tooltipEl = document.getElementById('chartjs-tooltip');
                        var spnahtml = tooltipItem.xLabel;
                        var html = "";
                        html += "<tr>" +
                            "<th></th>" +
                            "<th>Year</th>" +
                            "<th>Total Load</th>" +
                            "<th>Total Revenue</th>" +
                            "<th>Total Miles</th>" +
                            "<th>Drivers</th>" +
                            "<th>Avg RPM</th>" +
                            "<th>Avg Revenue</th>" +
                            "</tr>";
                        for (var dataset in data.datasets) {
                            html += "<tr>" +
                                "<td><span class='tipColor' style='background:" + data.datasets[dataset].backgroundColor + "'></span></td>" +
                                "<td>" + data.datasets[dataset].label + "</td>" +
                                "<td>" + data.datasets[dataset].data[tooltipItem.index] + "</td>" +
                                "<td style='color:" + data.datasets[dataset].backgroundColor + "'>$" + data.datasets[dataset].total_revenue[tooltipItem.index] + "</td>" +
                                "<td style='color:" + data.datasets[dataset].backgroundColor + "'>" + data.datasets[dataset].total_miles[tooltipItem.index].toLocaleString() + "</td>" +
                                "<td>" + data.datasets[dataset].driver_count[tooltipItem.index] + "</td>" +
                                "<td>$" + data.datasets[dataset].avg_dhrpm[tooltipItem.index] + "</td>" +
                                "<td>$" + data.datasets[dataset].avg_revenue[tooltipItem.index] + "</td>" +
                                "</tr>";
                        }
                        var spanroot = tooltipEl.querySelector('div.span');
                        spanroot.innerHTML = spnahtml;
                        var tableRoot = tooltipEl.querySelector('table');
                        tableRoot.innerHTML = html;

                        var position = $(billingChart.chart.canvas).offset();
                        var chartheight = billingChart.chart.height;
                        tooltipEl.style.opacity = 1;
                        if ($(window).width() < 592) {
                            tooltipEl.style.left = '20px';
                            tooltipEl.style.top = '-70px';
                        } else if (($("#chartjs-tooltip").width() + tooltipItem.x + 50) > $(".content").width()) {
                            tooltipEl.style.right = '20px';
                            tooltipEl.style.top = (tooltipItem.y - chartheight / 3) + 'px';
                        } else {
                            tooltipEl.style.left = tooltipItem.x + 'px';
                            tooltipEl.style.top = (tooltipItem.y - chartheight / 3) + 'px';
                        }



                        tooltipEl.style.display = 'block';

                    }

                },

            },


        }
    });
    $("#chartjs-tooltip").click(function () {
        $("#chartjs-tooltip").css('display', 'none')
    })

}