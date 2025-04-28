<?php
include '../components/components.php';
include '../components/dbconfig.php';

$query = "
    SELECT 
        s.name AS session_name,
        ROUND(
            (SUM(
                CASE f.sentiment
                    WHEN 'positive' THEN 1
                    WHEN 'neutral' THEN 0.5
                    ELSE 0
                END
            ) / COUNT(*)) * 100, 2
        ) AS satisfaction_rate
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
        'satisfactionRate' => $row['satisfaction_rate']
    ];
}

echo json_encode($data);