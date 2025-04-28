<?php

function adminLogin()
{
    session_name('admin_session');
    session_start();

    if (!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)) {
        echo "<script>
        window.location.href='index.php';
        </script>";
    }
    session_regenerate_id(true);
}

function teacherLogin()
{
    session_name('teacher_session');
    session_start();

    if (!(isset($_SESSION['teacherLogin']) && $_SESSION['teacherLogin'] == true)) {
        echo "<script>
        window.location.href='index.php';
        </script>";
    }
    session_regenerate_id(true);
}
function studentLogin()
{
    session_name('student_session');
    session_start();

    if (!(isset($_SESSION['studentLogin']) && $_SESSION['studentLogin'] == true)) {
        echo "<script>
        window.location.href='index.php';
        </script>";
    }
    session_regenerate_id(true);
}

function redirect($url)
{
    echo "<script>
        window.location.href='$url';
        </script>";
}
function alert($types, $msg)
{
    $alerts = ($types == "success") ? "alert-success" : "alert-danger";
    echo <<<alert
             
             <div class="alert $alerts fade show sticky" role="alert">
             <strong class="me-3" >$msg</strong> 
             </div>
             
             alert;
}



?>