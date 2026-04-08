<?php
include("connect.php");

$id = $_GET['id'];

mysqli_query($connect, "DELETE FROM projects WHERE id=$id");

header("Location: projects.php");
?>