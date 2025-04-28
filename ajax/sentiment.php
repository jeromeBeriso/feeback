<?php
include '../admin/components/dbconfig.php';
include '../admin/components/components.php';

studentLogin();

if (isset($_POST['submit_feedback'])) {
    $feedback_msg = $_POST['feedback_msg'];
    $feedback_id = $_POST['feedback_id'];

    $escaped_msg = escapeshellarg($feedback_msg);

    $path = "C:\Users\Jerome\AppData\Local\Programs\Python\Python312\python.exe";
    $script = realpath('../py/sentiment.py');

    $command = escapeshellcmd($path) . ' ' . escapeshellarg($script) . ' ' . $escaped_msg;
    $output = shell_exec($command);

    if ($output) {
        $result = json_decode($output, true);
        $sentiment = $result['sentiment'];
        $verdict = $result['verdict'];

        $sql = "UPDATE feedback SET response = ?,  sentiment = ?, verdict = ?, status = ? WHERE feedback_id = ?";
        $values = [$feedback_msg, $sentiment, $verdict, 1, $feedback_id];
        $dtypes = "sssii";

        if (update($sql, $values, $dtypes)) {
            echo 1; // Success
        } else {
            echo 0; // SQL error
        }
    } else {
        echo "error running python";
    }
}
?>
