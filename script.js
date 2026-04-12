/* ══════════════════════════════════
   SCRIPT.JS — Premium Portfolio JS
══════════════════════════════════ */

/* ── Custom Cursor ── */
(function () {
    var dot = document.querySelector('.cursor-dot');
    var ring = document.querySelector('.cursor-ring');
    if (!dot || !ring) return;

    var mx = 0, my = 0, rx = 0, ry = 0;

    document.addEventListener('mousemove', function (e) {
        mx = e.clientX; my = e.clientY;
        dot.style.transform = 'translate(' + (mx - 4) + 'px, ' + (my - 4) + 'px)';
    });

    function animRing() {
        rx += (mx - rx - 18) * 0.14;
        ry += (my - ry - 18) * 0.14;
        ring.style.transform = 'translate(' + rx + 'px, ' + ry + 'px)';
        requestAnimationFrame(animRing);
    }
    animRing();

    document.querySelectorAll('a, button, .project-card, .skill-name-tag, .stat-item').forEach(function (el) {
        el.addEventListener('mouseenter', function () { ring.classList.add('hover'); });
        el.addEventListener('mouseleave', function () { ring.classList.remove('hover'); });
    });
})();

/* ── Navbar Toggle ── */
function toggleNav() {
    var links = document.getElementById('navLinks');
    var icon = document.getElementById('toggle-icon');
    links.classList.toggle('open');
    icon.className = links.classList.contains('open') ? 'fas fa-times' : 'fas fa-bars';
}

function closeNav() {
    document.getElementById('navLinks').classList.remove('open');
    document.getElementById('toggle-icon').className = 'fas fa-bars';
}

/* ── Navbar scroll: pill → sticky ── */
window.addEventListener('scroll', function () {
    var nav = document.getElementById('mainNav');
    if (nav) nav.classList.toggle('scrolled', window.scrollY > 80);
});

/* ── All after DOM ready ── */
document.addEventListener('DOMContentLoaded', function () {

    /* ─ Active nav highlight ─ */
    var allSections = document.querySelectorAll('section[id]');
    var allNavLinks = document.querySelectorAll('.nav-links a');

    var navObs = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
            if (e.isIntersecting) {
                allNavLinks.forEach(function (a) {
                    a.classList.toggle('nav-active', a.getAttribute('href') === '#' + e.target.id);
                });
            }
        });
    }, { threshold: 0.35 });

    allSections.forEach(function (s) { navObs.observe(s); });

    /* ─ Section animate-on-scroll ─ */
    var secObs = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
            if (e.isIntersecting) e.target.classList.add('visible');
        });
    }, { threshold: 0.05 });

    document.querySelectorAll('.animate-on-scroll').forEach(function (el) {
        secObs.observe(el);
    });

    /* ─ Stats stagger ─ */
    var statsEl = document.querySelector('.stats-section');
    if (statsEl) {
        var statsObs = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    e.target.querySelectorAll('.stat-item').forEach(function (item, i) {
                        setTimeout(function () { item.classList.add('visible'); }, i * 130);
                    });
                    statsObs.unobserve(e.target);
                }
            });
        }, { threshold: 0.1 });
        statsObs.observe(statsEl);
    }

    /* ─ Stats counter animation ─ */
    function animateCounter(el, target, duration) {
        var start = 0;
        var step = target / (duration / 16);
        var timer = setInterval(function () {
            start += step;
            if (start >= target) { start = target; clearInterval(timer); }
            el.textContent = Math.floor(start) + '+';
        }, 16);
    }

    var counterDone = false;
    var counterObs = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
            if (e.isIntersecting && !counterDone) {
                counterDone = true;
                document.querySelectorAll('.stat-item h3').forEach(function (el) {
                    var val = parseInt(el.textContent);
                    if (!isNaN(val)) animateCounter(el, val, 1200);
                });
            }
        });
    }, { threshold: 0.3 });
    if (statsEl) counterObs.observe(statsEl);

    /* ─ Project cards ─ */
    var cards = document.querySelectorAll('.project-card');
    if (cards.length > 0) {
        /* Add number badges */
        cards.forEach(function (card, i) {
            var num = document.createElement('div');
            num.className = 'project-number';
            num.textContent = '0' + (i + 1);
            var imgWrap = card.querySelector('.project-img');
            if (imgWrap) imgWrap.appendChild(num);
        });

        /* Wrap overlay links text */
        cards.forEach(function (card) {
            var links = card.querySelectorAll('.project-overlay a');
            links.forEach(function (a) {
                var icon = a.querySelector('i');
                if (icon) {
                    var iconClass = icon.className;
                    icon.remove();
                    var isGithub = iconClass.indexOf('github') !== -1;
                    var newIcon = document.createElement('i');
                    newIcon.className = iconClass;
                    a.insertBefore(newIcon, a.firstChild);
                    if (!a.textContent.trim()) {
                        a.appendChild(document.createTextNode(isGithub ? ' GitHub' : ' Live'));
                    }
                }
            });
        });

        var cardObs = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    cardObs.unobserve(e.target);
                }
            });
        }, { threshold: 0, rootMargin: '0px 0px 0px 0px' });

        cards.forEach(function (card) { cardObs.observe(card); });
    }

    /* ─ 3D Tilt on project cards ─ */
    cards.forEach(function (card) {
        card.addEventListener('mousemove', function (e) {
            var rect = card.getBoundingClientRect();
            var x = (e.clientX - rect.left) / rect.width - 0.5;
            var y = (e.clientY - rect.top) / rect.height - 0.5;
            card.style.transform = 'perspective(800px) rotateY(' + (x * 6) + 'deg) rotateX(' + (-y * 5) + 'deg) translateY(-8px)';
        });
        card.addEventListener('mouseleave', function () {
            card.style.transform = 'perspective(800px) rotateY(0deg) rotateX(0deg) translateY(0)';
            card.style.transition = 'transform 0.6s cubic-bezier(0.23,1,0.32,1), border-color 0.4s, box-shadow 0.4s';
        });
        card.addEventListener('mouseenter', function () {
            card.style.transition = 'transform 0.1s ease, border-color 0.4s, box-shadow 0.4s';
        });
    });

    /* ─ Skill tags stagger ─ */
    var skillWrap = document.querySelector('.skill-names');
    if (skillWrap) {
        var skillTags = skillWrap.querySelectorAll('.skill-name-tag');
        skillTags.forEach(function (tag) {
            tag.style.opacity = '0';
            tag.style.transform = 'translateY(12px)';
            tag.style.transition = 'opacity 0.4s ease, transform 0.4s ease, background 0.3s, color 0.3s, border-color 0.3s';
        });

        var skillObs = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    skillTags.forEach(function (tag, i) {
                        setTimeout(function () {
                            tag.style.opacity = '1';
                            tag.style.transform = 'translateY(0)';
                        }, i * 65);
                    });
                    skillObs.unobserve(e.target);
                }
            });
        }, { threshold: 0.1 });
        skillObs.observe(skillWrap);
    }

    /* ─ Contact details slide ─ */
    var contactInfo = document.querySelector('.contact-info');
    if (contactInfo) {
        var details = contactInfo.querySelectorAll('.contact-detail');
        details.forEach(function (d) {
            d.style.opacity = '0';
            d.style.transform = 'translateX(-24px)';
            d.style.transition = 'opacity 0.5s ease, transform 0.5s ease, border-color 0.3s';
        });

        var cObs = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    details.forEach(function (d, i) {
                        setTimeout(function () {
                            d.style.opacity = '1';
                            d.style.transform = 'translateX(0)';
                        }, i * 140);
                    });
                    cObs.unobserve(e.target);
                }
            });
        }, { threshold: 0.1 });
        cObs.observe(contactInfo);
    }

    /* ─ Smooth scroll ─ */
    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
        anchor.addEventListener('click', function (e) {
            var target = document.querySelector(this.getAttribute('href'));
            if (!target) return;
            e.preventDefault();
            closeNav();
            window.scrollTo({
                top: target.getBoundingClientRect().top + window.scrollY - 80,
                behavior: 'smooth'
            });
        });
    });

    /* ─ Parallax on hero bg ─ */
    var heroBg = document.querySelector('.hero-bg');
    if (heroBg) {
        window.addEventListener('scroll', function () {
            var scrolled = window.scrollY;
            if (scrolled < window.innerHeight) {
                heroBg.style.transform = 'translateY(' + scrolled * 0.25 + 'px) scale(1.08)';
            }
        }, { passive: true });
    }

    /* ─ Form ripple ─ */
    var sendBtn = document.getElementById('formsend');
    if (sendBtn && !sendBtn.querySelector('span')) {
        var txt = sendBtn.textContent;
        sendBtn.innerHTML = '<span>' + txt + '</span>';
    }

    /* ─ Magnetic button effect ─ */
    document.querySelectorAll('.btn').forEach(function (btn) {
        btn.addEventListener('mousemove', function (e) {
            var rect = btn.getBoundingClientRect();
            var x = (e.clientX - rect.left - rect.width / 2) * 0.22;
            var y = (e.clientY - rect.top - rect.height / 2) * 0.22;
            btn.style.transform = 'translate(' + x + 'px, ' + y + 'px)';
        });
        btn.addEventListener('mouseleave', function () {
            btn.style.transform = 'translate(0, 0)';
            btn.style.transition = 'transform 0.5s cubic-bezier(0.23,1,0.32,1), color 0.4s, background 0.4s, box-shadow 0.4s';
        });
        btn.addEventListener('mouseenter', function () {
            btn.style.transition = 'transform 0.15s ease, color 0.4s, background 0.4s, box-shadow 0.4s';
        });
    });

    /* ─ About image intersection ─ */
    var aboutImg = document.querySelector('.about-img-wrap');
    if (aboutImg) {
        var imgObs = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    e.target.style.opacity = '1';
                    e.target.style.transform = 'translateX(0)';
                }
            });
        }, { threshold: 0.2 });
        aboutImg.style.opacity = '0';
        aboutImg.style.transform = 'translateX(-30px)';
        aboutImg.style.transition = 'opacity 0.9s ease, transform 0.9s cubic-bezier(0.23,1,0.32,1)';
        imgObs.observe(aboutImg);
    }

    /* ─ About text reveal ─ */
    var aboutText = document.querySelector('.about-text');
    if (aboutText) {
        var textObs = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    e.target.style.opacity = '1';
                    e.target.style.transform = 'translateX(0)';
                }
            });
        }, { threshold: 0.15 });
        aboutText.style.opacity = '0';
        aboutText.style.transform = 'translateX(30px)';
        aboutText.style.transition = 'opacity 0.9s ease 0.2s, transform 0.9s cubic-bezier(0.23,1,0.32,1) 0.2s';
        textObs.observe(aboutText);
    }

});