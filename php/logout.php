<?php
session_start();
session_destroy();
header("Location: project1.html");
exit();
?>
