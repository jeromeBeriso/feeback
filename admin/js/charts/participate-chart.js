const participationCtx = document
  .getElementById("stat-box-chart")
  .getContext("2d");

fetch("ajax/statistics_S.php")
  .then((res) => res.json())
  .then((data) => {
    const totalFeedbacks = data.totalFeedbacks;
    const participatedFeedbacks = data.participatedFeedbacks;

    const participationRate =
      totalFeedbacks > 0 ? (participatedFeedbacks / totalFeedbacks) * 100 : 0;
    const notParticipatedRate = 100 - participationRate;

    new Chart(participationCtx, {
      type: "doughnut",
      data: {
        labels: ["Participated", "Not Participated"],
        datasets: [
          {
            data: [participationRate, notParticipatedRate],
            backgroundColor: ["#4CAF50", "#f44336"],
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: "top",
          },
          tooltip: {
            callbacks: {
              label: function (tooltipItem) {
                const value = tooltipItem.raw;
                const percentage = value.toFixed(1);
                return `${tooltipItem.label}: ${percentage}%`;
              },
            },
          },
        },
      },
    });
  })
  .catch((error) => {
    console.error("Error fetching participation data:", error);
    alert("Failed to fetch participation data. Please try again later.");
  });
