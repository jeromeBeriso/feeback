<?php
include '../admin/components/dbconfig.php';
include '../admin/components/components.php';
studentLogin();

if (
    !isset($_GET['feedback_id']) ||
    !isset($_GET['student_id']) ||
    !isset($_GET['teacher_id']) ||
    !isset($_GET['faculty_id'])
) {
    alert('error', 'Invalid access detected.');
    redirect("error_page.php");
    exit;
}

$feedback_id = intval($_GET['feedback_id']);
$student_id = intval($_GET['student_id']);
$teacher_id = intval($_GET['teacher_id']);
$faculty_id = intval($_GET['faculty_id']);

// Check if feedback exists
$query = select("SELECT * FROM feedback WHERE feedback_id = ?", [$feedback_id], "i");
$res = mysqli_fetch_assoc($query);

if (!$res) {
    alert('error', 'Feedback not found.');
    redirect("error_page.php");
    exit;
}

// Check if student has already responded
$responseCheck = select("SELECT status FROM feedback WHERE feedback_id = ? AND student_id = ?", [$feedback_id, $student_id], "ii");
$responseData = mysqli_fetch_assoc($responseCheck);

if (!$responseData || $responseData['status'] == 1) {
    redirect("already_responded.php?subject_id={$subject_id}&feedback_id={$feedback_id}&student_id={$student_id}&faculty_id={$faculty_id}&teacher_id={$teacher_id}");
    exit;
}

// If valid, continue to the feedback form
$subject_id = $res['subject_id'];
$url = "../feedback_form.php?subject_id={$subject_id}&feedback_id={$feedback_id}&student_id={$student_id}&faculty_id={$faculty_id}&teacher_id={$teacher_id}";
redirect($url);
exit;