(function ($) {
    'use strict';
    $.fn.andSelf = function () {
        return this.addBack.apply(this, arguments);
    }
    $(function () {
        if ($("#transaction-history").length) {
            $.get('/admin/dashboard/transaction-history', function (res) {
                let areaData = {
                    labels: res.chart.labels,
                    datasets: [{
                        data: res.chart.values,
                        backgroundColor: ["#00b4c8", "#E2B84B","#F2F3F5" ]
                    }]
                };

                let areaOptions = {
                    responsive: true,
                    maintainAspectRatio: true,
                    cutoutPercentage: 80,
                    elements: { arc: { borderWidth: 0 } },
                    legend: { display: true, position: 'bottom' },
                    tooltips: { enabled: true }
                };

                let transactionhistoryChartPlugins = {
                    beforeDraw: function (chart) {
                        var ctx = chart.chart.ctx;
                        var width = chart.chart.width;
                        var height = chart.chart.height;

                        ctx.restore();
                        ctx.font = "1rem sans-serif";
                        ctx.fillStyle = "#000";
                        ctx.textAlign = "center";
                        ctx.fillText(`€${res.chart.total.toFixed(2)}`, width / 2, height / 2.4);

                        ctx.font = "0.75rem sans-serif";
                        ctx.fillStyle = "#D92D20";
                        ctx.fillText("Total", width / 2, height / 1.7);
                        ctx.save();
                    }
                };

                var transactionhistoryChartCanvas = $("#transaction-history").get(0).getContext("2d");
                new Chart(transactionhistoryChartCanvas, {
                    type: 'doughnut',
                    data: areaData,
                    options: areaOptions,
                    plugins: transactionhistoryChartPlugins
                });
            });
        }

    });
})(jQuery);

// ====================


$(document).ready(function () {

    $.get('/admin/dashboard/sales-chart', function (res) {
        const ctx = document.getElementById("sales-chart").getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: res.labels, 
                datasets: res.datasets 
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                tooltips: {
                    mode: 'index',
                    backgroundColor: '#fff',
                    titleFontColor: '#000',
                    bodyFontColor: '#000',
                    cornerRadius: 3,
                    intersect: false,
                },
                legend: {
                    labels: {
                        usePointStyle: true,
                        fontFamily: 'Montserrat',
                    },
                },
                scales: {
                    xAxes: [{
                        gridLines: { display: false, drawBorder: false },
                        scaleLabel: { display: false }
                    }],
                    yAxes: [{
                        gridLines: { display: true, drawBorder: false },
                        ticks: { beginAtZero: true },
                        beginAtZero: true,
                        min: 0,
                        max: 2000,
                        ticks: {
                            stepSize: 200
                        }
                    }]
                }


            }
        });
    });
});
