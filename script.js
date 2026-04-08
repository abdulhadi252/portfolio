function toggleNav() {
    const links = document.getElementById('navLinks');
    const icon = document.getElementById('toggle-icon');
    links.classList.toggle('open');
    icon.className = links.classList.contains('open') ? 'fas fa-times' : 'fas fa-bars';
}

function closeNav() {
    document.getElementById('navLinks').classList.remove('open');
    document.getElementById('toggle-icon').className = 'fas fa-bars';
}

const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) e.target.classList.add('visible');
    });
}


    , {
        threshold: 0.08
    });

// Navbar scroll: pill → sticky top
window.addEventListener('scroll', function () {
    const nav = document.querySelector('nav');
    if (window.scrollY > 80) {
        nav.classList.add('scrolled');
    } else {
        nav.classList.remove('scrolled');
    }
});
document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));
