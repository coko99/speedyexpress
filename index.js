var ctx = document.getElementById("myChart").getContext("2d");
var myChart = new Chart(ctx, {
  type: "line",
  data: {
    labels: [
      "Jan",
      "Feb",
      "Mar",
      "Apr",
      "May",
      "Jun",
      "Jul",
      "Aug",
      "Sep",
      "Oct",
      "Nov",
      "Dec",
    ],
    datasets: [
      {
        label: "Broj nečega",
        data: [12, 19, 3, 5, 2, 3, 10, 15, 7, 9, 11, 8],
        backgroundColor: "rgba(0, 119, 204, 0.3)",
        borderColor: "rgba(0, 119, 204, 0.7)",
        borderWidth: 2,
      },
    ],
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      yAxes: [
        {
          ticks: {
            beginAtZero: true,
          },
        },
      ],
    },
  },
});

///

var ctx = document.getElementById("myChart2").getContext("2d");
var myChart = new Chart(ctx, {
  type: "doughnut",
  data: {
    datasets: [
      {
        data: [30, 70],
        backgroundColor: ["rgba(255, 99, 132, 0.6)", "rgba(54, 162, 235, 0.6)"],
      },
    ],
    labels: ["Kurir1", "Kurir2"],
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    cutoutPercentage: 50, // podesiti na 50 da bude polukružni grafikon
    legend: {
      display: false, // isključiti legendu
    },
    animation: {
      animateScale: true,
      animateRotate: true,
    },
  },
});
