<?php
include '../components/components.php';
include '../components/dbconfig.php';

$query = "
    SELECT 
        s.name AS session_name,
        COUNT(f.feedback_id) AS total_feedback
    FROM feedback f
    INNER JOIN session s ON f.session_id = s.session_id
    GROUP BY s.name
    ORDER BY s.name
";

$result = $conn->query($query);

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = [
        'label' => $row['session_name'],
        'count' => (int)$row['total_feedback']
    ];
}

echo json_encode($data);