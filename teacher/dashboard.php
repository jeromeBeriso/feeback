<?php
include '../admin/components/dbconfig.php';
include '../admin/components/components.php';
teacherLogin();

$teacher_id = $_SESSION['teacherID'];
$faculty_id = $_SESSION['teacherFaculty'];

$faculty = "SELECT * FROM `faculty` WHERE `faculty_id` = ? ";
$faculty_details = mysqli_fetch_assoc(select($faculty, [$faculty_id], 'i'));
$qry_user = "SELECT * FROM `teacher` WHERE `id` = ?";
$user_details = mysqli_fetch_assoc(select($qry_user, [$teacher_id], 'i'));
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
        /* Bright Magenta */
        height: 100vh;
        padding-top: 20px;
    }

    .sidebar .nav-link {
        color: #FFFFFF;
        /* White for contrast */
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

    .h-font {
        font-family: "Poppins", sans-serif;
        font-weight: 700;
        font-style: normal;
    }

    .poppins-regular {
        font-family: "Poppins", sans-serif;
        font-weight: 400;
        font-style: normal;
    }


    .poppins-bold {
        font-family: "Poppins", sans-serif;
        font-weight: 700;
        font-style: normal;
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

            $teacherId = $_SESSION['teacherID'];

            $stmt = $conn->prepare("
    SELECT 
        COUNT(CASE WHEN f.sentiment = 'positive' THEN 1 END) AS total_positive,
        COUNT(CASE WHEN f.sentiment = 'negative' THEN 1 END) AS total_negative,
        COUNT(CASE WHEN f.sentiment = 'neutral' THEN 1 END) AS total_neutral,
        COUNT(CASE WHEN f.status = '1' THEN 1 END) AS new_feedback,
        COUNT(*) AS total_feedbacks
    FROM feedback f
    INNER JOIN `session` s ON f.session_id = s.session_id
    WHERE f.teacher_id = ?
");

            if (!$stmt) {
                die("Query prepare failed: " . $conn->error);
            }

            $stmt->bind_param("i", $teacherId);
            $stmt->execute();
            $feedback_fetch = $stmt->get_result()->fetch_assoc();

            $positive = $feedback_fetch['total_positive'];
            $total = $feedback_fetch['total_feedbacks'];
            $verdict_percentage = $total > 0 ? round(($positive / $total) * 100) : 0;
            ?>


            <div class="col-md-9 col-lg-10">
                <div class="container mt-3">
                    <div class="row">
                        <div class="col-12">
                            <div class="card p-3 shadow">
                                <h4>Dashboard</h4>
                                <?php
                                $message = "";
                                $color = "";

                                if ($positive >= $feedback_fetch['total_negative'] && $positive >= $feedback_fetch['total_neutral']) {
                                    $message = "Great job! Most feedback is positive.";
                                    $color = "success";
                                } elseif ($feedback_fetch['total_negative'] >= $positive && $feedback_fetch['total_negative'] >= $feedback_fetch['total_neutral']) {
                                    $message = "Heads up! Negative feedback is the highest.";
                                    $color = "danger";
                                } elseif ($feedback_fetch['total_neutral'] >= $positive && $feedback_fetch['total_neutral'] >= $feedback_fetch['total_negative']) {
                                    $color = "secondary";
                                    $message = "Feedback is mostly neutral. You’re doing fine.";
                                } else {
                                    $color = "dark";
                                    $message = "no data to show.";
                                }
                                ?>
                                <p class="text-wrap badge fs-6 text-start text-<?php echo $color ?> bg-white">
                                    <?= $message ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Feedback Summary Cards -->
                    <div class="row mt-3">
                        <div class="d-flex align-items-center justify-content-between mt-3">
                            <h5 class="text-muted">Total Sentiment Feedbacks</h5>
                            <select class="form-select bg-light w-auto" onchange="booking_analytics(this.value)">
                                <option selected>-- All Semester --</option>
                                <?php
                                $query = select(
                                    'SELECT DISTINCT s.session_id, s.name, s.status
                       FROM session s
                       INNER JOIN feedback f ON f.session_id = s.session_id
                       WHERE f.teacher_id = ?',
                                    [$teacher_id],
                                    'i'
                                );


                                while ($session = mysqli_fetch_assoc($query)) {
                                    $status = ($session['status'] == 1) ? 'Active' : 'Inactive';
                                    $class = ($session['status'] == 1) ? 'text-success' : '';

                                    echo '<option class="' . $class . '" value="' . $session['session_id'] . '">' . $session['name'] . ' - ' . $status . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3 mt-3">
                            <div class="card p-3 shadow">
                                <h4 class="card-header text-success">Positive Feedbacks</h4>
                                <p class="card-body text-success"><?= $feedback_fetch['total_positive'] ?></p>
                            </div>
                        </div>
                        <div class="col-md-3 mt-3">
                            <div class="card p-3 shadow">
                                <h4 class="card-header text-danger">Negative Feedbacks</h4>
                                <p class="card-body text-danger"><?= $feedback_fetch['total_negative'] ?></p>
                            </div>
                        </div>
                        <div class="col-md-3 mt-3">
                            <div class="card p-3 shadow">
                                <h4 class="card-header text-secondary">Neutral Feedbacks</h4>
                                <p class="card-body text-secondary"><?= $feedback_fetch['total_neutral'] ?></p>
                            </div>
                        </div>
                        <div class="col-md-3 mt-3">
                            <div class="card p-3 shadow">
                                <h4 class="card-header text-info">Verdict</h4>
                                <p class="card-body text-info"><?= $verdict_percentage ?>%</p>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-3">
                        <h5 class="text-muted">Total Feedbacks</h5>
                    </div>
                    <!-- Total Feedback Breakdown -->
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <div class="card p-3 shadow">
                                <h4 class="card-header text-success">New Feedbacks</h4>
                                <p class="card-body text-success"><?= $feedback_fetch['new_feedback'] ?></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card p-3 shadow">
                                <h4 class="card-header text-danger">Total Feedbacks</h4>
                                <p class="card-body text-danger"><?= $feedback_fetch['total_feedbacks'] ?></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card p-3 shadow">
                                <h4 class="card-header text-secondary">Students </h4>
                                <p class="card-body text-secondary"><?= $feedback_fetch['total_feedbacks'] ?></p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="js/components/dashboard.js"></script>
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
</body>

</html>