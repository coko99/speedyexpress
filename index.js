//ADMIN STATISTIKA
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



/*DATUM I VREME */
function prikaziDatumVreme() {
  var datum = new Date();
  var dan = datum.getDate();
  var mesec = datum.getMonth() + 1;
  var godina = datum.getFullYear();
  var sati = datum.getHours();
  var minuti = datum.getMinutes();
  var sekunde = datum.getSeconds();
  var formatiranDatum = dan + 1 + "." + mesec + "." + godina + ".";
  document.getElementById("prikazDatumaVremena").innerHTML = formatiranDatum;
  setTimeout(prikaziDatumVreme, 1000); // ažuriraj vreme svake sekunde
}

//korisnik STATISTIKA
