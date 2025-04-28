<?php
require('../../admin/components/components.php');
require('../../admin/components/dbconfig.php');

teacherLogin();

$teacher_id = $_SESSION['teacherID'];

$query = "
    SELECT 
        COUNT(CASE WHEN sentiment = 'positive' THEN 1 END) AS positive,
        COUNT(CASE WHEN sentiment = 'neutral' THEN 1 END) AS neutral,
        COUNT(CASE WHEN sentiment = 'negative' THEN 1 END) AS negative
    FROM feedback
    WHERE teacher_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

echo json_encode([
    'positive' => (int) $result['positive'],
    'neutral' => (int) $result['neutral'],
    'negative' => (int) $result['negative']
]);