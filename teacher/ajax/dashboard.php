<?php
require('../../admin/components/components.php');
require('../../admin/components/dbconfig.php');

teacherLogin();

$session_id = $_GET['session_id'] ?? null;
$teacher_id = $_SESSION['teacher_id'];

$where = "WHERE s.teacher_id = ?";
$params = [$teacher_id];
$types = "i";

if ($session_id) {
    $where .= " AND f.session_id = ?";
    $params[] = $session_id;
    $types .= "i";
}

// Feedback Summary Query (JOIN with session to validate teacher)
$query = "
    SELECT 
        COUNT(CASE WHEN f.sentiment = 'positive' THEN 1 END) AS total_positive,
        COUNT(CASE WHEN f.sentiment = 'negative' THEN 1 END) AS total_negative,
        COUNT(CASE WHEN f.sentiment = 'neutral' THEN 1 END) AS total_neutral,
        COUNT(CASE WHEN f.status = '1' THEN 1 END) AS new_feedback,
        COUNT(*) AS total_feedbacks
    FROM feedback f
    INNER JOIN session s ON f.session_id = s.session_id
    $where
";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

$positive = $data['total_positive'];
$total = $data['total_feedbacks'];
$verdict = $total > 0 ? round(($positive / $total) * 100) : 0;

if ($positive >= $data['total_negative'] && $positive >= $data['total_neutral']) {
    $message = "Great job! Most feedback is positive.";
} elseif ($data['total_negative'] >= $positive && $data['total_negative'] >= $data['total_neutral']) {
    $message = "Heads up! Negative feedback is the highest.";
} else {
    $message = "Feedback is mostly neutral. You’re doing fine.";
}

$student_query = "
    SELECT COUNT(DISTINCT f.student_id) AS students_count 
    FROM feedback f
    INNER JOIN session s ON f.session_id = s.session_id
    $where
";

$student_stmt = $conn->prepare($student_query);
$student_stmt->bind_param($types, ...$params);
$student_stmt->execute();
$student_result = $student_stmt->get_result()->fetch_assoc();


echo json_encode([
    'total_positive' => $data['total_positive'],
    'total_negative' => $data['total_negative'],
    'total_neutral' => $data['total_neutral'],
    'new_feedback' => $data['new_feedback'],
    'total_feedbacks' => $data['total_feedbacks'],
    'verdict_percentage' => $verdict,
    'message' => $message,
    'students_count' => $student_result['students_count']
]);