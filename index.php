<?php
include("connect.php");

$hero     = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM hero WHERE id=1"));
$about    = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM about WHERE id=1"));
$skills   = mysqli_query($connect, "SELECT * FROM skills");
$stats    = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM stats WHERE id=1"));
$projects = mysqli_query($connect, "SELECT * FROM projects");

if (isset($_POST['send'])) {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $phone    = $_POST['phone'];
    $feedback = $_POST['feedback'];
    mysqli_query($connect, "INSERT INTO messages(name,email,phone,message) VALUES('$name','$email','$phone','$feedback')");
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abdul Hadi | Frontend Developer</title>
    <?php include "links.php"; ?>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- ══ CURSOR ══ -->
<div class="cursor-dot" id="cursorDot"></div>
<div class="cursor-ring" id="cursorRing"></div>

<!-- ══ NAVBAR ══ -->
<nav id="mainNav">
    <div class="nav-inner">
        <div class="nav-logo">
            <img src="logo.png" alt="Abdul Hadi" style="height:48px;width:auto;display:block;">
        </div>
        <button class="nav-toggle" onclick="toggleNav()">
            <i class="fas fa-bars" id="toggle-icon"></i>
        </button>
        <ul class="nav-links" id="navLinks">
            <li><a href="#home"     onclick="closeNav()">Home</a></li>
            <li><a href="#about"    onclick="closeNav()">About</a></li>
            <li><a href="#projects" onclick="closeNav()">Projects</a></li>
            <li><a href="#contact"  onclick="closeNav()">Contact</a></li>
        </ul>
    </div>
</nav>

<!-- ══ HERO ══ -->
<section class="hero" id="home">
    <div class="hero-bg"></div>
    <div class="hero-overlay"></div>
    <div class="hero-inner">

        <!-- LEFT: floating cards -->
        <div class="hero-cards">
            <div class="hero-float-card">
                <div class="float-dot"></div>
                <div class="float-card-icon"><i class="fas fa-code"></i></div>
                <h4>Clean Code</h4>
                <p>Structured, readable &amp; maintainable frontend development</p>
            </div>
            <div class="hero-float-card">
                <div class="float-dot"></div>
                <div class="float-card-icon"><i class="fas fa-mobile-screen"></i></div>
                <h4>Responsive</h4>
                <p>Pixel-perfect on every screen size &amp; device</p>
            </div>
        </div>

        <!-- RIGHT: content -->
        <div class="hero-content">
            <h1><?php echo $hero['title']; ?></h1>
            <div class="hero-divider">
                <span></span>
                <em>Based in Karachi</em>
                <span></span>
            </div>
            <h2><?php echo $hero['subtitle']; ?></h2>
            <p><?php echo $hero['description']; ?></p>
            <div class="hero-btns">
                <a href="<?php echo $hero['button_link']; ?>" class="btn">
                    <?php echo $hero['button_text']; ?>
                </a>
                <a href="<?php echo $hero['button2_link']; ?>"
                    <?php if(strpos($hero['button2_link'], '.pdf') !== false) echo 'download'; ?>
                    class="btn btn-ghost">
                    <?php echo $hero['button2_text']; ?>
                </a>
            </div>
        </div>

    </div>

    <!-- Scroll indicator -->
    <div class="hero-scroll">
        <span>Scroll</span>
        <div class="scroll-line"></div>
    </div>
</section>

<!-- ══ ABOUT ══ -->
<section id="about" class="animate-on-scroll">
    <div class="container">
        <div class="section-title">
            <div class="section-label"><?php echo $about['title']; ?></div>
            <h2><?php echo $about['head2']; ?></h2>
        </div>
        <div class="about-grid">
            <div class="about-img-col">
                <div class="about-img-wrap">
                    <div class="about-img-inner">
                        <img src="uploads/<?php echo $about['image']; ?>" alt="Abdul Hadi">
                    </div>
                    <div class="about-badge">
                        <div class="badge-num"><?php echo $stats['experience']; ?>+</div>
                        <div class="badge-label">Years Exp.</div>
                    </div>
                </div>
                <div class="about-social">
                    <a href="https://linkedin.com/in/abdulhadi" target="_blank" title="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="https://github.com/abdulhadi252" target="_blank" title="GitHub">
                        <i class="fab fa-github"></i>
                    </a>
                    <a href="mailto:abdulhadi@gmail.com" title="Email">
                        <i class="fas fa-envelope"></i>
                    </a>
                </div>
            </div>
            <div class="about-text">
                <p><?php echo $about['para1']; ?></p>
                <p><?php echo $about['para2']; ?></p>
                <div class="tagline">
                    <p><?php echo $about['tagline']; ?></p>
                </div>
                <div class="skill-names">
                    <?php
                    $skills = mysqli_query($connect, "SELECT * FROM skills");
                    while ($row = mysqli_fetch_assoc($skills)) { ?>
                        <span class="skill-name-tag"><?php echo $row['skill_name']; ?></span>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══ STATS ══ -->
<section class="stats-section animate-on-scroll">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item">
                <h3><?php echo $stats['projects_completed']; ?>+</h3>
                <p>Projects Completed</p>
            </div>
            <div class="stat-item">
                <h3><?php echo $stats['small_projects']; ?>+</h3>
                <p>Small Projects</p>
            </div>
            <div class="stat-item">
                <h3><?php echo $stats['experience']; ?>+</h3>
                <p>Years Experience</p>
            </div>
            <div class="stat-item">
                <h3><?php echo $stats['seminars']; ?>+</h3>
                <p>Seminars Attended</p>
            </div>
        </div>
    </div>
</section>

<!-- ══ PROJECTS ══ -->
<section id="projects" class="animate-on-scroll">
    <div class="container">
        <div class="section-title">
            <div class="section-label">Portfolio</div>
            <h2>My <em>Work</em></h2>
        </div>
        <div class="projects-grid">
            <?php while ($row = mysqli_fetch_assoc($projects)) { ?>
            <div class="project-card">
                <div class="project-img">
                    <img src="uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>">
                    <div class="project-overlay">
                        <a href="<?php echo $row['live_link']; ?>" target="_blank">
                            <i class="fas fa-external-link-alt"></i> Live
                        </a>
                        <a href="<?php echo $row['github_link']; ?>" target="_blank">
                            <i class="fab fa-github"></i> GitHub
                        </a>
                    </div>
                </div>
                <div class="project-info">
                    <h3><?php echo $row['title']; ?></h3>
                    <p><?php echo $row['description']; ?></p>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>

<!-- ══ CONTACT ══ -->
<section id="contact" class="animate-on-scroll">
    <div class="container">
        <div class="section-title">
            <div class="section-label">Contact</div>
            <h2>Get In <em>Touch</em></h2>
        </div>
        <div class="contact-grid">
            <div class="contact-info">
                <h3>Let's <span>Work</span><br>Together</h3>
                <p class="contact-sub">Have a project in mind or just want to say hello? I'd love to hear from you.</p>
                <div class="contact-detail">
                    <i class="fas fa-envelope"></i>
                    <div><h4>Email</h4><p>abdulhadi@gmail.com</p></div>
                </div>
                <div class="contact-detail">
                    <i class="fas fa-phone"></i>
                    <div><h4>Phone</h4><p>03172045430 / 03132194854</p></div>
                </div>
                <div class="contact-detail">
                    <i class="fas fa-map-marker-alt"></i>
                    <div><h4>Location</h4><p>Karachi, Pakistan</p></div>
                </div>
            </div>
            <div class="contact-form">
                <div class="contact-form-wrap">
                    <form method="POST">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Your name" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" class="form-control" name="email" id="email" placeholder="your@email.com" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" class="form-control" name="phone" id="phone" placeholder="+92 xxx xxxxxxx" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="feedback">Message</label>
                            <textarea class="form-control" name="feedback" id="feedback" placeholder="Tell me about your project..." autocomplete="off"></textarea>
                        </div>
                        <div class="form-btns">
                            <button type="submit" name="send" id="formsend"><span>Send Message</span></button>
                            <button type="reset" id="formreset">Clear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══ ENDING ══ -->
<div class="site-ending">
    <div class="ending-line"></div>
    <div class="ending-content">
        <p class="ending-tagline">Built with passion &amp; clean code</p>
        <p class="ending-copy">&copy; 2026 Abdul Hadi &mdash; Frontend Developer &bull; Karachi, Pakistan</p>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>