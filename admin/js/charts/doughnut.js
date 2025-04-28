const doughnutCtx = document.getElementById("doughnut-chart").getContext("2d");

fetch("ajax/statistics_D.php")
  .then((res) => res.json())
  .then((data) => {
    const total = data.positive + data.neutral + data.negative;

    new Chart(doughnutCtx, {
      type: "doughnut",
      data: {
        labels: ["Positive", "Neutral", "Negative"],
        datasets: [
          {
            data: [data.positive, data.neutral, data.negative],
            backgroundColor: ["#28a745", "#6c757d", "#dc3545"]
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: "top"
          },
          tooltip: {
            callbacks: {
              label: function (tooltipItem) {
                const value = tooltipItem.raw;
                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                return `${tooltipItem.label}: ${percentage}%`;
              }
            }
          }
        }
      }
    });
  });
