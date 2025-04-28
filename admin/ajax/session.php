<?php
include '../components/components.php';
include '../components/dbconfig.php';

if (isset($_POST['add_session'])) {
    try {
        $session_name = $_POST['session_name'];
        $faculty_id = $_POST['faculty_id'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];

        // Insert session
        $insertSession = "INSERT INTO session (name, start_time, end_time)
                          VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertSession);
        $stmt->bind_param("sss", $session_name, $start_time, $end_time);
        $stmt->execute();

        $session_id = $conn->insert_id;

        $faculties = [];

        if ($faculty_id === "") {
            $facultyQuery = "SELECT faculty_id FROM faculty";
            $res = $conn->query($facultyQuery);
            while ($row = $res->fetch_assoc()) {
                $faculties[] = $row['faculty_id'];
            }
        } else {
            $faculties[] = $faculty_id;
        }

        foreach ($faculties as $fac_id) {
            // Get students
            $studentsQuery = "SELECT id FROM student WHERE faculty_id = ?";
            $stmt = $conn->prepare($studentsQuery);
            $stmt->bind_param("i", $fac_id);
            $stmt->execute();
            $studentsResult = $stmt->get_result();

            while ($student = $studentsResult->fetch_assoc()) {
                $student_id = $student['id'];

                // Get teachers in the same faculty
                $teacherQuery = "SELECT id FROM teacher WHERE faculty_id = ?";
                $teacherStmt = $conn->prepare($teacherQuery);
                $teacherStmt->bind_param("i", $fac_id);
                $teacherStmt->execute();
                $teacherResult = $teacherStmt->get_result();

                while ($teacher = $teacherResult->fetch_assoc()) {
                    $teacher_id = $teacher['id'];

                    // Get subjects taught by the teacher
                    $subjectQuery = "SELECT subject_id FROM teacher_subject WHERE teacher_id = ?";
                    $subjectStmt = $conn->prepare($subjectQuery);
                    $subjectStmt->bind_param("i", $teacher_id);
                    $subjectStmt->execute();
                    $subjectResult = $subjectStmt->get_result();

                    while ($subject = $subjectResult->fetch_assoc()) {
                        $subject_id = $subject['subject_id'];

                        // Insert feedback
                        $insertFeedback = "INSERT INTO feedback (session_id, student_id, teacher_id, subject_id, status)
                                           VALUES (?, ?, ?, ?, 0)";
                        $feedbackStmt = $conn->prepare($insertFeedback);
                        $feedbackStmt->bind_param("iiii", $session_id, $student_id, $teacher_id, $subject_id);
                        $feedbackStmt->execute();
                    }
                }
            }
        }

        echo 1;
    } catch (Exception $e) {
        echo 0;
    }
}

if (isset($_POST['get_session'])) {

    $query = "SELECT s.*, s.session_id AS status_update, 
              (SELECT COUNT(*) FROM feedback WHERE session_id = s.session_id) AS feedback_count
              FROM session s
              WHERE s.status = 0
              ORDER BY s.created_at DESC";

    $result = $conn->query($query);

    $i = 1;
    $output = '';

    while ($row = $result->fetch_assoc()) {
        $name = htmlspecialchars($row['name']);
        $start = date("M d, Y - g:i A", strtotime($row['start_time']));
        $end = date("M d, Y - g:i A", strtotime($row['end_time']));
        $count = $row['feedback_count'];
        $created = date("M d, Y - g:i A", strtotime($row['created_at']));
        $status_update = (int)$row['status_update']; // cast to int just in case

        $output .= "
            <tr>
                <td>{$i}</td>
                <td>{$name}</td>
                <td>{$start}</td>
                <td>{$end}</td>
                <td>{$count}</td>
                <td>{$created}</td>
                <td>
                    <button class='btn btn-outline-danger' onclick='status_update({$status_update})'>End Session</button>
                </td>
            </tr>
        ";
        $i++;
    }

    echo $output;
}

if (isset($_POST['get_all_session'])) {

    $query = "SELECT s.*, s.session_id AS status_update, 
              (SELECT COUNT(*) FROM feedback WHERE session_id = s.session_id) AS feedback_count
              FROM session s
              WHERE s.status = 0
              ORDER BY s.created_at DESC";

    $result = $conn->query($query);

    $i = 1;
    $output = '';

    while ($row = $result->fetch_assoc()) {
        $name = htmlspecialchars($row['name']);
        $start = date("M d, Y - g:i A", strtotime($row['start_time']));
        $end = date("M d, Y - g:i A", strtotime($row['end_time']));
        $count = $row['feedback_count'];
        $created = date("M d, Y - g:i A", strtotime($row['created_at']));
        $status_update = (int)$row['status_update']; // cast to int just in case

        $output .= "
            <tr>
                <td>{$i}</td>
                <td>{$name}</td>
                <td>{$start}</td>
                <td>{$end}</td>
                <td>{$count}</td>
                <td>{$created}</td>
            </tr>
        ";
        $i++;
    }

    echo $output;
}

if (isset($_POST['status_update'])) {
    $frm_data = filter($_POST);

    $q = "UPDATE `session` SET `status`=? WHERE `session_id`=?";
    $v = [1, $frm_data['status_update']];

    if (update($q, $v, 'ii')) {
        echo 1;
    } else {
        echo 0;
    }
}