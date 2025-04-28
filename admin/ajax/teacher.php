<?php
include '../components/components.php';
include '../components/dbconfig.php';

if (isset($_POST['get_teacher'])) {

    $query = "SELECT f.*, t.*, f.abbrev AS abbrev, f.faculty_name AS faculty
FROM teacher t
INNER JOIN faculty f ON t.faculty_id = f.faculty_id;

";

    $res = mysqli_query($conn, $query);

    // Check if the query was successful
    if ($res === false) {
        // Output the error message and terminate the script
        die("Error in SQL query: " . mysqli_error($conn));
    }

    $i = 1;
    $table_data = "";
    $status = "";

    while ($data = mysqli_fetch_assoc($res)) {

        if ($data['status'] == 0) {
            $status = "<button class='btn btn-dark' onclick='toggle_status({$data['id']}, 1)' >Activated</button>";
        } else {
            $status = "<button class='btn btn-secondary'onclick='toggle_status({$data['id']}, 0)'>Inactive</button>";
        }

        $table_data .= "
            <tr>
                <td>$i</td>
                <td>{$data['name']}</td>
                <td>{$data['abbrev']}</td>
                <td>{$data['ID_number']}</td>
                <td>$status</td>
                <td>
                    <button class='btn btn-danger'><i class='bi bi-trash'></i></button>
                    <button class='btn btn-primary' data-id='{$data['id']}' data-bs-toggle='modal' data-bs-target='#edit'><i class='bi bi-pencil-square'></i></button>
                </td>
            </tr>
        ";
        $i++;
    }
    echo $table_data;
}

if (isset($_POST['get_teacher_records'])) {

    $query = "SELECT 
                    f.*, 
                    t.*, 
                    ts.*, 
                    s.*, 
                    f.abbrev AS abbrev, 
                    f.faculty_name AS faculty,
                    t.name AS teacher,
                    s.name AS subj
                FROM teacher t
                INNER JOIN faculty f ON t.faculty_id = f.faculty_id
                INNER JOIN teacher_subject ts ON t.id = ts.teacher_id
                INNER JOIN subject s ON ts.subject_id = s.subject_id";

    $res = mysqli_query($conn, $query);

    // Check if the query was successful
    if ($res === false) {
        die("Error in SQL query: " . mysqli_error($conn));
    }

    $i = 1;
    $table_data = "";

    while ($data = mysqli_fetch_assoc($res)) {

        $table_data .= "
            <tr>
                <td>$i</td>
                <td>{$data['teacher']}</td>
                <td>{$data['ID_number']}</td>
                <td>{$data['faculty']}</td>
                <td>{$data['abbrev']}</td>
                <td>{$data['code']} - {$data['subj']} </td>
            </tr>
        ";
        $i++;
    }
    echo $table_data;
}


if (isset($_POST['add_teacher'])) {
    $form = filter($_POST);
    $name = $form['name'];
    $ID = $form['ID'];
    $Faculty = $form['faculty'];

    if (empty($name) || empty($ID) || empty($Faculty)) {
        echo "error:missing_fields";
        exit;
    }

    $last_digit = substr($ID, -3);
    $plain_password = "SVFC2025#" . $last_digit;
    $subject = json_decode($_POST['subject']);

    if (!is_array($subject) || count($subject) === 0) {
        echo "error:empty_subjects";
        exit;
    }

    $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

    $flag = 0;

    $sql = "INSERT INTO `teacher`(`name`, `ID_number`, `faculty_id`, `password`) VALUES (?,?,?,?)";
    $values = [$name, $ID, $Faculty, $hashed_password];

    if (insert($sql, $values, "ssis")) {
        global $conn;
        $teacher_id = mysqli_insert_id($conn);

        if (!$teacher_id) {
            echo "error:teacher_id_not_found";
            exit;
        }

        $sql2 = "INSERT INTO `teacher_subject`(`teacher_id`, `subject_id`) VALUES (?,?)";
        if ($stmt = mysqli_prepare($conn, $sql2)) {
            foreach ($subject as $s) {
                mysqli_stmt_bind_param($stmt, 'ii', $teacher_id, $s);
                mysqli_stmt_execute($stmt);
            }
            mysqli_stmt_close($stmt);
            $flag = 1;
        } else {
            $flag = 0;
            die('query cannot be prepared for teacher_subject');
        }
    } else {
        echo "error:insert_failed";
        exit;
    }

    echo $flag;
}

if (isset($_POST['toggle_status'])) {
    $frm_data = filter($_POST);

    $q = "UPDATE `teacher` SET `status`=? WHERE `id`=?";
    $v = [$frm_data['value'], $frm_data['toggle_status']];

    if (update($q, $v, 'ii')) {
        echo 1;
    } else {
        echo 0;
    }
}
if (isset($_POST['fetch_teacher_details'])) {
    $id = $_POST['get_teacher'];

    $q = "SELECT * FROM teacher WHERE id=?";
    $res = select($q, [$id], "i");

    if (mysqli_num_rows($res) > 0) {
        $teacher = mysqli_fetch_assoc($res);

        $sub = "SELECT subject_id FROM teacher_subject WHERE teacher_id=?";
        $sub_res = select($sub, [$id], "i");
        $subjects = [];

        while ($s = mysqli_fetch_assoc($sub_res)) {
            $subjects[] = $s['subject_id'];
        }

        echo json_encode([
            'status' => 'success',
            'data' => [
                'id' => $teacher['id'],
                'name' => $teacher['name'],
                'faculty_id' => $teacher['faculty_id'],
                'subjects' => $subjects
            ]
        ]);
    } else {
        echo json_encode(['status' => 'error']);
    }
}