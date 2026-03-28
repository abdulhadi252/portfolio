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

function validateForm() {
    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();
    const phone = document.getElementById("phone").value.trim();
    const feedback = document.getElementById("feedback").value.trim();

    if (!name || !email || !phone || !feedback) {
        alert("Please fill all required fields");
        return false;
    }

    if (!email.includes("@") || !email.includes(".")) {
        alert("Please enter a valid email address");
        return false;
    }

    if (phone.length < 11 || isNaN(phone)) {
        alert("Please enter a valid phone number (min 11 digits)");
        return false;
    }

    alert("Your message has been sent successfully! ✨");
    return true;
}

