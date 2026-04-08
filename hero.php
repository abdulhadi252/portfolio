<?php
include("connect.php");

$result = mysqli_query($connect, "SELECT * FROM hero WHERE id=1");
$row = mysqli_fetch_assoc($result);

if(isset($_POST['update'])){

    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    $desc = $_POST['description'];

    $btn1_text = $_POST['button_text'];
    $btn1_link = $_POST['button_link'];

    $btn2_text = $_POST['button2_text'];
    $btn2_link = $_POST['button2_link'];

    $image = $_FILES['image']['name'];

    if(!empty($image)){
        $tmp = $_FILES['image']['tmp_name'];
        move_uploaded_file($tmp, "uploads/".$image);

        mysqli_query($connect,"UPDATE hero SET 
        title='$title',
        subtitle='$subtitle',
        description='$desc',
        button_text='$btn1_text',
        button_link='$btn1_link',
        button2_text='$btn2_text',
        button2_link='$btn2_link',
        image='$image'
        WHERE id=1");

    } else {

        mysqli_query($connect,"UPDATE hero SET 
        title='$title',
        subtitle='$subtitle',
        description='$desc',
        button_text='$btn1_text',
        button_link='$btn1_link',
        button2_text='$btn2_text',
        button2_link='$btn2_link'
        WHERE id=1");
    }

    header("Location: hero.php");
}
?>

<h2>Hero Section</h2>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="title" value="<?php echo $row['title']; ?>" placeholder="Title"><br><br>

<input type="text" name="subtitle" value="<?php echo $row['subtitle']; ?>" placeholder="Subtitle"><br><br>

<textarea name="description"><?php echo $row['description']; ?></textarea><br><br>

<h3>Button 1</h3>
<input type="text" name="button_text" value="<?php echo $row['button_text']; ?>" placeholder="Text"><br><br>
<input type="text" name="button_link" value="<?php echo $row['button_link']; ?>" placeholder="Link"><br><br>

<h3>Button 2 (Resume)</h3>
<input type="text" name="button2_text" value="<?php echo $row['button2_text']; ?>" placeholder="Text"><br><br>
<input type="text" name="button2_link" value="<?php echo $row['button2_link']; ?>" placeholder="Link (uploads/resume.pdf)"><br><br>

<input type="file" name="image"><br><br>

<button type="submit" name="update">Update Hero</button>

</form>