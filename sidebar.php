<?php
// Get current page for active link highlight
$current = basename($_SERVER['PHP_SELF']);
?>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
    :root {
        --primary:   #c9a97f;
        --accent:    #d4af37;
        --dark:      #0a0a0a;
        --dark2:     #111111;
        --dark3:     #1a1a1a;
        --dark4:     #222222;
        --border:    rgba(201,169,127,0.15);
        --text:      #f0ece4;
        --text-muted:#9e9088;
        --sidebar-w: 260px;
    }

    * { margin:0; padding:0; box-sizing:border-box; }

    body {
        font-family: 'DM Sans', sans-serif;
        background: var(--dark2);
        color: var(--text);
        display: flex;
        min-height: 100vh;
    }

    /* ── SIDEBAR ── */
    .sidebar {
        width: var(--sidebar-w);
        min-height: 100vh;
        background: var(--dark);
        border-right: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        position: fixed;
        top: 0; left: 0;
        z-index: 100;
        transition: transform 0.3s ease;
    }

    .sidebar-brand {
        padding: 28px 24px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .brand-icon {
        width: 40px; height: 40px;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        font-size: 1rem;
        color: var(--dark);
        flex-shrink: 0;
    }

    .brand-text h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1rem;
        color: var(--text);
        line-height: 1.2;
    }

    .brand-text span {
        font-size: 0.72rem;
        color: var(--primary);
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .sidebar-nav {
        padding: 20px 12px;
        flex: 1;
    }

    .nav-label {
        font-size: 0.65rem;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--text-muted);
        padding: 0 12px;
        margin: 16px 0 8px;
    }

    .nav-label:first-child { margin-top: 0; }

    .sidebar-nav a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 14px;
        border-radius: 10px;
        color: var(--text-muted);
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.25s ease;
        margin-bottom: 3px;
        position: relative;
    }

    .sidebar-nav a i {
        width: 18px;
        text-align: center;
        font-size: 0.95rem;
        transition: color 0.25s;
    }

    .sidebar-nav a:hover {
        background: rgba(201,169,127,0.08);
        color: var(--text);
    }

    .sidebar-nav a:hover i { color: var(--primary); }

    .sidebar-nav a.active {
        background: rgba(201,169,127,0.12);
        color: var(--text);
        border-left: 3px solid var(--primary);
        padding-left: 11px;
    }

    .sidebar-nav a.active i { color: var(--primary); }

    .sidebar-nav a .badge {
        margin-left: auto;
        background: var(--primary);
        color: var(--dark);
        font-size: 0.65rem;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 20px;
    }

    /* Sidebar bottom logout */
    .sidebar-footer {
        padding: 16px 12px 24px;
        border-top: 1px solid var(--border);
    }

    .sidebar-footer a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 14px;
        border-radius: 10px;
        color: #e07070;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.25s ease;
    }

    .sidebar-footer a:hover {
        background: rgba(220,80,80,0.1);
    }

    /* ── MAIN CONTENT WRAPPER ── */
    .main-content {
        margin-left: var(--sidebar-w);
        flex: 1;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* ── TOP BAR ── */
    .topbar {
        background: var(--dark);
        border-bottom: 1px solid var(--border);
        padding: 16px 32px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 50;
    }

    .topbar-left {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .sidebar-toggle {
        display: none;
        background: none;
        border: none;
        color: var(--text);
        font-size: 1.2rem;
        cursor: pointer;
        padding: 4px;
    }

    .page-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.3rem;
        color: var(--text);
    }

    .topbar-right {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .admin-chip {
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--dark3);
        border: 1px solid var(--border);
        padding: 7px 14px 7px 8px;
        border-radius: 30px;
    }

    .admin-avatar {
        width: 28px; height: 28px;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--dark);
    }

    .admin-chip span {
        font-size: 0.85rem;
        color: var(--text);
        font-weight: 500;
    }

    /* ── PAGE WRAPPER (use inside each page) ── */
    .page-wrap {
        padding: 36px 40px;
        flex: 1;
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 900px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.open {
            transform: translateX(0);
        }

        .main-content {
            margin-left: 0;
        }

        .sidebar-toggle {
            display: block;
        }

        .page-wrap {
            padding: 24px 20px;
        }
    }

    /* Overlay for mobile */
    .sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.6);
        z-index: 99;
    }

    .sidebar-overlay.open { display: block; }
</style>

<!-- Sidebar overlay (mobile) -->
<div class="sidebar-overlay" id="overlay" onclick="closeSidebar()"></div>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">AH</div>
        <div class="brand-text">
            <h3>Abdul Hadi</h3>
            <span>Admin Panel</span>
        </div>
    </div>

    <nav class="sidebar-nav">

        <div class="nav-label">Main</div>
        <a href="dashboard.php" class="<?= $current=='dashboard.php' ? 'active' : '' ?>">
            <i class="fas fa-gauge-high"></i> Dashboard
        </a>

        <div class="nav-label">Content</div>
        <a href="hero.php" class="<?= $current=='hero.php' ? 'active' : '' ?>">
            <i class="fas fa-user"></i> Hero
        </a>
        <a href="about.php" class="<?= $current=='about.php' ? 'active' : '' ?>">
            <i class="fas fa-user"></i> About
        </a>
        <a href="skills.php" class="<?= $current=='skills.php' ? 'active' : '' ?>">
            <i class="fas fa-code"></i> Skills
        </a>
        <a href="stats.php" class="<?= $current=='stats.php' ? 'active' : '' ?>">
            <i class="fas fa-briefcase"></i> Stats
        </a>
        <a href="contact.php" class="<?= $current=='contact.php' ? 'active' : '' ?>">
            <i class="fas fa-user"></i> Contact Info
        </a>
        <a href="projects.php" class="<?= $current=='projects.php' ? 'active' : '' ?>">
            <i class="fas fa-gem"></i> Projects
        </a>
        <a href="messages.php" class="<?= $current=='messages.php' ? 'active' : '' ?>">
            <i class="fas fa-envelope"></i> Messages
            <span class="badge">New</span>
        </a>

        <div class="nav-label">Account</div>
        <a href="profile.php" class="<?= $current=='profile.php' ? 'active' : '' ?>">
            <i class="fas fa-circle-user"></i> Profile
        </a>

    </nav>

    <div class="sidebar-footer">
        <a href="logout.php">
            <i class="fas fa-right-from-bracket"></i> Logout
        </a>
    </div>
</div>

<script>
    function openSidebar() {
        document.getElementById('sidebar').classList.add('open');
        document.getElementById('overlay').classList.add('open');
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('overlay').classList.remove('open');
    }
</script>