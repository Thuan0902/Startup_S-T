$(function () {


  // -----------------------------------------------------------------------
  // sales overview
  // -----------------------------------------------------------------------

  // Sử dụng dữ liệu từ Laravel nếu có, nếu không dùng dữ liệu mẫu
  var chartData = window.threeDayStats || [
    { date_display: 'Hôm kia', article_clicks: 10, product_clicks: 15 },
    { date_display: 'Hôm qua', article_clicks: 20, product_clicks: 25 },
    { date_display: 'Hôm nay', article_clicks: 15, product_clicks: 30 }
  ];

  var options_sales_overview = {
    series: [
      {
        name: "Bài viết",
        data: chartData.map(item => item.article_clicks),
      },
      {
        name: "Sản phẩm",
        data: chartData.map(item => item.product_clicks),
      },
    ],
    chart: {
      type: "bar",
      height: 275,
      toolbar: {
        show: false,
      },
      foreColor: "#adb0bb",
      fontFamily: "inherit",
      sparkline: {
        enabled: false,
      },
    },
    grid: {
      show: false,
      borderColor: "transparent",
      padding: {
        left: 0,
        right: 0,
        bottom: 0,
      },
    },
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: "25%",
        endingShape: "rounded",
        borderRadius: 5,
      },
    },
    colors: ["var(--bs-info)", "var(--bs-primary)"],
    dataLabels: {
      enabled: false,
    },
    yaxis: {
      show: true,
      min: 0,
      tickAmount: 4,
    },
    stroke: {
      show: true,
      width: 5,
      lineCap: "butt",
      colors: ["transparent"],
    },
    xaxis: {
      type: "category",
      categories: chartData.map(item => item.date_display),
      axisBorder: {
        show: false,
      },
    },
    fill: {
      opacity: 1,
    },
    tooltip: {
      theme: "dark",
    },
    legend: {
      show: false,
    },
  };

  var chart_column_basic = new ApexCharts(
    document.querySelector("#sales-overview"),
    options_sales_overview
  );
  chart_column_basic.render();

  // Lưu reference để có thể update từ bên ngoài
  window.chart_column_basic = chart_column_basic;


})