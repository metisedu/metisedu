'use strict';
// Stacked bars
// ------------------------------

// Initialize chart
google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawBarStacked);

// Chart settings
function drawBarStacked() {

    // Data
    var data = google.visualization.arrayToDataTable([
        ['Genre', 'Fantasy & Sci Fi', 'Romance', 'Mystery/Crime', 'General', 'Western', 'Literature', { role: 'annotation' } ],
        ['2000', 20, 30, 35, 40, 45, 30, ''],
        ['2005', 14, 20, 25, 30, 48, 30, ''],
        ['2010', 10, 24, 20, 32, 18, 5, ''],
        ['2015', 15, 25, 30, 35, 20, 15, ''],
        ['2020', 16, 22, 23, 30, 16, 9, ''],
        ['2025', 12, 26, 20, 40, 20, 30, ''],
        ['2030', 28, 19, 29, 30, 12, 13, '']
    ]);

    // Options
    var options_bar_stacked = {
        backgroundColor: chart_bg,
        fontName: 'OpenSans',
        height: 300,
        chartArea: {
            left: '10%',
            width: '100%',
            height: 225
        },
        isStacked: true,
        hAxis: {
            textStyle:{color: chart_grid_text_color}
        },
        vAxis: {
            baselineColor: chart_gridlines_color,
            textStyle:{color: chart_grid_text_color},
            gridlines:{
                color: chart_gridlines_color,
                count: 5
            },
            minValue: 0
        },
        legend: {
            position: 'top',
            alignment: 'center',
            textStyle: {
                color: chart_legends_text_color,
            }
        },
        colors: colors
    };

    // Draw chart
    var bar_stacked = new google.visualization.BarChart($('#google-bar-stacked')[0]);
    bar_stacked.draw(data, options_bar_stacked);
}


// Resize chart
// ------------------------------
$(function () {

    // Resize chart on sidebar width change and window resize
    $(window).on('resize', resize);
    $(".sidebar-control").on('click', resize);

    // Resize function
    function resize() {
        drawBarStacked();
    }
});
