<?php
include 'admin/components/dbconfig.php';
include 'admin/components/components.php';
session_name('student_session');

if ((isset($_SESSION['studentLogin']) && $_SESSION['studentLogin'] == true)) {
    redirect('dashboard.php');
}

if (isset($_POST['login'])) {
    $data = filter($_POST);


    // Step 1: Check if student exists
    $student_exist = select(
        "SELECT * FROM `student` WHERE `student_id` = ? LIMIT 1",
        [$data['ID']],
        "s"
    );

    if ($student_exist === false || mysqli_num_rows($student_exist) == 0) {
        alert('error', 'INVALID'); // ID not found
    } else {
        $student = mysqli_fetch_assoc($student_exist);

        if (!$student) {
            alert('error', 'INVALID'); // ID not found
        } else {
            if (!password_verify($data['password'], $student['password'])) {
                alert('error', 'INCORRECT PASSWORD');
            } else {
                session_start();
                session_regenerate_id(true);
                $_SESSION['studentLogin'] = true;
                $_SESSION['studentID'] = $student['id'];
                $_SESSION['studentName'] = $student['name'];
                $_SESSION['studentFaculty'] = $student['faculty_id'];
                redirect('feedback.php');
                echo 1; // success
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background: radial-gradient(circle, rgba(255, 255, 255, 1) 0%, rgba(255, 0, 193, 1) 100%);
    }

    .costum-btn-light {
        background-color: #ddd;
        color: #333;
    }

    .costum-btn-light:hover {
        background-color: #bbb;
    }

    .login-card {
        width: 100%;
        max-width: 400px;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: #fff;
    }

    .login-card .form-control:focus {
        box-shadow: none;
        border-color: #AD0083;
    }

    .btn-primary {
        background-color: #FF00C1;
        border-color: #FF00C1;
    }

    .btn-primary:hover {
        background-color: #AD0083;
        border-color: #AD0083;
    }

    .text-primary {
        color: #FF00C1 !important;
    }

    .halfbright {
        filter: brightness(50%);
    }
    </style>
</head>

<body>
    <div class="login-card shadow">
        <div class="d-flex justify-content-center mt-3">
            <img src="logo.png" width="90%" alt="background">
        </div>
        <div class="d-flex justify-content-center">
            <span class="badge mt-0 mb-4 text-primary text-center">Saint Vincent De Ferrer College of Camarin</span>
        </div>
        <h3 class="text-center mb-4 text-dark">Login</h3>
        <form method="POST">
            <div class="form-floating mb-3">
                <input type="text" name="ID" class="form-control" id="floatingStudentID" placeholder="Student ID">
                <label for="floatingStudentID">student-ID</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" name="password" class="form-control" id="floatingPassword"
                    placeholder="Password">
                <label for="floatingPassword">Password</label>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100">Login</button>

        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>