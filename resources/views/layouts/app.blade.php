
<!DOCTYPE html>
<html lang="en">

<head>
      <title>@yield('title', 'LiveJobsBD - Find Your Dream Jobs')</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700;9..144,800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
        <!-- Meanmenu CSS -->
        <link rel="stylesheet" href="{{asset('assets/css/meanmenu.css')}}">

        <!-- Icofont -->
        <link rel="stylesheet" href="{{asset('assets/css/icofont.min.css')}}">

        <!-- Owl Carousel CSS -->
        <link rel="stylesheet" href="{{asset('assets/css/owl.carousel.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/css/owl.theme.default.min.css')}}">
    
        <!-- Animate CSS -->
        <link rel="stylesheet" href="{{asset('assets/css/animate.min.css')}}">
 
        <!-- Flaticon CSS -->
        <link rel="stylesheet" href="{{asset('assets/css/animate.min.css')}}">
        <link rel="stylesheet" href="assets/fonts/flaticon.css">
        <!-- Odometer CSS -->
        <link rel="stylesheet" href="{{asset('assets/css/odometer.min.css')}}">
 
        <!-- Style CSS -->
        <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
  
        <!-- Responsive CSS -->
        <link rel="stylesheet" href="{{asset('assets/css/responsive.css')}}">
   
        <!-- Theme Dark CSS -->
        <link rel="stylesheet" href="{{asset('assets/css/theme-dark.css')}}">
    
        <link rel="icon" type="image/png" href="{{asset('assets/img/favicon.png')}}">
        

        @vite('resources/css/app.css')
        @vite('resources/js/app.js')

        <link rel="stylesheet" type="text/css" href="{{ asset('css/home.css') }}">

    </head>
<body>


       @include('partials.navbar')
        <main class="py-4">
                @yield('content')
        </main>
       @include('partials.footer')




      <!-- Essential JS -->
        <script data-cfasync="false" src="../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/bootstrap.bundle.min.js"></script>
        <!-- Meanmenu JS -->
        <script src="assets/js/jquery.meanmenu.js"></script>
        <!-- Mixitup JS -->
        <script src="assets/js/jquery.mixitup.min.js"></script>
        <!-- Owl Carousel JS -->
        <script src="assets/js/owl.carousel.min.js"></script>
        <!-- Form Ajaxchimp JS -->
    <script src="assets/js/jquery.ajaxchimp.min.js"></script>
    <!-- Form Validator JS -->
    <script src="assets/js/form-validator.min.js"></script>
    <!-- Contact JS -->
        <script src="assets/js/contact-form-script.js"></script>
        <!-- Wow JS -->
        <script src="assets/js/wow.min.js"></script>
        <!-- Odometer JS -->
        <script src="assets/js/odometer.min.js"></script>
        <script src="assets/js/jquery.appear.min.js"></script>
        <!-- Custom JS -->
        <script src="assets/js/custom.js"></script>

    <script>
const menuToggle = document.getElementById('menuToggle');
const navLinks = document.getElementById('navLinks');
menuToggle.addEventListener('click', () => {
    navLinks.classList.toggle('active');
    const icon = menuToggle.querySelector('i');
    icon.classList.toggle('fa-bars');
    icon.classList.toggle('fa-times');
});

document.querySelectorAll('a[href^="#"]').forEach(anchor=>{
    anchor.addEventListener('click',function(e){
        const target = document.querySelector(this.getAttribute('href'));
        if(target){
            e.preventDefault();
            target.scrollIntoView({behavior:'smooth'});
            navLinks.classList.remove('active');
        }
    });
});

const stats = document.querySelectorAll('.stat-item');
const sections = document.querySelectorAll('.section-header');

window.addEventListener('scroll', () => {
    let triggerBottom = window.innerHeight / 5 * 4;
    stats.forEach(stat => {
        const statTop = stat.getBoundingClientRect().top;
        if(statTop < triggerBottom) stat.classList.add('active');
    });
    sections.forEach(section => {
        const sectionTop = section.getBoundingClientRect().top;
        if(sectionTop < triggerBottom) section.classList.add('active');
    });
});
</script>
</body>
</html>
