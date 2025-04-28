<?php
include '../components/components.php';
include '../components/dbconfig.php';

try {
  $query = "SELECT 
                  COUNT(*) AS total_feedbacks,
                  COUNT(CASE WHEN sentiment IS NOT NULL THEN 1 END) AS participated_feedbacks
              FROM feedback";
  $result = $conn->query($query);

  if (!$result) {
    throw new Exception("Database query failed: " . $conn->error);
  }

  $data = $result->fetch_assoc();

  $totalFeedbacks = $data['total_feedbacks'];
  $participatedFeedbacks = $data['participated_feedbacks'];

  $participationRate = ($totalFeedbacks > 0) ? ($participatedFeedbacks / $totalFeedbacks) * 100 : 0;


  $response = [
    'participationRate' => $participationRate,
    'totalFeedbacks' => $totalFeedbacks,
    'participatedFeedbacks' => $participatedFeedbacks
  ];

  echo json_encode($response);
} catch (Exception $e) {
  echo json_encode(['error' => $e->getMessage()]);
}