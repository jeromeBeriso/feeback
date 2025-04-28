<?php
require('components/components.php');
require('components/dbconfig.php');
adminLogin()
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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

    .text-teal {
        color: teal !important;
    }

    .bg-teal {
        background-color: teal !important;
    }

    .body {
        background-color: radial-gradient(circle, rgba(255, 255, 255, 1) 0%, rgba(255, 0, 193, 1) 100%);
    }



    @media (max-width: 767px) {
        .sidebar {
            height: auto;
        }
    }

    .nav-shadow {
        box-shadow: 0px 0px 28px 0px rgba(82, 63, 105, 0.13) !important;
    }
    </style>
</head>

<body>

    <?php
    include 'components/header.php';
    ?>

    <div class="container-fluid">
        <div class="row">

            <?php
            include 'components/sidebar.php';
            ?>
            <div class="col-md-9 col-lg-10">
                <h2 class="text-muted mt-1">Dashboard</h2>
                <div class="container mt-3">
                    <!-- Total Feedback Count -->
                    <div class="row mt-3">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h5 class="text-muted">Total Feedback</h5>
                            <!-- Semester Filter Dropdown -->
                            <select class="form-select bg-light w-auto" onchange="fetchFeedbackData(this.value)">
                                <option selected value="">-- All semester --</option>
                                <?php
                                $query = selectAll('session');
                                while ($session = mysqli_fetch_assoc($query)) {
                                    $status = ($session['status'] == 1) ? 'Active' : 'Inactive';
                                    $class = ($session['status'] == 1) ? 'text-success' : '';
                                    echo '<option class="' . $class . '" value="' . $session['session_id'] . '">' . $session['name'] . ' - ' . $status . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <div class="card shadow">
                                <div class="card-header text-success">
                                    <h4>Total Positive</h4>
                                </div>
                                <div class="card-body text-success">
                                    <p id="total_positive"></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card shadow">
                                <div class="card-header text-danger">
                                    <h4>Total Negative</h4>
                                </div>
                                <div class="card-body text-danger">
                                    <p id="total_negative"></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card shadow">
                                <div class="card-header text-secondary">
                                    <h4>Total Neutral</h4>
                                </div>
                                <div class="card-body text-secondary">
                                    <p id="total_neutral"></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card shadow">
                                <div class="card-header text-teal">
                                    <h4>Overall Verdict</h4>
                                </div>
                                <div class="card-body text-teal">
                                    <p id="overall_verdict">%</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Student Summary -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5 class="text-muted">Student</h5>
                        </div>

                        <div class="col-md-3">
                            <div class="card shadow">
                                <div class="card-header text-success">
                                    <h4>Recent Feedbacks</h4>
                                </div>
                                <div class="card-body text-success">
                                    <p id="recent_feedback"></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card shadow">
                                <div class="card-header text-danger">
                                    <h4>Total Feedbacks</h4>
                                </div>
                                <div class="card-body text-danger">
                                    <p id="total_feedback"></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card shadow">
                                <div class="card-header text-secondary">
                                    <h4>Participating Students</h4>
                                </div>
                                <div class="card-body text-secondary">
                                    <p id="participating_students"></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card shadow">
                                <div class="card-header text-teal">
                                    <h4>Total Students</h4>
                                </div>
                                <div class="card-body text-teal">
                                    <p id="total_students"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Teacher Summary -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5 class="text-muted">Teacher</h5>
                        </div>

                        <div class="col-md-3">
                            <div class="card shadow">
                                <div class="card-header text-success">
                                    <h4>Newly Added</h4>
                                </div>
                                <div class="card-body text-success">
                                    <p id="new_teachers"></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card shadow">
                                <div class="card-header text-danger">
                                    <h4>Total Submissions</h4>
                                </div>
                                <div class="card-body text-danger">
                                    <p id="teacher_submissions"></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card shadow">
                                <div class="card-header text-secondary">
                                    <h4>Active Teachers</h4>
                                </div>
                                <div class="card-body text-secondary">
                                    <p id="active_teachers"></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card shadow">
                                <div class="card-header text-teal">
                                    <h4>Total Teachers</h4>
                                </div>
                                <div class="card-body text-teal">
                                    <p id="total_teachers"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
    <script>
    function fetchFeedbackData(sessionId) {
        const xhr = new XMLHttpRequest();
        // If no session is selected (sessionId is empty), pass 0 to fetch data for all semesters
        xhr.open("GET", "ajax/dashboard.php?session_id=" + (sessionId || 0), true);
        xhr.onload = function() {
            if (this.status === 200) {
                const data = JSON.parse(this.responseText);
                document.getElementById("total_positive").innerText = data.total_positive;
                document.getElementById("total_negative").innerText = data.total_negative;
                document.getElementById("total_neutral").innerText = data.total_neutral;
                document.getElementById("overall_verdict").innerText = data.overall_verdict;
                document.getElementById("recent_feedback").innerText = data.recent_feedback;
                document.getElementById("total_feedback").innerText = data.total_feedback;
                document.getElementById("participating_students").innerText = data.participating_students;
                document.getElementById("total_students").innerText = data.total_students;
                document.getElementById("new_teachers").innerText = data.new_teachers;
                document.getElementById("teacher_submissions").innerText = data.teacher_submissions;
                document.getElementById("active_teachers").innerText = data.active_teachers;
                document.getElementById("total_teachers").innerText = data.total_teachers;
            }
        };
        xhr.send();
    }

    window.onload = function() {
        fetchFeedbackData(); // Fetch data for all semesters when the page loads
    };
    </script>


</body>

</html>