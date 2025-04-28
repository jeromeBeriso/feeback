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
    tfoot th {
        position: sticky;
        bottom: 0;
        background-color: #f8f9fa;
        z-index: 1;
    }

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

    /* width */
    ::-webkit-scrollbar {
        width: 10px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: rgb(36, 36, 36);
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
        background: #555;
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
                <h3 class="text-muted">Teacher</h3>
                <div class="card border-1 shadow mb-4">
                    <div class="card-body">
                        <h5 class="text-primary">Add Teacher</h5>
                        <form id="teacher_form">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="">Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Name" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="">ID Number</label>
                                    <input type="text" name="ID" class="form-control" placeholder="ID Number" required>
                                </div>
                                <input type="hidden" id="teacher_id">
                                <div class="col-md-4 mb-3">
                                    <label for="">Faculty</label>
                                    <select class="form-select" name="faculty" required>
                                        <option value="" disabled selected>Select Faculty</option>
                                        <?php
                                        $query = selectAll('faculty');
                                        while ($Faculty = mysqli_fetch_assoc($query)) {
                                            echo "<option value='{$Faculty['faculty_id']}'>{$Faculty['abbrev']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Subject</label>
                                <div class="row">
                                    <?php
                                    $res = selectAll('subject');
                                    while ($opt = mysqli_fetch_assoc($res)) {
                                        echo "
                        <div class='col-md-3'>
                            <label>
                            <input type='checkbox' name='subj' value='{$opt['subject_id']}' class='form-check-input shadow-none text-sm'>
                            {$opt['code']} - {$opt['name']}
                            </label>
                        </div>";
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button class="btn btn-success">Save</button>
                            </div>
                        </form>

                    </div>

                </div>

                <div class="card border-1 shadow mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5 class="text-primary">Teacher List</h5>
                        </div>

                        <div class="table-responsive-lg" style="max-height: 350px; overflow-y: auto;">
                            <table class="table table-hover border text-center" id="dataTable">
                                <thead>
                                    <tr class="card-header-bg text-success">
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Faculty</th>
                                        <th>ID Number</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody id="teacher-data">
                                    <!-- Dynamic rows go here -->
                                </tbody>

                                <tfoot>
                                    <tr class="card-header-bg sticky-footer">
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Faculty</th>
                                        <th>ID Number</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                            </table>

                        </div>

                    </div>
                </div>
            </div>
        </div>





        <!-- Modal -->
        <div class="modal fade" id="edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Update Teacher</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card-body">
                            <form>
                                <div class="d-flex justify-content-between gap-3">
                                    <div class="col-md-5">
                                        <label for="name">Name</label>
                                        <input type="name" class="form-control" id="name" placeholder="name">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Faculty</label>
                                        <select class="form-select" name="faculty" required>
                                            <option value="" disabled selected>Select Faculty</option>
                                            <?php
                                            $query = selectAll('faculty');
                                            while ($Faculty = mysqli_fetch_assoc($query)) {
                                                echo "<option value='{$Faculty['faculty_id']}'>{$Faculty['abbrev']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Subject</label>
                                    <div class="row">
                                        <?php
                                        $res = selectAll('subject');
                                        while ($opt = mysqli_fetch_assoc($res)) {
                                            echo "
                        <div class='col-md-3'>
                            <label>
                            <input type='checkbox' name='subj' value='{$opt['subject_id']}' class='form-check-input shadow-none text-sm'>
                            {$opt['code']} - {$opt['name']}
                            </label>
                        </div>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn  btn-outline-danger"
                                data-bs-dismiss="modal">cancel</button>
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
    <script src="js/teacher.js"></script>
</body>

</html>