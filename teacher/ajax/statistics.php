<?php
require('../../admin/components/components.php');
require('../../admin/components/dbconfig.php');

teacherLogin();

$teacher_id = $_SESSION['teacherID'];

$query = "
    SELECT 
        s.name AS session_name,
        COUNT(f.feedback_id) AS total_feedback
    FROM feedback f
    INNER JOIN session s ON f.session_id = s.session_id
    WHERE f.teacher_id = ?
    GROUP BY s.name
    ORDER BY s.name
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = [
        'label' => $row['session_name'],
        'count' => (int)$row['total_feedback']
    ];
}

echo json_encode($data);