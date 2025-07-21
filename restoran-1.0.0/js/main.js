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
    spinner();
    
    
    // Initiate the wowjs
    new WOW().init();


    // Sticky Navbar
    $(window).scroll(function () {
        if ($(this).scrollTop() > 45) {
            $('.navbar').addClass('sticky-top shadow-sm');
        } else {
            $('.navbar').removeClass('sticky-top shadow-sm');
        }
    });
    
    
    // Dropdown on mouse hover
    const $dropdown = $(".dropdown");
    const $dropdownToggle = $(".dropdown-toggle");
    const $dropdownMenu = $(".dropdown-menu");
    const showClass = "show";
    
    $(window).on("load resize", function() {
        if (this.matchMedia("(min-width: 992px)").matches) {
            $dropdown.hover(
            function() {
                const $this = $(this);
                $this.addClass(showClass);
                $this.find($dropdownToggle).attr("aria-expanded", "true");
                $this.find($dropdownMenu).addClass(showClass);
            },
            function() {
                const $this = $(this);
                $this.removeClass(showClass);
                $this.find($dropdownToggle).attr("aria-expanded", "false");
                $this.find($dropdownMenu).removeClass(showClass);
            }
            );
        } else {
            $dropdown.off("mouseenter mouseleave");
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
        $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
        return false;
    });


    // Facts counter
    $('[data-toggle="counter-up"]').counterUp({
        delay: 10,
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


    // Testimonials carousel
    $(".testimonial-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1000,
        center: true,
        margin: 24,
        dots: true,
        loop: true,
        nav : false,
        responsive: {
            0:{
                items:1
            },
            768:{
                items:2
            },
            992:{
                items:3
            }
        }
    });
    
})(jQuery);
document.addEventListener('DOMContentLoaded', function() {
    // Form elements
    const loginForm = document.getElementById('login');
    const registerForm = document.getElementById('register');
    const resetForm = document.getElementById('reset-password');
    
    // Toggle between forms
    document.getElementById('switch-to-register').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('login-form').classList.remove('active');
        document.getElementById('register-form').classList.add('active');
        document.getElementById('login-toggle').classList.remove('active');
        document.getElementById('register-toggle').classList.add('active');
    });
    
    document.getElementById('switch-to-login').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('register-form').classList.remove('active');
        document.getElementById('login-form').classList.add('active');
        document.getElementById('register-toggle').classList.remove('active');
        document.getElementById('login-toggle').classList.add('active');
    });
    
    document.getElementById('forgot-password-link').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('login-form').classList.remove('active');
        document.getElementById('reset-form').classList.add('active');
    });
    
    document.getElementById('back-to-login').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('reset-form').classList.remove('active');
        document.getElementById('login-form').classList.add('active');
    });
    
    // Login form submission
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Clear previous errors
            document.querySelectorAll('#login-form .error-message').forEach(el => el.textContent = '');
            
            const email = document.getElementById('login-email').value;
            const password = document.getElementById('login-password').value;
            
            fetch('login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect || 'dashboard.php';
                } else {
                    // Display errors
                    if (data.errors) {
                        for (const [field, message] of Object.entries(data.errors)) {
                            const errorElement = document.getElementById(`login-${field}-error`);
                            if (errorElement) {
                                errorElement.textContent = message;
                            }
                        }
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
    
    // Register form submission
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Clear previous errors
            document.querySelectorAll('#register-form .error-message').forEach(el => el.textContent = '');
            
            const name = document.getElementById('register-name').value;
            const email = document.getElementById('register-email').value;
            const password = document.getElementById('register-password').value;
            const confirmPassword = document.getElementById('register-confirm-password').value;
            
            fetch('register.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}&confirm_password=${encodeURIComponent(confirmPassword)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    // Switch to login form
                    document.getElementById('register-form').classList.remove('active');
                    document.getElementById('login-form').classList.add('active');
                    document.getElementById('register-toggle').classList.remove('active');
                    document.getElementById('login-toggle').classList.add('active');
                } else {
                    // Display errors
                    if (data.errors) {
                        for (const [field, message] of Object.entries(data.errors)) {
                            const errorElement = document.getElementById(`register-${field.replace('confirm_', 'confirm-')}-error`);
                            if (errorElement) {
                                errorElement.textContent = message;
                            }
                        }
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
    
    // Reset password form submission
    if (resetForm) {
        resetForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Clear previous errors
            document.querySelectorAll('#reset-form .error-message').forEach(el => el.textContent = '');
            document.getElementById('reset-success').textContent = '';
            
            const email = document.getElementById('reset-email').value;
            const newPassword = document.getElementById('new-password').value;
            const confirmNewPassword = document.getElementById('confirm-new-password').value;
            
            fetch('reset_password.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `email=${encodeURIComponent(email)}&new_password=${encodeURIComponent(newPassword)}&confirm_new_password=${encodeURIComponent(confirmNewPassword)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('reset-success').textContent = data.message;
                    // Clear form
                    resetForm.reset();
                } else {
                    // Display errors
                    if (data.errors) {
                        for (const [field, message] of Object.entries(data.errors)) {
                            const errorElement = document.getElementById(`${field.replace('confirm_', 'confirm-')}-error`);
                            if (errorElement) {
                                errorElement.textContent = message;
                            }
                        }
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
    
    // Close modal
    document.getElementById('closeLoginModal').addEventListener('click', function() {
        document.getElementById('loginModal').style.display = 'none';
    });
});

