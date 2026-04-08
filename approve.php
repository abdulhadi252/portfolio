<?php
include("connect.php");

$id = $_GET['id'];

mysqli_query($connect,"UPDATE admins SET status='approved' WHERE id='$id'");

header("Location: manage-admins.php");