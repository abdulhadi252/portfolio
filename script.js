/* ── Custom Cursor ── */
(function(){
    var dot=document.querySelector('.cursor-dot');
    var ring=document.querySelector('.cursor-ring');
    if(!dot||!ring)return;
    var mx=0,my=0,rx=0,ry=0,dx=0,dy=0;
    document.addEventListener('mousemove',function(e){mx=e.clientX;my=e.clientY;});
    function anim(){
        dx+=(mx-dx)*.6;dy+=(my-dy)*.6;
        dot.style.transform='translate('+(dx-3)+'px,'+(dy-3)+'px)';
        rx+=(mx-rx-17)*.13;ry+=(my-ry-17)*.13;
        ring.style.transform='translate('+rx+'px,'+ry+'px)';
        requestAnimationFrame(anim);
    }
    anim();
    document.querySelectorAll('a,button,.project-card,.skill-name-tag,.stat-item').forEach(function(el){
        el.addEventListener('mouseenter',function(){ring.classList.add('hover');});
        el.addEventListener('mouseleave',function(){ring.classList.remove('hover');});
    });
})();

/* ── Navbar ── */
function toggleNav(){
    var l=document.getElementById('navLinks');
    var i=document.getElementById('toggle-icon');
    l.classList.toggle('open');
    i.className=l.classList.contains('open')?'fas fa-times':'fas fa-bars';
}
function closeNav(){
    document.getElementById('navLinks').classList.remove('open');
    document.getElementById('toggle-icon').className='fas fa-bars';
}

document.addEventListener('DOMContentLoaded',function(){

    /* ── Active nav ── */
    var secs=document.querySelectorAll('section[id]');
    var navAs=document.querySelectorAll('.nav-links a');
    new IntersectionObserver(function(entries){
        entries.forEach(function(e){
            if(e.isIntersecting)navAs.forEach(function(a){a.classList.toggle('nav-active',a.getAttribute('href')==='#'+e.target.id);});
        });
    },{threshold:.35}).observe&&secs.forEach(function(s){
        new IntersectionObserver(function(entries){
            entries.forEach(function(e){
                if(e.isIntersecting)navAs.forEach(function(a){a.classList.toggle('nav-active',a.getAttribute('href')==='#'+e.target.id);});
            });
        },{threshold:.35}).observe(s);
    });

    /* ── Animate on scroll ── */
    var secObs=new IntersectionObserver(function(entries){
        entries.forEach(function(e){if(e.isIntersecting)e.target.classList.add('visible');});
    },{threshold:.05});
    document.querySelectorAll('.animate-on-scroll').forEach(function(el){secObs.observe(el);});

    /* ── Stats ── */
    var statsEl=document.querySelector('.stats-section');
    if(statsEl){
        var statsObs=new IntersectionObserver(function(entries){
            entries.forEach(function(e){
                if(e.isIntersecting){
                    e.target.querySelectorAll('.stat-item').forEach(function(item,i){
                        setTimeout(function(){item.classList.add('visible');},i*130);
                    });
                    statsObs.unobserve(e.target);
                }
            });
        },{threshold:.1});
        statsObs.observe(statsEl);
    }

    /* ── Counter easeOutExpo ── */
    function easeOut(t){return t===1?1:1-Math.pow(2,-10*t);}
    function animCount(el,target,dur){
        var start=null;
        function step(ts){
            if(!start)start=ts;
            var p=Math.min((ts-start)/dur,1);
            el.textContent=Math.floor(easeOut(p)*target)+'+';
            if(p<1)requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    }
    var cDone=false;
    if(statsEl){
        new IntersectionObserver(function(entries){
            entries.forEach(function(e){
                if(e.isIntersecting&&!cDone){
                    cDone=true;
                    document.querySelectorAll('.stat-item h3').forEach(function(el){
                        var v=parseInt(el.textContent);
                        if(!isNaN(v))animCount(el,v,1400);
                    });
                }
            });
        },{threshold:.3}).observe(statsEl);
    }

    /* ── Project cards ── */
    var cards=document.querySelectorAll('.project-card');
    var cardObs=new IntersectionObserver(function(entries){
        entries.forEach(function(e){
            if(e.isIntersecting){e.target.classList.add('visible');cardObs.unobserve(e.target);}
        });
    },{threshold:0});
    cards.forEach(function(card){cardObs.observe(card);});

    /* ── 3D tilt ── */
    cards.forEach(function(card){
        card.addEventListener('mousemove',function(e){
            var r=card.getBoundingClientRect();
            var x=(e.clientX-r.left)/r.width-.5;
            var y=(e.clientY-r.top)/r.height-.5;
            card.style.transform='perspective(900px) rotateY('+(x*5)+'deg) rotateX('+(-y*4)+'deg) translateY(-6px)';
        });
        card.addEventListener('mouseleave',function(){
            card.style.transform='perspective(900px) rotateY(0) rotateX(0) translateY(0)';
            card.style.transition='transform .65s cubic-bezier(.23,1,.32,1),border-color .4s,box-shadow .4s,opacity .6s';
        });
        card.addEventListener('mouseenter',function(){
            card.style.transition='transform .12s ease,border-color .4s,box-shadow .4s,opacity .6s';
        });
    });

    /* ── Skill tags stagger ── */
    var sw=document.querySelector('.skill-names');
    if(sw){
        var tags=sw.querySelectorAll('.skill-name-tag');
        tags.forEach(function(t){t.style.opacity='0';t.style.transform='translateY(10px)';t.style.transition='opacity .4s,transform .4s,color .35s,border-color .35s';});
        new IntersectionObserver(function(entries){
            entries.forEach(function(e){
                if(e.isIntersecting){
                    tags.forEach(function(t,i){setTimeout(function(){t.style.opacity='1';t.style.transform='translateY(0)';},i*60);});
                }
            });
        },{threshold:.1}).observe(sw);
    }

    /* ── Contact slide ── */
    var ci=document.querySelector('.contact-info');
    if(ci){
        var ds=ci.querySelectorAll('.contact-detail');
        ds.forEach(function(d){d.style.opacity='0';d.style.transform='translateX(-20px)';d.style.transition='opacity .5s,transform .5s,border-color .3s,background .3s';});
        new IntersectionObserver(function(entries){
            entries.forEach(function(e){
                if(e.isIntersecting){ds.forEach(function(d,i){setTimeout(function(){d.style.opacity='1';d.style.transform='translateX(0)';},i*140);});}
            });
        },{threshold:.1}).observe(ci);
    }

    /* ── Smooth scroll ── */
    document.querySelectorAll('a[href^="#"]').forEach(function(a){
        a.addEventListener('click',function(e){
            var t=document.querySelector(this.getAttribute('href'));
            if(!t)return;
            e.preventDefault();closeNav();
            window.scrollTo({top:t.getBoundingClientRect().top+window.scrollY-80,behavior:'smooth'});
        });
    });

    /* ── Hero video parallax ── */
    var hv=document.querySelector('.hero-bg');
    if(hv){
        window.addEventListener('scroll',function(){
            if(window.scrollY<window.innerHeight){
                hv.style.transform='translateY('+(window.scrollY*.15)+'px) scale(1.04)';
            }
        },{passive:true});
    }

    /* ── About image ── */
    var ai=document.querySelector('.about-img-wrap');
    if(ai){
        ai.style.opacity='0';ai.style.transform='translateX(-28px)';ai.style.transition='opacity 1s,transform 1s cubic-bezier(.23,1,.32,1)';
        new IntersectionObserver(function(entries){
            entries.forEach(function(e){if(e.isIntersecting){e.target.style.opacity='1';e.target.style.transform='translateX(0)';}});
        },{threshold:.15}).observe(ai);
    }

    /* ── About text ── */
    var at=document.querySelector('.about-text');
    if(at){
        at.style.opacity='0';at.style.transform='translateX(28px)';at.style.transition='opacity 1s ease .2s,transform 1s cubic-bezier(.23,1,.32,1) .2s';
        new IntersectionObserver(function(entries){
            entries.forEach(function(e){if(e.isIntersecting){e.target.style.opacity='1';e.target.style.transform='translateX(0)';}});
        },{threshold:.12}).observe(at);
    }

    /* ── Magnetic buttons ── */
    document.querySelectorAll('.btn').forEach(function(btn){
        btn.addEventListener('mousemove',function(e){
            var r=btn.getBoundingClientRect();
            var x=(e.clientX-r.left-r.width/2)*.2;
            var y=(e.clientY-r.top-r.height/2)*.2;
            btn.style.transform='translate('+x+'px,'+y+'px)';
        });
        btn.addEventListener('mouseleave',function(){btn.style.transform='translate(0,0)';btn.style.transition='transform .55s cubic-bezier(.23,1,.32,1),color .4s,background .4s';});
        btn.addEventListener('mouseenter',function(){btn.style.transition='transform .12s ease,color .4s,background .4s';});
    });

    /* ── Form label color on focus ── */
    document.querySelectorAll('.form-control').forEach(function(inp){
        inp.addEventListener('focus',function(){
            var l=this.closest('.form-group')&&this.closest('.form-group').querySelector('label');
            if(l)l.style.color='var(--accent2)';
        });
        inp.addEventListener('blur',function(){
            var l=this.closest('.form-group')&&this.closest('.form-group').querySelector('label');
            if(l)l.style.color='var(--primary)';
        });
    });

});