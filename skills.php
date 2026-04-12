<?php
include("connect.php");

// ADD
if(isset($_POST['add_skill'])){
    $skills = $_POST['skills'];
    $skillsArray = explode(",", $skills);
    foreach($skillsArray as $skill){
        $skill = trim($skill);
        if(!empty($skill)){
            mysqli_query($connect, "INSERT INTO skills(skill_name) VALUES('$skill')");
        }
    }
    header("Location: skills.php");
    exit();
}

// UPDATE
if(isset($_POST['update_skill'])){
    $sid  = $_POST['skill_id'];
    $name = trim($_POST['skill_name']);
    if(!empty($name)){
        mysqli_query($connect, "UPDATE skills SET skill_name='$name' WHERE id=$sid");
    }
    header("Location: skills.php");
    exit();
}

// DELETE
if(isset($_GET['delete'])){
    $did = (int)$_GET['delete'];
    mysqli_query($connect, "DELETE FROM skills WHERE id=$did");
    header("Location: skills.php");
    exit();
}

$result = mysqli_query($connect, "SELECT * FROM skills ORDER BY id ASC");
$count  = mysqli_num_rows($result);
$skills_list = [];
while($r = mysqli_fetch_assoc($result)) $skills_list[] = $r;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skills | Admin Panel</title>
    <?php include("sidebar.php"); ?>
    <style>
        .skills-wrap { max-width: 740px; }

        .card {
            background: var(--dark); border: 1px solid var(--border);
            border-radius: 16px; padding: 28px 32px; margin-bottom: 24px;
        }

        .card-header {
            display:flex; align-items:center; gap:14px;
            margin-bottom:22px; padding-bottom:16px; border-bottom:1px solid var(--border);
        }
        .card-header-icon {
            width:44px; height:44px; background:rgba(201,169,127,0.12);
            border:1px solid var(--border); border-radius:12px;
            display:flex; align-items:center; justify-content:center;
            color:var(--primary); font-size:1.1rem;
        }
        .card-header h2 { font-family:'Playfair Display',serif; font-size:1.3rem; color:var(--text); }
        .card-header p  { font-size:0.82rem; color:var(--text-muted); margin-top:2px; }

        /* Add form */
        .add-row { display:flex; gap:12px; }

        .input-wrap { position:relative; flex:1; }
        .input-wrap i.ico {
            position:absolute; left:14px; top:50%;
            transform:translateY(-50%);
            color:var(--text-muted); font-size:0.88rem; pointer-events:none;
        }
        .form-control {
            width:100%; background:var(--dark3); border:1px solid var(--border);
            border-radius:10px; padding:12px 16px 12px 42px;
            color:var(--text); font-family:'DM Sans',sans-serif;
            font-size:0.92rem; outline:none;
            transition:border-color 0.3s, background 0.3s;
        }
        .form-control:focus { border-color:var(--primary); background:rgba(201,169,127,0.04); }
        .form-control::placeholder { color:var(--text-muted); }

        .hint { font-size:0.78rem; color:var(--text-muted); margin-top:7px; }
        .hint i { color:var(--primary); margin-right:4px; }

        .btn-add {
            padding:12px 22px; background:var(--primary); color:var(--dark);
            border:none; border-radius:10px; font-weight:700; font-size:0.88rem;
            cursor:pointer; font-family:'DM Sans',sans-serif; white-space:nowrap;
            display:flex; align-items:center; gap:8px; transition:all 0.3s;
        }
        .btn-add:hover { background:var(--accent); transform:translateY(-1px); }

        /* Skills list */
        .list-header {
            display:flex; align-items:center; justify-content:space-between;
            margin-bottom:20px; padding-bottom:14px; border-bottom:1px solid var(--border);
        }
        .list-header h3 { font-family:'Playfair Display',serif; font-size:1.15rem; color:var(--text); }
        .count-badge {
            background:rgba(201,169,127,0.12); border:1px solid var(--border);
            color:var(--primary); font-size:0.75rem; font-weight:700;
            padding:4px 12px; border-radius:20px;
        }

        /* Skill rows */
        .skill-row {
            display:flex; align-items:center; gap:10px;
            padding:10px 0; border-bottom:1px solid rgba(201,169,127,0.07);
            opacity:0; transform:translateX(-10px);
            animation: rowIn 0.3s ease forwards;
        }
        .skill-row:last-child { border-bottom:none; }

        .skill-row:nth-child(1){animation-delay:0.02s} .skill-row:nth-child(2){animation-delay:0.05s}
        .skill-row:nth-child(3){animation-delay:0.08s} .skill-row:nth-child(4){animation-delay:0.11s}
        .skill-row:nth-child(5){animation-delay:0.14s} .skill-row:nth-child(6){animation-delay:0.17s}
        .skill-row:nth-child(7){animation-delay:0.20s} .skill-row:nth-child(8){animation-delay:0.23s}

        @keyframes rowIn { to { opacity:1; transform:translateX(0); } }

        .skill-num {
            font-size:0.72rem; color:var(--text-muted);
            width:24px; text-align:right; flex-shrink:0;
        }

        /* View mode tag */
        .skill-tag-view {
            flex:1; display:flex; align-items:center;
        }
        .skill-tag {
            background:rgba(201,169,127,0.1); border:1px solid var(--border);
            color:var(--primary); font-size:0.85rem; font-weight:600;
            padding:6px 16px; border-radius:20px;
            transition:background 0.2s;
        }

        /* Edit mode */
        .skill-edit-form {
            flex:1; display:none; align-items:center; gap:8px;
        }
        .skill-edit-input {
            flex:1; background:var(--dark3); border:1px solid var(--primary);
            border-radius:8px; padding:7px 12px; color:var(--text);
            font-family:'DM Sans',sans-serif; font-size:0.88rem; outline:none;
        }
        .btn-save-edit {
            padding:7px 14px; background:var(--primary); color:var(--dark);
            border:none; border-radius:8px; font-size:0.8rem; font-weight:700;
            cursor:pointer; font-family:'DM Sans',sans-serif; transition:all 0.2s;
        }
        .btn-save-edit:hover { background:var(--accent); }
        .btn-cancel-edit {
            padding:7px 10px; background:transparent; color:var(--text-muted);
            border:1px solid var(--border); border-radius:8px;
            font-size:0.8rem; cursor:pointer; transition:all 0.2s;
        }
        .btn-cancel-edit:hover { border-color:var(--primary); color:var(--primary); }

        /* Action buttons */
        .skill-actions { display:flex; gap:6px; flex-shrink:0; }

        .btn-edit {
            width:32px; height:32px; border-radius:8px;
            background:rgba(201,169,127,0.1); border:1px solid var(--border);
            color:var(--primary); cursor:pointer; font-size:0.78rem;
            display:flex; align-items:center; justify-content:center;
            transition:all 0.2s;
        }
        .btn-edit:hover { background:var(--primary); color:var(--dark); }

        .btn-del {
            width:32px; height:32px; border-radius:8px;
            background:rgba(220,80,80,0.08); border:1px solid rgba(220,80,80,0.18);
            color:#e07070; cursor:pointer; font-size:0.78rem;
            display:flex; align-items:center; justify-content:center;
            text-decoration:none; transition:all 0.2s;
        }
        .btn-del:hover { background:rgba(220,80,80,0.2); border-color:rgba(220,80,80,0.4); }

        .empty-msg {
            text-align:center; padding:36px;
            color:var(--text-muted); font-size:0.9rem;
        }
        .empty-msg i { display:block; font-size:2rem; color:var(--border); margin-bottom:10px; }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle" onclick="openSidebar()"><i class="fas fa-bars"></i></button>
                <span class="page-title">Skills</span>
            </div>
            <div class="topbar-right">
                <div class="admin-chip"><div class="admin-avatar">A</div><span>Admin</span></div>
            </div>
        </div>

        <div class="page-wrap">
            <div class="skills-wrap">

                <!-- ADD -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-icon"><i class="fas fa-plus"></i></div>
                        <div><h2>Add Skills</h2><p>Separate multiple skills with commas</p></div>
                    </div>
                    <form method="POST">
                        <div class="add-row">
                            <div class="input-wrap">
                                <i class="fas fa-code ico"></i>
                                <input type="text" name="skills" class="form-control"
                                    placeholder="HTML, CSS, JavaScript, React.js" autocomplete="off">
                            </div>
                            <button type="submit" name="add_skill" class="btn-add">
                                <i class="fas fa-plus"></i> Add
                            </button>
                        </div>
                        <p class="hint"><i class="fas fa-info-circle"></i> Use comma to add multiple at once</p>
                    </form>
                </div>

                <!-- LIST -->
                <div class="card">
                    <div class="list-header">
                        <h3>All Skills</h3>
                        <span class="count-badge"><?php echo $count; ?> total</span>
                    </div>

                    <?php if($count > 0): ?>
                    <?php foreach($skills_list as $i => $sk): ?>
                    <div class="skill-row" id="row-<?php echo $sk['id']; ?>">
                        <span class="skill-num"><?php echo $i+1; ?></span>

                        <!-- View mode -->
                        <div class="skill-tag-view" id="view-<?php echo $sk['id']; ?>">
                            <span class="skill-tag"><?php echo htmlspecialchars($sk['skill_name']); ?></span>
                        </div>

                        <!-- Edit mode (hidden by default) -->
                        <form method="POST" class="skill-edit-form" id="edit-<?php echo $sk['id']; ?>">
                            <input type="hidden" name="skill_id" value="<?php echo $sk['id']; ?>">
                            <input type="text" name="skill_name" class="skill-edit-input"
                                value="<?php echo htmlspecialchars($sk['skill_name']); ?>">
                            <button type="submit" name="update_skill" class="btn-save-edit">
                                <i class="fas fa-check"></i> Save
                            </button>
                            <button type="button" class="btn-cancel-edit"
                                onclick="cancelEdit(<?php echo $sk['id']; ?>)">
                                <i class="fas fa-xmark"></i>
                            </button>
                        </form>

                        <div class="skill-actions">
                            <button class="btn-edit" onclick="startEdit(<?php echo $sk['id']; ?>)" title="Edit">
                                <i class="fas fa-pen"></i>
                            </button>
                            <a href="?delete=<?php echo $sk['id']; ?>"
                               onclick="return confirm('Remove \'<?php echo htmlspecialchars($sk['skill_name']); ?>\'?')"
                               class="btn-del" title="Delete">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <div class="empty-msg">
                        <i class="fas fa-code"></i>
                        No skills yet. Add your first skill above!
                    </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

    <script>
        function startEdit(id) {
            document.getElementById('view-' + id).style.display = 'none';
            var editForm = document.getElementById('edit-' + id);
            editForm.style.display = 'flex';
            editForm.querySelector('.skill-edit-input').focus();
        }

        function cancelEdit(id) {
            document.getElementById('view-' + id).style.display = 'flex';
            document.getElementById('edit-' + id).style.display = 'none';
        }
    </script>
</body>
</html>