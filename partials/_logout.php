<?php

session_start();
echo "Logging out";
session_destroy();
header('Location: /forum/index.php');

?>