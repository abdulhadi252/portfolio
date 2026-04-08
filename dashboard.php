<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
}
?>

<link rel="stylesheet" href="style.css">

<div class="container">
<h2>Dashboard</h2>

<a href="profile.php">Profile</a><br><br>

<?php if($_SESSION['role']=='super_admin'){ ?>
<a href="manage-admins.php">Manage Admins</a><br><br>
<?php } ?>

<a href="logout.php">Logout</a>
</div>