<?php
require('../../admin/components/components.php');
require('../../admin/components/dbconfig.php');
teacherLogin();

if (isset($_POST['get_subject'])) {
    $teacher_id = $_SESSION['teacherID'];

    $query = "SELECT DISTINCT sub.subject_id, sub.code, sub.name
              FROM feedback f
              INNER JOIN subject sub ON f.subject_id = sub.subject_id
              WHERE f.teacher_id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $teacher_id);
    $stmt->execute();
    $res = $stmt->get_result();

    $i = 1;
    $table_data = "";

    while ($data = $res->fetch_assoc()) {
        $table_data .= "
            <tr>
                <td>$i</td>
                <td>{$data['code']} - {$data['name']}</td>
                <td>
                    <button class='btn btn-primary' onclick='getFeedbackRecords({$data['subject_id']})' data-bs-toggle='modal'
                        data-bs-target='#record'>
                        <i class='bi bi-eye'></i>
                    </button>
                </td>
            </tr>
        ";
        $i++;
    }

    echo $table_data;
}

if (isset($_POST['getFeedbackRecords']) && isset($_POST['subject_id'])) {
    $subject_id = $_POST['subject_id'];
    $teacher_id = $_SESSION['teacherID'];
    $status = 1;

    $subjectQuery = "SELECT code, name FROM subject WHERE subject_id = ?";
    $stmt1 = $conn->prepare($subjectQuery);
    $stmt1->bind_param("i", $subject_id);
    $stmt1->execute();
    $subjectResult = $stmt1->get_result()->fetch_assoc();

    $subjectFullName = $subjectResult['code'] . " - " . $subjectResult['name'];

    // Get feedback records
    $query = "SELECT f.*, s.name
              FROM feedback f
              INNER JOIN session s ON f.session_id = s.session_id
              WHERE f.subject_id = ? AND f.teacher_id = ? AND f.status = ?";
    $stmt2 = $conn->prepare($query);

    if (!$stmt2) {
        die("SQL error: " . $conn->error);
    }

    $stmt2->bind_param("iii", $subject_id, $teacher_id, $status);
    $stmt2->execute();
    $res = $stmt2->get_result();

    $i = 1;
    $table_data = "";

    while ($row = $res->fetch_assoc()) {
        $message = htmlspecialchars($row['response']);
        $response = ucfirst($row['sentiment']);
        $verdict = htmlspecialchars($row['verdict']);
        $semester = htmlspecialchars($row['name']);

        $badgeClass = 'text-secondary';
        if ($response === 'Positive') $badgeClass = 'text-success';
        elseif ($response === 'Negative') $badgeClass = 'text-danger';

        $table_data .= "
            <tr>
                <td>{$i}</td>
                <td>{$message}<br><small class='text-muted'>Semester: {$semester}</small></td>
                <td><span class='badge bg-white mt-2 {$badgeClass}'>{$response}</span></td>
                <td><span class='mt-3'>{$verdict}</span></td>
            </tr>
        ";
        $i++;
    }


    echo json_encode([
        'subject_name' => $subjectFullName,
        'table_data' => $table_data
    ]);
}