<?php
include("connect.php");

if(isset($_GET['approve'])){
    $id = $_GET['approve'];
    mysqli_query($connect, "UPDATE messages SET status='approved' WHERE id=$id");
    header("Location: messages.php");
    exit();
}

if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($connect, "DELETE FROM messages WHERE id=$id");
    header("Location: messages.php");
    exit();
}

$result = mysqli_query($connect, "SELECT * FROM messages ORDER BY id DESC");
$total  = mysqli_num_rows($result);
$pending = mysqli_num_rows(mysqli_query($connect, "SELECT id FROM messages WHERE status='pending'"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages | Admin Panel</title>
    <?php include("sidebar.php"); ?>
    <style>
        /* Summary row */
        .summary-row {
            display:grid; grid-template-columns:repeat(3,1fr); gap:18px;
            margin-bottom:32px;
        }
        .summary-card {
            background:var(--dark); border:1px solid var(--border);
            border-radius:14px; padding:22px 24px;
            display:flex; align-items:center; gap:16px;
            transition:var(--transition);
        }
        .summary-card:hover { border-color:var(--primary); transform:translateY(-3px); }
        .summary-icon {
            width:44px; height:44px; border-radius:12px;
            background:rgba(201,169,127,0.12); border:1px solid var(--border);
            display:flex; align-items:center; justify-content:center;
            color:var(--primary); font-size:1.1rem; flex-shrink:0;
        }
        .summary-card h3 { font-size:1.8rem; color:var(--primary); line-height:1; margin-bottom:3px; }
        .summary-card p  { font-size:0.82rem; color:var(--text-muted); }

        /* Messages list */
        .msg-card {
            background:var(--dark); border:1px solid var(--border);
            border-radius:14px; padding:22px 24px; margin-bottom:16px;
            display:grid; grid-template-columns:1fr auto;
            gap:16px; align-items:start;
            opacity:0; transform:translateY(16px);
            animation:msgIn 0.4s ease forwards;
            transition:border-color 0.3s;
        }

        .msg-card:hover { border-color:var(--primary); }

        .msg-card:nth-child(1){animation-delay:0.04s}
        .msg-card:nth-child(2){animation-delay:0.09s}
        .msg-card:nth-child(3){animation-delay:0.14s}
        .msg-card:nth-child(4){animation-delay:0.19s}
        .msg-card:nth-child(5){animation-delay:0.24s}

        @keyframes msgIn { to { opacity:1; transform:translateY(0); } }

        .msg-meta {
            display:flex; flex-wrap:wrap; gap:18px;
            margin-bottom:10px; align-items:center;
        }

        .msg-name { font-family:'Playfair Display',serif; font-size:1.05rem; color:var(--text); }

        .msg-badge {
            font-size:0.72rem; font-weight:700; padding:3px 10px;
            border-radius:20px; text-transform:uppercase; letter-spacing:0.5px;
        }
        .badge-pending  { background:rgba(220,170,60,0.15);  color:#e0a830; border:1px solid rgba(220,170,60,0.25); }
        .badge-approved { background:rgba(100,200,120,0.12); color:#7dd89a; border:1px solid rgba(100,200,120,0.25); }

        .msg-info { display:flex; flex-wrap:wrap; gap:16px; margin-bottom:10px; }
        .msg-info span { font-size:0.84rem; color:var(--text-muted); display:flex; align-items:center; gap:6px; }
        .msg-info span i { color:var(--primary); font-size:0.78rem; }

        .msg-text {
            font-size:0.9rem; color:rgba(240,236,228,0.8);
            line-height:1.7; padding:14px 16px;
            background:var(--dark3); border-radius:8px;
            border-left:3px solid var(--border);
        }

        /* Actions */
        .msg-actions { display:flex; flex-direction:column; gap:8px; min-width:100px; }

        .btn-approve {
            padding:8px 14px; background:rgba(100,200,120,0.12);
            color:#7dd89a; border:1px solid rgba(100,200,120,0.25);
            border-radius:8px; text-decoration:none; font-size:0.82rem;
            font-weight:600; text-align:center; transition:all 0.3s;
            display:flex; align-items:center; justify-content:center; gap:6px;
        }
        .btn-approve:hover { background:rgba(100,200,120,0.22); }

        .btn-delete {
            padding:8px 14px; background:rgba(220,80,80,0.1);
            color:#e07070; border:1px solid rgba(220,80,80,0.2);
            border-radius:8px; text-decoration:none; font-size:0.82rem;
            font-weight:600; text-align:center; transition:all 0.3s;
            display:flex; align-items:center; justify-content:center; gap:6px;
        }
        .btn-delete:hover { background:rgba(220,80,80,0.2); }

        /* Empty */
        .empty-state {
            text-align:center; padding:70px 20px;
            background:var(--dark); border:1px solid var(--border); border-radius:16px;
        }
        .empty-state i { font-size:2.8rem; color:var(--border); margin-bottom:14px; display:block; }
        .empty-state h3 { font-family:'Playfair Display',serif; color:var(--text-muted); margin-bottom:8px; }
        .empty-state p  { font-size:0.88rem; color:var(--text-muted); }

        /* Page header */
        .page-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:28px; }
        .page-header h1 { font-family:'Playfair Display',serif; font-size:1.6rem; color:var(--text); }
        .page-header p  { font-size:0.85rem; color:var(--text-muted); margin-top:3px; }

        @media(max-width:640px){
            .summary-row { grid-template-columns:1fr; }
            .msg-card { grid-template-columns:1fr; }
            .msg-actions { flex-direction:row; }
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle" onclick="openSidebar()"><i class="fas fa-bars"></i></button>
                <span class="page-title">Messages</span>
            </div>
            <div class="topbar-right">
                <div class="admin-chip"><div class="admin-avatar">A</div><span>Admin</span></div>
            </div>
        </div>

        <div class="page-wrap">

            <div class="page-header">
                <div><h1>Contact Messages</h1><p>Manage messages from your portfolio visitors</p></div>
            </div>

            <!-- Summary -->
            <div class="summary-row">
                <div class="summary-card">
                    <div class="summary-icon"><i class="fas fa-envelope"></i></div>
                    <div><h3><?php echo $total; ?></h3><p>Total Messages</p></div>
                </div>
                <div class="summary-card">
                    <div class="summary-icon"><i class="fas fa-clock"></i></div>
                    <div><h3><?php echo $pending; ?></h3><p>Pending</p></div>
                </div>
                <div class="summary-card">
                    <div class="summary-icon"><i class="fas fa-circle-check"></i></div>
                    <div><h3><?php echo $total - $pending; ?></h3><p>Approved</p></div>
                </div>
            </div>

            <!-- Messages -->
            <?php if($total > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <div class="msg-card">
                    <div>
                        <div class="msg-meta">
                            <span class="msg-name"><?php echo htmlspecialchars($row['name']); ?></span>
                            <span class="msg-badge badge-<?php echo $row['status'] == 'pending' ? 'pending' : 'approved'; ?>">
                                <?php echo $row['status']; ?>
                            </span>
                        </div>
                        <div class="msg-info">
                            <span><i class="fas fa-envelope"></i><?php echo htmlspecialchars($row['email']); ?></span>
                            <span><i class="fas fa-phone"></i><?php echo htmlspecialchars($row['phone']); ?></span>
                            <span><i class="fas fa-hashtag"></i>ID: <?php echo $row['id']; ?></span>
                        </div>
                        <div class="msg-text"><?php echo htmlspecialchars($row['message']); ?></div>
                    </div>
                    <div class="msg-actions">
                        <?php if($row['status'] == 'pending'): ?>
                        <a href="?approve=<?php echo $row['id']; ?>" class="btn-approve">
                            <i class="fas fa-check"></i> Approve
                        </a>
                        <?php endif; ?>
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn-delete"
                           onclick="return confirm('Delete this message?')">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-envelope-open"></i>
                    <h3>No Messages Yet</h3>
                    <p>Messages from your contact form will appear here.</p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</body>
</html>