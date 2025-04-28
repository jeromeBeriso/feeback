<?php
include '../components/components.php';
include '../components/dbconfig.php';

if (isset($_POST['get_faculty'])) {

    $query = "SELECT * FROM `faculty`";

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
                <td>{$data['faculty_name']}</td>
                <td>{$data['abbrev']}</td>
                <td>
                <button class='btn btn-danger' onclick='remove_faculty({$data['faculty_id']})' ><i class='bi bi-trash'></i></button>
            </td>
            </tr>
        ";
        $i++;
    }
    echo $table_data;
}

if (isset($_POST['get_all_faculty'])) {

    $query = "SELECT * FROM `faculty`";

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
                <td>{$data['faculty_name']}</td>
                <td>{$data['abbrev']}</td>
            </tr>
        ";
        $i++;
    }
    echo $table_data;
}
if (isset($_POST['add_faculty'])) {
    $form = filter($_POST);
    $name = $form['faculty'];
    $ABV = $form['ABV'];


    // Insert into the database
    $sql = "INSERT INTO `faculty`(`faculty_name`, `abbrev`) VALUES (?,?)";
    $values = [$name, $ABV];
    $dtypes = "ss";



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

if (isset($_POST['remove_faculty'])) {
    $frm_data = filter($_POST);

    $res2 = delete("DELETE FROM `faculty` WHERE `faculty_id`=?", [$frm_data['id']], 'i');

    if ($res2) {
        echo 1;
    } else {
        echo 0;
    }
}