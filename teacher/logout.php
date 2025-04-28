<?php
      include '../admin/components/components.php';

session_name('teacher_session');
session_start();
session_unset();
session_destroy();
redirect('index.php');
?>