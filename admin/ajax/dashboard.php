<?php
include '../components/dbconfig.php';
include '../components/components.php';

$session_id = isset($_GET['session_id']) ? intval($_GET['session_id']) : 0;

// Initialize response array
$response = [];

// Adjust feedback query to fetch data for all semesters if session_id is 0
$feedbackQuery = "SELECT 
    IFNULL(SUM(CASE WHEN sentiment = 'Positive' THEN 1 ELSE 0 END), 0) AS total_positive,
    IFNULL(SUM(CASE WHEN sentiment = 'Negative' THEN 1 ELSE 0 END), 0) AS total_negative,
    IFNULL(SUM(CASE WHEN sentiment = 'Neutral' THEN 1 ELSE 0 END), 0) AS total_neutral,
    COUNT(*) AS total_feedback
FROM feedback
                  WHERE ($session_id = 0 OR session_id = $session_id) AND sentiment IS NOT NULL"; // Exclude NULL values from feedback count

$result = mysqli_query($conn, $feedbackQuery);
if ($row = mysqli_fetch_assoc($result)) {
    $response['total_positive'] = $row['total_positive'];
    $response['total_negative'] = $row['total_negative'];
    $response['total_neutral'] = $row['total_neutral'];
    $response['total_feedback'] = $row['total_feedback'];
}

// Query to get recent feedbacks
$recentFeedbackQuery = "SELECT COUNT(*) AS recent_feedback FROM feedback WHERE ($session_id = 0 OR session_id = $session_id) AND created_at >= NOW() - INTERVAL 30 DAY";
$result = mysqli_query($conn, $recentFeedbackQuery);
if ($row = mysqli_fetch_assoc($result)) {
    $response['recent_feedback'] = $row['recent_feedback'];
}

// Query to get participating students
$participatingStudentsQuery = "SELECT COUNT(DISTINCT student_id) AS participating_students FROM feedback WHERE ($session_id = 0 OR session_id = $session_id)";
$result = mysqli_query($conn, $participatingStudentsQuery);
if ($row = mysqli_fetch_assoc($result)) {
    $response['participating_students'] = $row['participating_students'];
}

// Query to get total students
$totalStudentsQuery = "SELECT COUNT(*) AS total_students FROM student WHERE faculty_id IN (SELECT faculty_id FROM teacher WHERE id IN (SELECT DISTINCT teacher_id FROM feedback WHERE ($session_id = 0 OR session_id = $session_id)))";
$result = mysqli_query($conn, $totalStudentsQuery);
if ($row = mysqli_fetch_assoc($result)) {
    $response['total_students'] = $row['total_students'];
}

// Query to get newly added teachers
$newTeachersQuery = "SELECT COUNT(*) AS new_teachers FROM teacher WHERE created_at >= NOW() - INTERVAL 30 DAY";
$result = mysqli_query($conn, $newTeachersQuery);
if ($row = mysqli_fetch_assoc($result)) {
    $response['new_teachers'] = $row['new_teachers'];
}

// Query to get total submissions by teachers
$teacherSubmissionsQuery = "SELECT COUNT(*) AS teacher_submissions FROM teacher_feeds WHERE feedback_id IN (SELECT feedback_id FROM feedback WHERE ($session_id = 0 OR session_id = $session_id))";
$result = mysqli_query($conn, $teacherSubmissionsQuery);
if ($row = mysqli_fetch_assoc($result)) {
    $response['teacher_submissions'] = $row['teacher_submissions'];
}

// Query to get active teachers
$activeTeachersQuery = "SELECT COUNT(*) AS active_teachers FROM teacher WHERE id IN (SELECT DISTINCT teacher_id FROM feedback WHERE ($session_id = 0 OR session_id = $session_id))";
$result = mysqli_query($conn, $activeTeachersQuery);
if ($row = mysqli_fetch_assoc($result)) {
    $response['active_teachers'] = $row['active_teachers'];
}

// Query to get total teachers
$totalTeachersQuery = "SELECT COUNT(*) AS total_teachers FROM teacher";
$result = mysqli_query($conn, $totalTeachersQuery);
if ($row = mysqli_fetch_assoc($result)) {
    $response['total_teachers'] = $row['total_teachers'];
}

// Calculate overall verdict as a percentage
if ($response['total_feedback'] > 0) {
    // Only consider non-null feedback for the verdict
    $total_valid_feedback = $response['total_positive'] + $response['total_negative'] + $response['total_neutral'];
    if ($total_valid_feedback > 0) {
        // Calculate the verdict
        $verdict = (($response['total_positive'] - $response['total_negative']) / $total_valid_feedback) * 100;

        // Check if the result is an integer and format accordingly
        if (in_array($verdict, [50, 51])) {
            $response['overall_verdict'] = round($verdict) . '%'; // Ignore decimals for 50 or 51
        } else {
            $response['overall_verdict'] = number_format($verdict, 2) . '%'; // Show decimals for others
        }
    } else {
        $response['overall_verdict'] = '0%'; // If no valid feedback, verdict is 0%
    }
} else {
    $response['overall_verdict'] = '0%'; // No feedback, verdict is 0%
}


// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);