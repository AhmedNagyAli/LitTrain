<!-- Hero Section with Slider -->
<section class="relative w-full h-[90vh] overflow-hidden">
  <!-- Swiper -->
  <div class="swiper mySwiper w-full h-full">
    <div class="swiper-wrapper">

      <!-- Slide 1 -->
      <div class="swiper-slide relative">
        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1920&q=80"
             class="w-full h-full object-cover" />
        <div class="absolute inset-0 bg-black/50 flex flex-col justify-center items-center text-center text-white">
          <h1 class="text-4xl md:text-6xl font-extrabold mb-4">Discover New Books</h1>
          <p class="text-lg md:text-xl max-w-2xl">Expand your knowledge with our vast collection of modern and classic literature.</p>
        </div>
      </div>

      <!-- Slide 2 -->
      <div class="swiper-slide relative">
        <img src="https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=1920&q=80"
             class="w-full h-full object-cover" />
        <div class="absolute inset-0 bg-black/50 flex flex-col justify-center items-center text-center text-white">
          <h1 class="text-4xl md:text-6xl font-extrabold mb-4">Train Your Skills</h1>
          <p class="text-lg md:text-xl max-w-2xl">Interactive training sessions designed to improve your reading speed and accuracy.</p>
        </div>
      </div>

      <!-- Slide 3 -->
      <div class="swiper-slide relative">
        <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=1920&q=80"
             class="w-full h-full object-cover" />
        <div class="absolute inset-0 bg-black/50 flex flex-col justify-center items-center text-center text-white">
          <h1 class="text-4xl md:text-6xl font-extrabold mb-4">Join Our Community</h1>
          <p class="text-lg md:text-xl max-w-2xl">Connect with readers and writers worldwide, share knowledge, and grow together.</p>
        </div>
      </div>

    </div>

    <!-- Pagination -->
    <div class="swiper-pagination"></div>
    <!-- Navigation -->
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
  </div>
</section>

<!-- SwiperJS CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
  var swiper = new Swiper(".mySwiper", {
    loop: true,
    autoplay: {
      delay: 4000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    effect: "fade",
    fadeEffect: {
      crossFade: true
    }
  });
</script>
