fetch("ajax/statistics.php")
  .then(res => res.json())
  .then(data => {
    const labels = data.map(item => item.label);
    const counts = data.map(item => item.count);

    const barCtx = document.getElementById("bar-chart").getContext("2d");

    new Chart(barCtx, {
      type: "bar",
      data: {
        labels: labels,
        datasets: [{
          label: "Total Feedback per Semester",
          data: counts,
          backgroundColor: "rgba(75, 192, 192, 0.6)",
          borderColor: "rgba(75, 192, 192, 1)",
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true
          }
        },
        plugins: {
          title: {
            display: true,
            text: "Feedback Summary by Session"
          }
        }
      }
    });
  });
