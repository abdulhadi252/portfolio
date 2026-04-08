<?php
include("connect.php");

$data = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM stats WHERE id=1"));

if(isset($_POST['update'])){
    $p = $_POST['projects'];
    $s = $_POST['small'];
    $e = $_POST['experience'];
    $sem = $_POST['seminars'];

    mysqli_query($connect,"UPDATE stats SET 
    projects_completed='$p',
    small_projects='$s',
    experience='$e',
    seminars='$sem'
    WHERE id=1");

    header("Location: stats.php");
}
?>

<h2>Update Stats</h2>

<form method="POST">

<input type="text" name="projects" value="<?php echo $data['projects_completed']; ?>"><br><br>

<input type="text" name="small" value="<?php echo $data['small_projects']; ?>"><br><br>

<input type="text" name="experience" value="<?php echo $data['experience']; ?>"><br><br>

<input type="text" name="seminars" value="<?php echo $data['seminars']; ?>"><br><br>

<button type="submit" name="update">Update</button>

</form>