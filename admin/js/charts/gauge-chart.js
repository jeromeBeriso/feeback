document.addEventListener("DOMContentLoaded", () => {
  fetch("ajax/statistics_G.php")
    .then((res) => res.json())
    .then((data) => {
      const labels = data.map((item) => item.label);
      const satisfactionRates = data.map((item) => item.satisfactionRate);

      const barCtx = document.getElementById("line-chart").getContext("2d");

      if (window.myChart instanceof Chart) {
        window.myChart.destroy();
      }

      window.myChart = new Chart(barCtx, {
        type: "bar",
        data: {
          labels: labels,
          datasets: [
            {
              label: "Satisfaction Rate per Semester (%)",
              data: satisfactionRates,
              backgroundColor: "rgba(75, 192, 192, 0.6)",
              borderColor: "rgba(75, 192, 192, 1)",
              borderWidth: 1,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true,
              max: 100,
              ticks: {
                stepSize: 10,
              },
            },
          },
          plugins: {
            title: {
              display: true,
              text: "Satisfaction Rate per Semester",
            },
          },
        },
      });
    })
    .catch((error) => console.error("Error fetching data:", error));
});
