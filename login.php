<?php
session_start();
include("connect.php");

if(isset($_POST['login'])){

$email = $_POST['email'];
$password = $_POST['password'];

$q = mysqli_query($connect,"SELECT * FROM admins WHERE email='$email'");
$user = mysqli_fetch_assoc($q);

if($user){

    if($user['status'] != 'approved'){
        $msg = "Wait for approval!";
    }
    elseif(password_verify($password,$user['password'])){
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        header("Location: dashboard.php");
        exit;
    }else{
        $msg = "Wrong password";
    }

}else{
    $msg = "User not found";
}
}
?>