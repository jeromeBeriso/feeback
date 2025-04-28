<?php
include '../admin/components/dbconfig.php';
include '../admin/components/components.php';
studentLogin();

if (isset($_POST['get_feedbacks'])) {
    $student_id = $_SESSION['studentID'];

    // Fetch feedbacks with related subject, teacher, and session info
    $query = select(
        "SELECT f.*, s.code, s.name AS subname, t.name AS teacher, se.status AS session_status
        FROM feedback f
        INNER JOIN subject s ON s.subject_id = f.subject_id
        INNER JOIN teacher t ON t.id = f.teacher_id
        INNER JOIN session se ON se.session_id = f.session_id
        WHERE f.student_id = ?",
        [$student_id],
        'i'
    );

    // Get student's faculty
    $squery = select(
        "SELECT faculty_id FROM student WHERE id = ?",
        [$student_id],
        'i'
    );
    $res = mysqli_fetch_assoc($squery);

    $i = 1;
    $table_data = "";
    $has_valid_feedback = false;

    while ($data = mysqli_fetch_assoc($query)) {
        if ($data['session_status'] == 0) {
            $has_valid_feedback = true;

            if ($data['status'] == 0) {
                $url = "ajax/redirect.php?subject_id={$data['subject_id']}&feedback_id={$data['feedback_id']}&student_id={$data['student_id']}&faculty_id={$res['faculty_id']}&teacher_id={$data['teacher_id']}";
                $status = "<a href='$url' class='btn btn-sm btn-primary shadow-none'>Take Feedback</a>";
            } else {
                $status = "<span class='badge text-success bg-light'>Already Responded</span>";
            }

            $table_data .= "
                <tr>
                    <td>$i</td>
                    <td>{$data['code']} - {$data['subname']}</td>
                    <td>{$data['teacher']}</td>
                    <td>$status</td>
                </tr>
            ";
            $i++;
        }
    }

    // If no valid feedbacks found
    if (!$has_valid_feedback) {
        $table_data .= "
            <tr>
                <td colspan='4' class='text-center'>No Feedback Data Available</td>
            </tr>
        ";
    }

    echo $table_data;
}