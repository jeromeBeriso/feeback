<?php
include '../components/components.php';
include '../components/dbconfig.php';

if (isset($_POST['get_student'])) {

    $query = "SELECT s.*, s.*, f.abbrev AS abbrev, f.faculty_name AS faculty
FROM student s
INNER JOIN faculty f ON s.faculty_id = f.faculty_id;

";

    $res = mysqli_query($conn, $query);

    // Check if the query was successful
    if ($res === false) {
        // Output the error message and terminate the script
        die("Error in SQL query: " . mysqli_error($conn));
    }

    $i = 1;
    $table_data = "";

    while ($data = mysqli_fetch_assoc($res)) {

        $table_data .= "
            <tr>
                <td>$i</td>
                <td>{$data['name']}</td>
                <td>{$data['abbrev']}</td>
                <td>{$data['student_id']}</td>
                <td>
                    <button class='btn btn-danger'><i class='bi bi-trash'></i></button>
                    <button class='btn btn-primary' data-bs-toggle='modal'
                        data-bs-target='#edit'><i class='bi bi-pencil-square'></i>
                    </button>
                </td>
            </tr>
        ";
        $i++;
    }
    echo $table_data;
}

if (isset($_POST['get_student_records'])) {

    $query = "SELECT s.*, f.*, f.abbrev AS abbrev, f.faculty_name AS faculty
FROM student s
INNER JOIN faculty f ON s.faculty_id = f.faculty_id;

";

    $res = mysqli_query($conn, $query);

    // Check if the query was successful
    if ($res === false) {
        // Output the error message and terminate the script
        die("Error in SQL query: " . mysqli_error($conn));
    }

    $i = 1;
    $table_data = "";

    while ($data = mysqli_fetch_assoc($res)) {

        $table_data .= "
            <tr>
                <td>$i</td>
                <td>{$data['name']}</td>
                <td>{$data['student_id']}</td>
                <td>{$data['faculty']}</td>
                <td>{$data['abbrev']}</td>
            </tr>
        ";
        $i++;
    }
    echo $table_data;
}

if (isset($_POST['add_student'])) {
    $form = filter($_POST);
    $name = $form['name'];
    $ID = $form['student_id'];
    $Faculty = $form['faculty'];
    $last_digit = substr($ID, -4);
    $plain_password = "Feed#" . $last_digit;

    // Hash the password
    $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

    // Insert into the database
    $sql = "INSERT INTO `student`(`name`,`student_id`, `faculty_id`,`password`) VALUES (?,?,?,?)";
    $values = [$name, $ID, $Faculty, $hashed_password];
    $dtypes = "ssis";

    try {
        if (update($sql, $values, $dtypes)) {
            echo 1; // Success
        } else {
            echo 0;
        }
    } catch (Exception $e) {
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            echo "duplicate";
        } else {
            echo "error";
        }
    }
}