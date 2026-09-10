$(document).ready(function(){
  var days = JSON.parse($('#data-statistics').attr('days'));
  var parameters = JSON.parse($('#data-statistics').attr('parameters'));
  $(function () {
    //-------------
    //- BAR CHART -
    //-------------
    var areaChartData = {
      labels  : days,
      datasets: [
        {
          label               : 'Doanh Thu',
          backgroundColor     : 'rgba(92,123,217,0.9)',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius         : false,
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : parameters
        },
      ]
    }

    //---------------------
    //- STACKED BAR CHART -
    //---------------------
    var barChartData = $.extend(true, {}, areaChartData);
    var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d');
    var stackedBarChartData = $.extend(true, {}, barChartData);

    var stackedBarChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      scales: {
        xAxes: [{
          stacked: true,
        }],
        yAxes: [{
          stacked: true
        }]
      }
    }

    new Chart(stackedBarChartCanvas, {
      type: 'bar',
      data: stackedBarChartData,
      options: stackedBarChartOptions
    });
  });

  // chart best-selling products
  var lableBestSellProduct = JSON.parse($('#pieChart').attr('label'))
  var dataBestSellProduct = JSON.parse($('#pieChart').attr('data'))
  var donutData        = {
    labels: lableBestSellProduct,
    datasets: [
      {
        data: dataBestSellProduct,
        backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de', '#CC6699', '#00DD00', '#001100', '#FFFF33'],
      }
    ]
  }

  var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
  var pieData        = donutData;
  var pieOptions     = {
    maintainAspectRatio : false,
    responsive : true,
  }
  //Create pie or douhnut chart
  // You can switch between pie and douhnut using the method below.
  new Chart(pieChartCanvas, {
    type: 'pie',
    data: pieData,
    options: pieOptions
  })

    let dateParam = getQueryParam('reservation')
    if (dateParam === null) {
        dateParam = getFirstAndLastDayOfCurrentMonth();
    }
    $('#reservation').daterangepicker(
        {
            locale: {
                format: 'DD/MM/YYYY',
                applyLabel: 'Áp dụng',
                cancelLabel: 'Hủy',
                monthNames: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                daysOfWeek: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7']
            },
            startDate: dateParam.firstDay,
            endDate: dateParam.lastDay,
            maxSpan: {
                "days": 30
            },

        }
    )

});

function getFirstAndLastDayOfCurrentMonth() {
    const now = new Date();

    // Lấy năm và tháng hiện tại
    const year = now.getFullYear();
    const month = now.getMonth();

    // Ngày đầu tiên của tháng hiện tại
    const firstDay = new Date(year, month, 1);

    // Ngày cuối cùng của tháng hiện tại
    const lastDay = new Date(year, month + 1, 0);

    return {
        firstDay: firstDay + '/' + (month + 1) + '/' + year,
        lastDay: lastDay + '/' + (month + 1) + '/' + year
    };
}
function getQueryParam(param) {
    const url = window.location.href;
    // Tạo một đối tượng URL từ chuỗi URL
    const urlObj = new URL(url);

    // Sử dụng phương thức searchParams để lấy giá trị của tham số
    const paramValue = urlObj.searchParams.get(param);

    if (paramValue === null) {
        return null;
    }

    return {
        firstDay: paramValue.split(' - ')[0],
        lastDay: paramValue.split(' - ')[1]
    };
}
