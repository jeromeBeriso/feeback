<?php
$host = 'localhost';
$server = 'root';
$pass = '';
$database = 'feedbackSys';

$conn = mysqli_connect($host, $server, $pass, $database);

if (!$conn) {
    die("Error connecting to the database" . mysqli_connect_error());
}

function filter($data)
{
    // Check if the input is an array
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = trim($value);
            $data[$key] = stripcslashes($data[$key]);
            $data[$key] = htmlspecialchars($data[$key]);
            $data[$key] = strip_tags($data[$key]);
        }
    } else {
        $data = trim($data);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);
        $data = strip_tags($data);
    }


    return $data;
}


function select($sql, $values, $dtypes)
{
    $conn = $GLOBALS['conn'];

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, $dtypes, ...$values);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if ($res) {
            mysqli_stmt_close($stmt);
            return $res;
        } else {
            die('Error executing statement: ' . mysqli_stmt_error($stmt));
        }
    } else {
        die('Error preparing statement: ' . mysqli_error($conn));
    }
}

function update($sql, $values, $dtypes)
{
    $conn = $GLOBALS['conn'];
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, $dtypes, ...$values);
        if (mysqli_stmt_execute($stmt)) {
            $res = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            return $res;
        } else {
            throw new Exception('Error executing statement: ' . mysqli_stmt_error($stmt));
        }
    } else {
        throw new Exception('Error preparing statement: ' . mysqli_error($conn));
    }
}
function insert($sql, $values, $dtypes)
{
    $conn = $GLOBALS['conn'];

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, $dtypes, ...$values);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return true; // Return true if the query was successful
        } else {
            die('Error executing Insert statement: ' . mysqli_stmt_error($stmt));
        }
    } else {
        die('Error preparing statement: ' . mysqli_error($conn));
    }
}


function selectAll($table)
{
    $conn = $GLOBALS['conn'];
    $res = mysqli_query($conn, "SELECT * FROM $table");
    return $res;
}

function delete($sql, $values, $dtypes)
{
    $conn = $GLOBALS['conn'];

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, $dtypes, ...$values);
        if (mysqli_stmt_execute($stmt)) {
            $res = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            return $res;
        } else {
            $error_message = mysqli_error($conn); // Get detailed error message
            mysqli_stmt_close($stmt);
            die("Query cannot be executed Delete: $error_message");
        }
    } else {
        $error_message = mysqli_error($conn); // Get detailed error message
        die("Query cannot be prepared: $error_message");
    }
}

?>