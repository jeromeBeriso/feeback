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
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        background: radial-gradient(circle, rgba(255, 255, 255, 1) 0%, rgba(255, 0, 193, 1) 100%);
    }

    @media (max-width: 767px) {
        .sidebar {
            height: auto;
        }
    }

    .nav-shadow {
        box-shadow: 0px 0px 28px 0px rgba(82, 63, 105, 0.13) !important;
    }

    .table thead th {
        background-color: #f8f9fa;
    }

    .table tbody tr:hover {
        background-color: #f1f1f1;
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
            <div class="col-md-9 col-lg-10 mt-4">
                <h3 class="text-muted">Feedback Sessions</h3>

                <div class="card border-1 shadow mb-4">
                    <div class="card-body">
                        <h5 class="text-primary">Manage Feedback Sessions</h5>
                        <form id="session_form">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" name="session_name" class="form-control"
                                        placeholder="Name">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">Feedback for</label>
                                    <select class="form-select" name="faculty_id">
                                        <option value="">All</option>
                                        <?php
                                        $query = selectAll('faculty');
                                        while ($Faculty = mysqli_fetch_assoc($query)) {
                                            echo "<option value='{$Faculty['faculty_id']}'>{$Faculty['abbrev']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label for="">From</label>
                                    <input type="datetime-local" name="start_time" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">Until</label>
                                    <input type="datetime-local" name="end_time" class="form-control">
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-success" type="submit">Save</button>
                            </div>
                        </form>

                    </div>
                </div>

                <div class="card border-1 shadow mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5 class="text-primary">Feedback(s) List</h5>
                        </div>

                        <div class="table-responsive-lg">
                            <table class="table table-hover border text-center" id="dataTable">
                                <thead>
                                    <tr class="card-header-bg text-success">
                                        <th>#</th>
                                        <th>Semester</th>
                                        <th>Start</th>
                                        <th>End</th>
                                        <th>Total Feedbacks</th>
                                        <th>created at</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="session-data">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
    <script>
    let session_form = document.getElementById("session_form");

    session_form.addEventListener("submit", function(e) {
        e.preventDefault();
        add_session();
    });

    function add_session() {
        let data = new FormData();
        data.append("add_session", "");
        data.append("session_name", session_form.elements["session_name"].value);
        data.append("faculty_id", session_form.elements["faculty_id"].value);
        data.append("start_time", session_form.elements["start_time"].value);
        data.append("end_time", session_form.elements["end_time"].value);

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/session.php", true);

        xhr.onload = function() {
            if (this.responseText == 1) {
                alert("Session created and feedback forms added successfully!");
                session_form.reset();
            } else {
                alert("Something went wrong while adding the session.");
            }
        };
        xhr.send(data);
    }

    function get_session() {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/session.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function() {
            document.getElementById("session-data").innerHTML = this.responseText;
        };
        xhr.send("get_session");
    }

    function status_update(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to end this session?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, end it!",
            cancelButtonText: "Cancel",
            customClass: {
                confirmButton: "bg-danger",
                cancelButton: "bg-secondary"
            }
        }).then((result) => {
            if (result.isConfirmed) {
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "ajax/session.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhr.onload = function() {
                    if (this.responseText == 1) {
                        Swal.fire({
                            title: "Successfully Ended!",
                            text: "Session is now closed!",
                            icon: "success",
                            customClass: {
                                confirmButton: "bg-primary"
                            }
                        });
                        get_session();
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Failed to initiate changes!",
                            text: "Oops...Something went wrong!",
                            footer: '<a href="#">Why do I have this issue?</a>',
                            customClass: {
                                confirmButton: "bg-primary"
                            }
                        });
                    }
                };
                xhr.send("status_update=" + id);
            }
        });
    }

    window.onload = function() {
        get_session();
    }
    </script>
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