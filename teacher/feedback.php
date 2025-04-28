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
    <title>Feedback</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>


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


    <?php include 'components/header.php'; ?>
    <div class="container-fluid">
        <div class="row">
            <?php include 'components/sidebar.php'; ?>

            <div class="col-md-9 col-lg-10 mt-4">
                <h3 class="text-muted">Feedback</h3>
                <div class="card border-1 shadow mb-4">
                    <div class="card-body">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5 class="text-primary">Subject(s) List</h5>
                            </div>

                            <div class="table-responsive-lg">
                                <table class="table table-hover border text-center" id="dataTable">
                                    <thead>
                                        <tr class="card-header-bg text-success">
                                            <th>#</th>
                                            <th>Subject</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="subject-data">
                                    </tbody>
                                </table>
                            </div>
                        </div>


                    </div>

                </div>
            </div>

        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="record" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive-lg">
                        <table class="table table-hover border text-center" id="dataTable">
                            <thead>
                                <tr class="card-header-bg text-success">
                                    <th>#</th>
                                    <th>Feedback message</th>
                                    <th>Feedback Response</th>
                                    <th>Verdict</th>
                                </tr>
                            </thead>
                            <tbody id="record-data">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
    $(document).ready(function() {
        // Fetch student data
        $.ajax({
            url: 'ajax/subject.php',
            type: 'POST',
            data: {
                get_subject: true
            },
            success: function(data) {
                $('#subject-data').html(data);
            },
            error: function(xhr, status, error) {
                console.error(xhr);
            }
        });
        $('#dataTable').DataTable({
            "paging": true,
            "searching": true,
            "info": true,
            "lengthChange": false,
            "pageLength": 5,
            "language": {
                "search": "",
                "searchPlaceholder": "Search..."
            }
        });
    });

    function getFeedbackRecords(subject_id) {
        $.ajax({
            url: 'ajax/subject.php',
            type: 'POST',
            dataType: 'json',
            data: {
                getFeedbackRecords: true,
                subject_id: subject_id
            },
            success: function(response) {
                document.getElementById('staticBackdropLabel').innerHTML = response.subject_name +
                    " - Feedback Record";
                document.getElementById('record-data').innerHTML = response.table_data;
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
    </script>
    <script src="js/components/feedback.js"></script>

</body>

</html>