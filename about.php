<?php
session_start();
include("connect.php");

$data = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM about WHERE id=1"));

if (isset($_POST['update'])) {

    $title   = mysqli_real_escape_string($connect, $_POST['title']);
    $head2   = mysqli_real_escape_string($connect, $_POST['head2']);
    $para1   = mysqli_real_escape_string($connect, $_POST['para1']);
    $para2   = mysqli_real_escape_string($connect, $_POST['para2']);
    $tagline = mysqli_real_escape_string($connect, $_POST['tagline']);

    $image = $_FILES['image']['name'] ?? '';
    $tmp   = $_FILES['image']['tmp_name'] ?? '';

    if (!empty($image)) {
        move_uploaded_file($tmp, "uploads/" . $image);

        $query = "UPDATE about SET 
            title='$title',
            head2='$head2',
            para1='$para1',
            para2='$para2',
            tagline='$tagline',
            image='$image'
            WHERE id=1";
    } else {
        $query = "UPDATE about SET 
            title='$title',
            head2='$head2',
            para1='$para1',
            para2='$para2',
            tagline='$tagline'
            WHERE id=1";
    }

    mysqli_query($connect, $query);

    $success = true;
    $data = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM about WHERE id=1"));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About | Admin Panel</title>

    <?php
    include "links.php";
    ?>

    <?php include("sidebar.php"); ?>

    <style>
        /* ── PAGE SPECIFIC ── */
        .card {
            background: var(--dark);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 32px;
            max-width: 780px;
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 32px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border);
        }

        .card-header-icon {
            width: 44px;
            height: 44px;
            background: rgba(201, 169, 127, 0.12);
            border: 1px solid var(--border);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 1.1rem;
        }

        .card-header h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            color: var(--text);
        }

        .card-header p {
            font-size: 0.82rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        /* Form */
        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--primary);
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            background: var(--dark3);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 12px 16px;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.3s, background 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary);
            background: rgba(201, 169, 127, 0.04);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        textarea.form-control {
            min-height: 140px;
            resize: vertical;
            line-height: 1.7;
        }

        /* Image upload area */
        .upload-area {
            border: 2px dashed var(--border);
            border-radius: 12px;
            padding: 28px;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.3s, background 0.3s;
            position: relative;
        }

        .upload-area:hover {
            border-color: var(--primary);
            background: rgba(201, 169, 127, 0.04);
        }

        .upload-area input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }

        .upload-icon {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 10px;
        }

        .upload-area p {
            font-size: 0.88rem;
            color: var(--text-muted);
        }

        .upload-area span {
            color: var(--primary);
            font-weight: 600;
        }

        /* Current image preview */
        .current-img {
            display: flex;
            align-items: center;
            gap: 14px;
            background: var(--dark3);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 12px;
        }

        .current-img img {
            width: 52px;
            height: 52px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid var(--border);
        }

        .current-img div p {
            font-size: 0.82rem;
            color: var(--text-muted);
        }

        .current-img div span {
            font-size: 0.9rem;
            color: var(--text);
            font-weight: 500;
        }

        /* Buttons */
        .btn-row {
            display: flex;
            gap: 12px;
            margin-top: 8px;
        }

        .btn-save {
            padding: 12px 32px;
            background: var(--primary);
            color: var(--dark);
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.92rem;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-save:hover {
            background: var(--accent);
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(201, 169, 127, 0.25);
        }

        .btn-back {
            padding: 12px 24px;
            background: transparent;
            color: var(--text-muted);
            border: 1px solid var(--border);
            border-radius: 10px;
            font-weight: 500;
            font-size: 0.92rem;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        /* Success alert */
        .alert-success {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(100, 200, 120, 0.1);
            border: 1px solid rgba(100, 200, 120, 0.25);
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 24px;
            color: #7dd89a;
            font-size: 0.9rem;
            font-weight: 500;
        }
    </style>
</head>

<body>

    <!-- MAIN CONTENT -->
    <div class="main-content">

        <!-- TOP BAR -->
        <div class="topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle" onclick="openSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <span class="page-title">About Section</span>
            </div>
            <div class="topbar-right">
                <div class="admin-chip">
                    <div class="admin-avatar">A</div>
                    <span>Admin</span>
                </div>
            </div>
        </div>

        <!-- PAGE CONTENT -->
        <div class="page-wrap">

            <?php if (isset($success)): ?>
                <div class="alert-success">
                    <i class="fas fa-circle-check"></i>
                    About section updated successfully!
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <div class="card-header-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h2>Update About</h2>
                        <p>Edit your name, title, description and profile image</p>
                    </div>
                </div>

                <form method="POST" enctype="multipart/form-data">

                    <div class="form-group">
                        <label>Your Title / Role</label>
                        <input type="text" name="title" class="form-control"
                            placeholder="e.g. Frontend Developer"
                            value="<?php echo htmlspecialchars($data['title'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Heading 2</label>
                        <input type="text" name="head2" class="form-control"
                            placeholder="Add heading 2"
                            value="<?php echo htmlspecialchars($data['head2'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Brief Discription</label>
                        <input type="text" name="para1" class="form-control"
                            placeholder="Enter Brief Discription"
                            value="<?php echo htmlspecialchars($data['para1'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Short Discription</label>
                        <input type="text" name="para2" class="form-control"
                            placeholder="Enter Short  Discription"
                            value="<?php echo htmlspecialchars($data['para2'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Your's Tagline</label>
                        <input type="text" name="tagline" class="form-control"
                            placeholder="Enter Yours Tagline"
                            value="<?php echo htmlspecialchars($data['tagline'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Profile Image</label>

                        <?php if (!empty($data['image'])): ?>
                            <div class="current-img">
                                <img src="uploads/<?php echo $data['image']; ?>" alt="Current Image">
                                <div>
                                    <p>Current image</p>
                                    <span><?php echo $data['image']; ?></span>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="upload-area">
                            <input type="file" name="image" accept="image/*"
                                onchange="showFileName(this)">
                            <div class="upload-icon">
                                <i class="fas fa-cloud-arrow-up"></i>
                            </div>
                            <p id="upload-label">
                                <span>Click to upload</span> or drag & drop<br>
                                PNG, JPG, WEBP — max 5MB
                            </p>
                        </div>
                    </div>

                    <div class="btn-row">
                        <button type="submit" name="update" class="btn-save">
                            <i class="fas fa-floppy-disk"></i> Save Changes
                        </button>
                        <a href="dashboard.php" class="btn-back">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>

                </form>
            </div>

        </div><!-- /page-wrap -->
    </div><!-- /main-content -->

    <script>
        function showFileName(input) {
            if (input.files && input.files[0]) {
                document.getElementById('upload-label').innerHTML =
                    '<span>' + input.files[0].name + '</span> selected ✓';
            }
        }
    </script>

</body>

</html>