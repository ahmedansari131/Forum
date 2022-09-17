<?php

$showError = "false";
if($_SERVER["REQUEST_METHOD"] == "POST") {
    include '_dbconnect.php';
    $email = $_POST['loginEmail'];
    $password = $_POST['loginPassword'];

    $sql = "SELECT * from users where user_email = '$email'";
    $result = mysqli_query($conn, $sql);

    $numRows = mysqli_num_rows($result);
    if($numRows == 1) {
        $row = mysqli_fetch_assoc($result);
        if(password_verify($password, $row['user_password'])) {
            session_start();
            $_SESSION['loggedin'] = true;
            $_SESSION['useremail'] = $email;
            $_SESSION['sno'] = $row['sno'];
            // echo "Logged in " . $email;
        } 
        else {
            echo "Unable to login";
        }
        header('Location: /forum/index.php');
    } 
    header('Location: /forum/index.php');

}

?>