<?php
include 'admin/components/components.php';
studentLogin();

if (
    !isset($_GET['feedback_id']) ||
    !isset($_GET['student_id']) ||
    !isset($_GET['teacher_id']) ||
    !isset($_GET['faculty_id']) ||
    !isset($_GET['subject_id'])

) {
    alert('error', 'Cannot get anything');
    // redirect("error_page.php");
    exit;
}

$feedback_id = intval($_GET['feedback_id']);
$student_id = intval($_GET['student_id']);
$teacher_id = intval($_GET['teacher_id']);
$faculty_id = intval($_GET['faculty_id']);
$subject_id = intval($_GET['subject_id']);
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
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
        background-color: radial-gradient(circle, rgba(255, 255, 255, 1) 0%, rgba(255, 0, 193, 1) 100%) !important;
    }

    .text-pink {
        color: #FF00C1 !important;
    }

    .border-top-pink {
        border-top-color: #FF00C1 !important;

    }

    .btn-pink {
        background-color: #FF00C1 !important;
        color: #FFFFFF;
    }

    .btn-pink:hover {
        background-color: #FF4DD6 !important;
        color: #FFFFFF;
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

    .bg-light-pink {
        background-color: #fde4f2;
    }

    .custom-col {
        width: 45.83%;
        max-width: 100%;
    }
    </style>
</head>

<body class="bg-light-pink">
    <div class="container-fluid">
        <div class="row">

            <div class="d-flex justify-content-center align-items-center min-vh-100">
                <div class="container mt-4">
                    <div class="row justify-content-center">
                        <div class="col-md-6 col-lg-5">
                            <div class="card shadow rounded-4 overflow-hidden">

                                <!-- Top Colored Border -->
                                <div class="w-100" style="height: 8px; background-color: #ff69b4;"></div>

                                <div class="card-body text-center">

                                    <!-- Logo -->
                                    <img src="logo.png" class="img-fluid mb-3"
                                        style="max-height: 470px; max-width: 220px;" alt="Saint Vincent Logo">

                                    <!-- School Name -->
                                    <h5 class="card-title text-pink">Saint Vincent De Ferrer College</h5>

                                    <!-- Response Message -->
                                    <h6 class="text-dark">User Already Responded</h6>
                                    <p class="text-secondary">Seems like you've already responded to this form</p>

                                    <!-- Footer with Button -->
                                    <div class="card-footer bg-transparent border-0 mt-2">
                                        <div class="d-flex justify-content-end">
                                            <?php
                                            echo "<a href='view_response.php?subject_id={$subject_id}&feedback_id={$feedback_id}&student_id={$student_id}&faculty_id={$faculty_id}&teacher_id={$teacher_id}'
                                                class='btn btn-pink px-4'>View Response</a>";

                                            ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

</body>

</html>