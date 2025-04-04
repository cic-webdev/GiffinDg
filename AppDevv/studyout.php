<?php
session_start();
session_destroy();
header("Location: studylog.php");
exit;
?>
