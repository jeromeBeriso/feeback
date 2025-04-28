<?php
include 'admin/components/dbconfig.php';
include 'admin/components/components.php';
studentLogin();

$student_id = $_SESSION['studentID'];
$faculty_id = $_SESSION['studentFaculty'];



$faculty = "SELECT * FROM `faculty` WHERE `faculty_id` = ? ";
$faculty_details = mysqli_fetch_assoc(select($faculty, [$faculty_id], 'i'));
$qry_user = "SELECT * FROM `student` WHERE `id` = ?";
$user_details = mysqli_fetch_assoc(select($qry_user, [$student_id], 'i'));
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

    <?php include 'components/headbar.php'; ?>

    <div class="container-fluid">
        <div class="row">

            <?php include 'components/sidebar.php'; ?>

            <div class="col-md-9 col-lg-10">
                <div class="container mt-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <h5 class="card-title card-header">Feedbacks</h5>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <select class="form-select" id="semesterFilter">
                                            <option selected disabled>-- Select Session --</option>
                                            <?php
                                            $query = select(
                                                'SELECT DISTINCT s.session_id, s.name, s.status
                       FROM session s
                       INNER JOIN feedback f ON f.session_id = s.session_id
                       WHERE f.student_id = ?',
                                                [$student_id],
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



                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Subject</th>
                                                <th scope="col">Professor</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="feedbacks-data">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <script src="js/feedback.js"></script>
</body>

</html>