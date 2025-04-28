<?php
include '../components/components.php';
include '../components/dbconfig.php';

// Query for all feedback sentiment counts
$query = "
    SELECT 
        COUNT(CASE WHEN sentiment = 'positive' THEN 1 END) AS positive,
        COUNT(CASE WHEN sentiment = 'neutral' THEN 1 END) AS neutral,
        COUNT(CASE WHEN sentiment = 'negative' THEN 1 END) AS negative
    FROM feedback
    WHERE sentiment IS NOT NULL
";

$result = $conn->query($query);
$row = $result->fetch_assoc();

// Return data as JSON
echo json_encode([
    'positive' => (int) $row['positive'],
    'neutral' => (int) $row['neutral'],
    'negative' => (int) $row['negative']
]);