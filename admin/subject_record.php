<!DOCTYPE html>
<html lang="en">

<head>
    <title>Subject Record</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <!-- JavaScript -->
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
                <h3 class="text-muted">Subject</h3>
                <div class="card border-1 shadow mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5 class="text-primary">Subject List</h5>
                        </div>

                        <div class="table-responsive-lg">
                            <table class="table table-hover border text-center" id="dataTable">
                                <thead>
                                    <tr class="card-header-bg text-success">
                                        <th>#</th>
                                        <th>Subject - Code</th>
                                    </tr>
                                </thead>
                                <tbody id="subject_record-data">
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
                get_all_subject: true
            },
            success: function(data) {
                $('#subject_record-data').html(data);
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
    </script>
</body>

</html>