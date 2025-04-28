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
    <style>
        .sidebar {
            background-color: #FF00C1 !important;
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
                <h3 class="text-muted">Student</h3>
                <div class="card border-1 shadow mb-4">
                    <div class="card-body">
                        <h5 class="text-primary">Add Student</h5>
                        <form id="student_form">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="">Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Name" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="ID">Student ID</label>
                                    <input type="text" name="student_id" class="form-control" placeholder="Student ID"
                                        required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="">Faculty</label>
                                    <select class="form-select" name="faculty" aria-label="Default select example"
                                        required>
                                        <option selected>Select Faculty</option>
                                        <?php
                                        $query = selectAll('faculty');
                                        while ($Faculty = mysqli_fetch_assoc($query, )) {
                                            echo " <option value= $Faculty[faculty_id]> $Faculty[abbrev]</option>";
                                        }

                                        ?>


                                    </select>
                                </div>
                            </div>

                            <div class="d-flex align-content-center">
                                <button class="btn btn-success">Save</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card border-1 shadow mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5 class="text-primary">Student(s) List</h5>
                        </div>

                        <div class="table-responsive-lg">
                            <table class="table table-hover border text-center" id="dataTable">
                                <thead>
                                    <tr class="card-header-bg text-success">
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Faculty</th>
                                        <th>Student Number</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="student-data">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





    <!-- Modal -->
    <div class="modal fade" id="edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Update Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <form>
                            <div class="col-md-12">
                                <div class="form-floating mb-3">
                                    <input type="name" class="form-control" id="floatingInput"
                                        placeholder="name@example.com">
                                    <label for="floatingInput">Name</label>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="floatingPassword"
                                        placeholder="Id Number">
                                    <label for="floatingPassword">Student ID Number</label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="">Faculty</label>
                                <select class="form-select" name="faculty" aria-label="Default select example" required>
                                    <option selected>Select Faculty</option>
                                    <?php
                                    $query = selectAll('faculty');
                                    while ($Faculty = mysqli_fetch_assoc($query, )) {
                                        echo " <option value= $Faculty[faculty_id]> $Faculty[abbrev]</option>";
                                    }

                                    ?>

                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn  btn-outline-danger" data-bs-dismiss="modal">cancel</button>
                        <button type="button" class="btn btn-outline-success">save</button>
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
    <script src="js/student.js"></script>
</body>

</html>