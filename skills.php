<?php
include("connect.php");

if(isset($_POST['add_skill'])){
    $skills = $_POST['skills'];

    $skillsArray = explode(",", $skills);

    foreach($skillsArray as $skill){
        $skill = trim($skill);

        if(!empty($skill)){
            mysqli_query($connect, "INSERT INTO skills(skill_name) VALUES('$skill')");
        }
    }

    header("Location: skill.php");
}
?>

<h2>Add Your Skills</h2>

<form method="POST">
    <input type="text" name="skills" placeholder="HTML, CSS, JavaScript">
    <button type="submit" name="add_skill">Add</button>
</form>

<hr>

<h3>All Skills</h3>

<?php
$result = mysqli_query($connect, "SELECT * FROM skills");

while($row = mysqli_fetch_assoc($result)){
    echo "<p>".$row['skill_name']."</p>";
}
?>