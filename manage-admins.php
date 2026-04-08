<?php
session_start();
include("connect.php");

if($_SESSION['role']!='super_admin'){
    die("Access Denied");
}

$q = mysqli_query($connect,"SELECT * FROM admins");
?>

<link rel="stylesheet" href="style.css">

<div class="container">
<h2>Manage Admins</h2>

<?php while($row = mysqli_fetch_assoc($q)){ ?>

<p><?php echo $row['email']; ?> (<?php echo $row['status']; ?>)</p>

<?php if($row['status']=='pending'){ ?>
<a href="approve.php?id=<?php echo $row['id']; ?>">Approve</a>
<?php } ?>

<hr>

<?php } ?>

<a href="dashboard.php">Back</a>
</div>