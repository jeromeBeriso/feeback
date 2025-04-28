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



if (
    !isset($_GET['feedback_id']) ||
    !isset($_GET['student_id']) ||
    !isset($_GET['teacher_id']) ||
    !isset($_GET['faculty_id']) ||
    !isset($_GET['subject_id'])

) {
    alert('error', 'Cannot get anything');
    redirect("error_page.php");
    exit;
}

$feedback_id = intval($_GET['feedback_id']);
$student_id = intval($_GET['student_id']);
$teacher_id = intval($_GET['teacher_id']);
$faculty_id = intval($_GET['faculty_id']);
$subject_id = intval($_GET['subject_id']);

// Validate feedback before rendering the form
$responseCheck = select("SELECT status FROM feedback WHERE feedback_id = ? AND student_id = ?", [$feedback_id, $student_id], "ii");
$responseData = mysqli_fetch_assoc($responseCheck);

if (!$responseData || $responseData['status'] == 1) {
    redirect("already_responded.php?subject_id={$subject_id}&feedback_id={$feedback_id}&student_id={$student_id}&faculty_id={$faculty_id}&teacher_id={$teacher_id}");
    exit;
}

$tquery = select("SELECT * FROM teacher WHERE id = ?", [$teacher_id], "i");
$tres = mysqli_fetch_assoc($tquery);

$squery = select("SELECT * FROM subject WHERE subject_id = ?", [$subject_id], "i");
$sres = mysqli_fetch_assoc($squery);

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
        border-top-width: 15px !important;

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

            <div class="d-flex justify-content-center">
                <div class="custom-col">
                    <div class="container mt-3">
                        <div class="row">
                            <div class="card border-top-pink border-top">
                                <div class="card-body ">
                                    <div class="d-flex justify-content-center">
                                        <img src="logo.png" class="card-img-top img-fluid"
                                            style="max-height: 470px; max-width: 220px;" alt="img">
                                    </div>
                                    <h5 class="card-title text-pink text-center ">Saint Vincent De Ferrer College</h5>
                                    <h6 class="card-title text-center">Feedback Evaluation Form</h6>
                                    <div class="d-flex justify-content-center">
                                        <span class="badge text-muted bg-white text-wrap">Personal Details will
                                            automatically
                                            hide to all other users.</span>
                                    </div>
                                    <footer>
                                        <div class="d-flex card-footer mt-3 justify-content-between">
                                            <h6 class="text-wrap"><?php echo $user_details['name'] ?></h6>
                                            <h6 class="text-wrap"><?php echo $faculty_details['abbrev'] ?></h6>
                                        </div>

                                    </footer>
                                </div>

                            </div>

                            <div class="card mt-3">
                                <div class="card-header d-flex justify-content-between mt-1">
                                    <h6 class="text-wrap"><?php echo $tres['name'] ?></h6>
                                    <h6 class="text-wrap"><?php echo $sres['code'] . '-' . $sres['name'] ?></h6>
                                </div>
                                <div class="card-body">
                                    <form id="feedback_form">
                                        <div class="form-floating">
                                            <textarea class="form-control" name="feedback_msg"
                                                placeholder="Leave a comment here" require id="floatingTextarea2"
                                                style="height: 100px"></textarea>
                                            <label for="floatingTextarea2">Feedback</label>
                                        </div>
                                        <input type="hidden" name="feedback_id" value="<?php echo $feedback_id ?>">
                                        <div class="d-flex justify-content-end">
                                            <button class="btn btn-pink mt-3">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header">
                                    <i class="bi bi-chat-quote"></i> Quote
                                </div>
                                <div class="card-body">
                                    <blockquote class="blockquote mb-0">
                                        <p class="fs-6">"Integrity is doing the right thing, even when no one is
                                            watching. Honesty is speaking the truth. Fairness is treating others the
                                            way
                                            you’d want to be treated. Together, they are the foundation of trust."</p>
                                        <footer class="blockquote-footer fs-6">Inspired by C.S. Lewis</footer>
                                    </blockquote>
                                </div>
                            </div>


                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
    <script>
    let feedback_form = document.getElementById('feedback_form');

    feedback_form.addEventListener("submit", function(e) {
        e.preventDefault();
        submit_feedback();
    });

    function submit_feedback() {
        let data = new FormData();
        data.append("submit_feedback", "");
        data.append("feedback_msg", feedback_form.elements["feedback_msg"].value);
        data.append("feedback_id", feedback_form.elements["feedback_id"].value);

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/sentiment.php", true);
        xhr.onload = function() {
            if (this.responseText == 1) {
                feedback_form.reset();
                window.location.href = "submitted_success.php";
            } else {
                alert("Error: " + this.responseText);
            }
        };


        xhr.send(data);
    }
    </script>


</body>

</html>