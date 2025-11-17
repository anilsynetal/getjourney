(function ($) {
    "use strict";

    // Spinner
    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner(0);


    // Initiate the wowjs
    new WOW().init();


    // Sticky Navbar
    $(window).scroll(function () {
        if ($(this).scrollTop() > 45) {
            $('.nav-bar').addClass('sticky-top shadow-sm');
        } else {
            $('.nav-bar').removeClass('sticky-top shadow-sm');
        }
    });

    // Facts counter
    $('[data-toggle="counter-up"]').counterUp({
        delay: 5,
        time: 2000
    });


    // Modal Video
    $(document).ready(function () {
        var $videoSrc;
        $('.btn-play').click(function () {
            $videoSrc = $(this).data("src");
        });
        console.log($videoSrc);

        $('#videoModal').on('shown.bs.modal', function (e) {
            $("#video").attr('src', $videoSrc + "?autoplay=1&amp;modestbranding=1&amp;showinfo=0");
        })

        $('#videoModal').on('hide.bs.modal', function (e) {
            $("#video").attr('src', $videoSrc);
        })
    });


    // Testimonial-carousel
    $(".testimonial-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 2000,
        center: false,
        dots: false,
        loop: true,
        margin: 25,
        nav: true,
        navText: [
            '<i class="bi bi-arrow-left"></i>',
            '<i class="bi bi-arrow-right"></i>'
        ],
        responsiveClass: true,
        responsive: {
            0: {
                items: 1
            },
            576: {
                items: 1
            },
            768: {
                items: 2
            },
            992: {
                items: 2
            },
            1200: {
                items: 2
            }
        }
    });



    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({ scrollTop: 0 }, 1500, 'easeInOutExpo');
        return false;
    });




})(jQuery);

document.addEventListener('DOMContentLoaded', function () {
    // Enhanced dropdown behavior
    const dropdownToggle = document.querySelector('.nav-item.dropdown .dropdown-toggle');
    const dropdownMenu = document.querySelector('.tours-dropdown');
    let dropdownTimeout;

    if (dropdownToggle && dropdownMenu) {
        // Show dropdown on hover (desktop only)
        if (window.innerWidth >= 992) {
            dropdownToggle.parentElement.addEventListener('mouseenter', function () {
                clearTimeout(dropdownTimeout);
                dropdownMenu.classList.add('show');
                dropdownToggle.setAttribute('aria-expanded', 'true');
            });

            dropdownToggle.parentElement.addEventListener('mouseleave', function () {
                dropdownTimeout = setTimeout(() => {
                    dropdownMenu.classList.remove('show');
                    dropdownToggle.setAttribute('aria-expanded', 'false');
                }, 100);
            });

            // Keep dropdown open when hovering over it
            dropdownMenu.addEventListener('mouseenter', function () {
                clearTimeout(dropdownTimeout);
            });

            dropdownMenu.addEventListener('mouseleave', function () {
                dropdownTimeout = setTimeout(() => {
                    dropdownMenu.classList.remove('show');
                    dropdownToggle.setAttribute('aria-expanded', 'false');
                }, 100);
            });
        }

        // Add smooth animation classes
        dropdownMenu.addEventListener('show.bs.dropdown', function () {
            this.style.opacity = '0';
            this.style.transform = 'translateY(-10px)';

            setTimeout(() => {
                this.style.transition = 'all 0.3s ease-out';
                this.style.opacity = '1';
                this.style.transform = 'translateY(0)';
            }, 10);
        });

        // Add click tracking for analytics (optional)
        const countryLinks = dropdownMenu.querySelectorAll('.country-item');
        countryLinks.forEach(link => {
            link.addEventListener('click', function () {
                const countryName = this.querySelector('.country-name').textContent;
                // You can add Google Analytics or other tracking here
                console.log('Country clicked:', countryName);
            });
        });
    }

    // Close dropdown when clicking outside
    // document.addEventListener('click', function (event) {
    //     if (!dropdownToggle.parentElement.contains(event.target)) {
    //         dropdownMenu.classList.remove('show');
    //         dropdownToggle.setAttribute('aria-expanded', 'false');
    //     }
    // });

    // Handle window resize
    window.addEventListener('resize', function () {
        if (window.innerWidth < 992) {
            // Remove hover behavior on mobile
            dropdownMenu.classList.remove('show');
            dropdownToggle.setAttribute('aria-expanded', 'false');
        }
    });
});

