   // Skill Section Progresss Bar Script
        let bars = document.querySelectorAll(".progress-bar");

        for (let i = 0; i < bars.length; i++) {
            let percent = bars[i].dataset.width;
            bars[i].style.width = percent + "%";
        }
        // Skill Section Progresss Bar Script end

        // for project dot click to new project 
        const dots = document.querySelectorAll(".slider-dot");
        const projectsContainer = document.querySelector(".projects-container");
        const totalSlides = document.querySelectorAll(".project-card").length;

        dots.forEach((dot, index) => {
            dot.addEventListener("click", () => {
                // move slider
                projectsContainer.style.transform = `translateX(-${index * 100}%)`;

                // active dot change
                dots.forEach(d => d.classList.remove("active"));
                dot.classList.add("active");
            });
        });

        // for project dot click to new project end 

        let elements = document.querySelectorAll('.animate-on-scroll');

        document.onscroll = function () {
            for (let i = 0; i < elements.length; i++) {
                let scroll = elements[i];
                if (scroll.getBoundingClientRect().top < 400) { // jab element screen ke andar aa jaye
                    scroll.style.opacity = 1;
                    scroll.style.transform = 'translateY(0)';
                }
            }
        }


        // ............................form validation.........................
        function data() {
            let name = document.getElementById("name").value;
            let company = document.getElementById("company").value;
            let email = document.getElementById("email").value;
            let phone = document.getElementById("phone").value;
            let ideas = document.getElementById("ideas").value;
            let feedback = document.getElementById("feedback").value;

            if (name == "" || company == "" || email == "" || phone == "" || ideas == "" || feedback == "") {
                alert("Please Fill All Fields");
                return false;
            }
            else if (!email.endsWith("@gmail.com")) {
                alert("Please Enter a Valid Email");
                return false;
            }
            else if (phone.length < 11) {
                alert("Please Enter a Valid Number");
                return false;
            }
            else if (isNaN(phone)) {
                alert("Please Enter Only Number");
                return false;
            }
            else if (!phone.startsWith("03" || "92" || "35")) {
                alert("please number starts for 03/92/35 only for some country");
                return false;
            }
            else {
                alert("Your Form Succesfully Submited Thanksful To You ✨✨");
                return true;
            }
        }