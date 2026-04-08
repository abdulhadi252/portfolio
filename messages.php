<?php
include("connect.php");

// APPROVE
if(isset($_GET['approve'])){
    $id = $_GET['approve'];
    mysqli_query($connect, "UPDATE messages SET status='approved' WHERE id=$id");
    header("Location: messages.php");
}

// DELETE
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($connect, "DELETE FROM messages WHERE id=$id");
    header("Location: messages.php");
}

// FETCH DATA
$result = mysqli_query($connect, "SELECT * FROM messages ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Messages</title>
    <style>
        body{font-family:Arial; background:#111; color:#fff;}
        table{width:100%; border-collapse:collapse;}
        th,td{padding:10px; border:1px solid #444;}
        th{background:#222;}
        a{padding:5px 10px; text-decoration:none; border-radius:5px;}
        .approve{background:green; color:#fff;}
        .delete{background:red; color:#fff;}
    </style>
</head>
<body>

<h2>Contact Messages</h2>

<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Message</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['email']; ?></td>
    <td><?php echo $row['phone']; ?></td>
    <td><?php echo $row['message']; ?></td>
    <td><?php echo $row['status']; ?></td>
    <td>
        <?php if($row['status'] == 'pending'){ ?>
            <a class="approve" href="?approve=<?php echo $row['id']; ?>">Approve</a>
        <?php } ?>
        <a class="delete" href="?delete=<?php echo $row['id']; ?>">Delete</a>
    </td>
</tr>

<?php } ?>

</table>

</body>
</html>