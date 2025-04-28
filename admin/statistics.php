<?php
require('components/components.php');
require('components/dbconfig.php');
adminLogin()
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Statistics</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
    .sidebar {
        background-color: #FF00C1 !important;
        height: 100vh;
        padding-top: 20px;
    }

    .sidebar .nav-link {
        color: #FFFFFF;
    }

    .sidebar .nav-link.active,
    .sidebar .nav-link:focus {
        background-color: #AD0083 !important;
    }

    .nav-shadow {
        box-shadow: 0px 0px 28px 0px rgba(82, 63, 105, 0.13) !important;
    }

    .h-font {
        font-family: "Poppins", sans-serif;
        font-weight: 700;
    }

    .chart-container {
        height: 300px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    canvas {
        width: 100% !important;
        height: 100% !important;
    }
    </style>
</head>

<body>

    <?php include 'components/header.php'; ?>


    <div class="container-fluid">
        <div class="row">

            <?php include 'components/sidebar.php'; ?>

            <!-- Content -->
            <div class="col-md-9 col-lg-10">
                <h4 class="mt-3">Statistics</h4>

                <div class="container mt-3">

                    <!-- Charts -->
                    <div class="row">
                        <div class="col-lg-8 col-md-12 mb-3">
                            <div class="card shadow">
                                <div class="card-header">
                                    <h6 class="text-dark">Satisfaction Rate</h6>
                                </div>
                                <div class="card-body chart-container">
                                    <canvas id="line-chart"></canvas>

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-12">
                            <div class="card shadow">
                                <div class="card-header">
                                    <h6 class="text-dark">Participation Rate</h6>
                                </div>
                                <div class="card-body chart-container">
                                    <canvas id="stat-box-chart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Charts -->
                    <div class="row">
                        <div class="col-lg-8 col-md-12 mb-3">
                            <div class="card shadow">
                                <div class="card-header">
                                    <h6 class="text-dark">Semester Distribution</h6>
                                </div>
                                <div class="card-body chart-container">
                                    <canvas id="bar-chart"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-12">
                            <div class="card shadow">
                                <div class="card-header">
                                    <h6 class="text-dark">Sentiment Distribution</h6>
                                </div>
                                <div class="card-body chart-container">
                                    <canvas id="doughnut-chart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- End of Content -->
        </div>
    </div>
    <script>
    function setActive() {
        let navbar = document.getElementById('sidebar-menu');
        let a_tags = navbar.getElementsByTagName('a');

        for (i = 0; i < a_tags.length; i++) {
            let file = a_tags[i].href.split('/').pop();
            let file_name = file.split('.')[0];

            if (document.location.href.indexOf(file_name) >= 0) {
                a_tags[i].classList.add('active');
            }
        }
    }
    setActive();
    </script>
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/charts/bar-char.js"></script>
    <script src="js/charts/doughnut.js"></script>
    <script src="js/charts/gauge-chart.js"></script>
    <script src="js/charts/participate-chart.js"></script>

</body>

</html>