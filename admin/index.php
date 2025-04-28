<?php
require('components/components.php');
require('components/dbconfig.php');

session_name('admin_session');
session_start();
session_regenerate_id(true);

if ((isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)) {
    redirect('dashboard.php');
}

if (isset($_POST['login'])) {
    $frm_data = filter($_POST);

    // Step 1: Check if admin exists
    $qry = "SELECT * FROM `admin` WHERE `ID_number` = ?";
    $res = select($qry, [$frm_data['ID']], "s");

    if ($res->num_rows == 0) {
        alert('error', 'Invalid ID number');
    } else {
        // Step 2: Verify password
        $qry_password = "SELECT * FROM `admin` WHERE `ID_number` = ? AND `password` = ?";
        $res_password = select($qry_password, [$frm_data['ID'], $frm_data['password']], "ss");

        if ($res_password->num_rows == 1) {
            $row = mysqli_fetch_assoc($res_password);
            $_SESSION['adminLogin'] = true;
            $_SESSION['id'] = $row['id'];
            redirect('dashboard.php');
        } else {
            alert('error', 'Incorrect Password');
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
    </style>
</head>

<body>
    <div class="login-card">
        <h3 class="text-center mb-4">Login</h3>
        <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-floating mb-3">
                <input type="text" name="ID" class="form-control" id="floatingPassword" placeholder="ID number">
                <label for="floatingPassword">ID Number</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" name="password" id="floatingPassword"
                    placeholder="Password">
                <label for="floatingPassword">Password</label>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
        </form>
        <div>
            <?php $error ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>