<?php
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <div class="landing_page-logo">
            <img src="./images/logo.png" alt="company-logo" id="landing-logo">
        </div>
        <div class="header-nav-list">
            <a href="#" class="header-nav-listname nav-hover active">HOME</a>
            <a href="./service_landing_page.html" class="header-nav-listname nav-hover">SERVICES</a>
            <a href="" class="header-nav-listname nav-hover">ABOUT US</a>
            <button class="landing-btn header-nav-listname pink" onclick="window.location.href='./login.html'">LOGIN</button>
            <button class="landing-btn header-nav-listname blue" onclick="window.location.href='./register.html'">REGISTER</button>
        </div>
    </header>
    <main>
        <div class="row">
            <div class="col-1">
                <div class="item-text-display">
                    <p class="item-description text-1st">FAST</p>
                    <P class="item-description text-2nd">EFFICIENT</P>
                    <p class="item-description text-3rd">TRUSTWORTY</p>
                    <div class="btn-start-position">
                        <button class="btn-start" onclick="window.location.assign('./register.html')">
                            <p class="btn-text">Get Started!</p>
                            <div class="arrow">
                                <div class="long-arrow"></div>
                            </div>
                        </button>
                    </div>

                </div>
            </div>
            <div class="col-2">
                <div class="bg-light-ellipse ellipse1"></div>

                <div class="carousel">
                    <div class="carousel-track">
                        <img class="slide" src="./images/architect-c4000.png" alt="architect-c4000">
                        <img class="slide" src="./images/cell-dyn_ruby.png" alt="cell-dyn ruby">
                        <img class="slide" src="./images/architect-i1000sr.png" alt="architect-i1000sr">
                    </div>
                </div>

                <img src="./images/prev.png" class="carousel-button prev" alt="Previous">
                <img src="./images/next.png" class="carousel-button next" alt="Next">

                <div class="carousel-indicators">
                    <span class="dot current"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                </div>
            </div>
        </div>

        <!-- Row 2 -->
        <div class="row row2">
            <div class="col-1">
                <div class="bg-light-ellipse ellipse2"></div>
                <img class="img-1" src="./images/architect-c4000.png" alt="">
            </div>

            <div class="col-2">
                <h2 class="item-description text-center">EXPLORE</h2>
                <pre class="text-block" style="margin-left: 15%;">
Explore different kinds of reagent machines 
that are used in laboratories to automate the 
handling and dispensing of chemical substances, 
improving accuracy, efficiency, and safety in 
various testing and analytical procedures. 
These machines range from simple dispensers to 
highly advanced robotic systems capable of 
preparing complex assays, reducing human error, 
and ensuring consistent results.
                </pre>
            </div>
        </div>

        <!-- Row 3 -->
        <div class="row row2">
            <div class="col-1">
                <h2 class="item-description text-center">MONITOR</h2>
                <pre class="text-block" style="margin-left: 15%;">
Continuous monitoring of the reagent machines 
includes tracking input levels of reagents and 
output performance metrics to ensure optimal 
functionality and timely replenishment.
                </pre>
            </div>

            <div class="col-2">
                <div class="bg-light-ellipse ellipse3"></div>
                <img class="img-2" src="./images/cell-dyn_ruby.png" alt="">
            </div>
        </div>

        <!-- Row 4 -->
        <div class="row">
            <div class="col-1">
                <div class="bg-light-ellipse ellipse4"></div>
                <img class="img-3" src="./images/microscope.png" alt="">
            </div>

            <div class="col-3">
                <h2 class="item-description" style="margin-left: 25%;">NOTIFY</h2>
                <pre class="text-block">
Notify users when reagent machines require 
maintenance, based on input levels, output 
performance, or scheduled service intervals, 
to prevent downtime and ensure continuous 
operation.
                </pre>
                <button class="btn-start" onclick="window.location.assign('./login.html')" style="margin-left: 35%;">
                    <p class="btn-text">Learn More</p>
                    <div class="arrow">
                        <div class="long-arrow"></div>
                    </div>
                </button>
            </div>
        </div>
    </main>

    <footer>
        <h4 class="footer-title">Get In Touch</h4>
        <div class="footer-row footer-margin">
            <div class="footer-col-1">
                <div class="footer-contacts">
                    <img src="./images/location-icon.png" alt="" class="footer-img">
                    <h5 class="footer-text text-bolder text-bigger">ADDRESS</h5>
                    <p class="footer-text text-bolder">J&JK METS NAGA BRANCH</p>
                    <p class="footer-text">NAGA, TABACO CITY OF POLANGUI</p>
                    <p class="footer-text">M.H DEL PILAR ST. ZONE 65</p>
                </div>
            </div>
            <div class="footer-col-2">
                <div class="footer-contacts">
                    <img src="./images/call-icon.png" alt="" class="footer-img">
                    <h5 class="footer-text text-bolder text-bigger">PHONE</h5>
                    <p class="footer-text text-bolder">ALEX MAYER</p>
                    <p class="footer-text">09XX-XXX-XXX | SMART</p>
                    <p class="footer-text">09XX-XXX-XXXX | GLOBE</p>
                </div>
            </div>
            <div class="footer-col-3">
                <div class="footer-contacts">
                    <img src="./images/gmail-icon.png" alt="" class="footer-img">
                    <h5 class="footer-text text-bolder text-bigger">EMAIL</h5>
                    <p class="footer-text text-bolder">AlexMayer@gmail.com</p>
                    <p class="footer-text text-bolder">The GreedyChef@gmail.com</p>
                </div>
            </div>
        </div>
    </footer>



    <script>
        // Carousel + Dot Indicator (dont know how tf this work)
        const track = document.querySelector('.carousel-track');
        const slides = Array.from(document.querySelectorAll('.slide'));
        const dots = document.querySelectorAll(".dot");
        const nextBtn = document.querySelector('.next');
        const prevBtn = document.querySelector('.prev');

        let currentIndex = 0;
        const slideWidth = 500; //width of the image (not official)

        function goToSlide(index) {
            // wrap around
            currentIndex = (index + slides.length) % slides.length;

            // move carousel
            track.style.transform = `translateX(-${currentIndex * slideWidth}px)`;

            // update dots
            dots.forEach(dot => dot.classList.remove("current"));
            dots[currentIndex].classList.add("current");
        }

        // next button
        nextBtn.addEventListener('click', () => {
            goToSlide(currentIndex + 1);
            resetAuto();
        });

        // prev button
        prevBtn.addEventListener('click', () => {
            goToSlide(currentIndex - 1);
            resetAuto();
        });

        // dots
        dots.forEach((dot, index) => {
            dot.addEventListener("click", () => {
                goToSlide(index)
                resetAuto();
            });
        });

        let auto = setInterval(() => goToSlide(currentIndex + 1), 3000);

        function resetAuto() {
            clearInterval(auto);
            auto = setInternal(() => goToSlide(currentIndex + 1), 3000);
        }

        window.addEventListener("resize", () => goToSlide(currentIndex));

        goToSlide(0);
    </script>
</body>

</html>