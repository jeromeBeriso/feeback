<?php
require 'components.php';

session_name('admin_session');
session_start();
session_unset();
session_destroy();
redirect('../index.php');
?>