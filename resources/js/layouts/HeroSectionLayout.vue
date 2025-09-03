<template>
  <section class="body-container" id="heroSection">
    <div class="hero-container">
      <div class="hero-container__main">
        <div class="blob-container">
          <div class="blob-1"></div>
          <div class="blob-2"></div>
          <div class="blob-3"></div>
          <div class="blob-4"></div>
        </div>
          <h1 class="hero__main-h1">Helping create&nbsp;<span class="auto-type"></span></h1>
          <div class="card__sign-in">
              <h2 class="card__heading">SIGN-IN TO APPLY</h2>
              <p class="card__sign-in__card-description">Apply now for the upcoming academic year. Please ensure that you have a stable internet connection when accessing this page. Please continue with Google when you are ready. </p>
              <Link class="card__signup">Google SSO</Link>
          </div>
          <!-- <div class="courses-container">
              <h2 class="courses-offered">COURSES OFFERED</h2>
              <div class="courses-item-container">
                <div class="courses-item">
                  <div>
                    <div class="courses__card-icon__bg">
                    <div class="courses__card-icon"><img src="../../../storage/app/public/images/nurse-line.svg" alt="Diploma in Midwifery Icon"></div>
                    </div>
                  </div>
                  <div class="courses-group">
                    <p class="course-title">Bachelor of Science in Nursing</p>
                  </div>
                </div>
                <div class="courses-item">
                  <div class="courses-group">
                  <div class="courses__card-icon__bg">
                    <div class="courses__card-icon"> <img src="../../../storage/app/public/images/lungs-fill.svg" alt="Diploma in Midwifery Icon"></div>
                    </div>
                  </div>
                  <div class="courses-group">
                    <p class="course-title">Bachelor of Science in Radiologic Technology</p>
                  </div>
                </div>
                <div class="courses-item">
                  <div class="courses-group">
                    <div class="courses__card-icon__bg">
                      <div class="courses__card-icon"><img src="../../../storage/app/public/images/empathize-line.svg" alt="Diploma in Midwifery Icon">
                      </div>
                    </div>
                  </div>
                <div class="courses-group">
                  <p class="course-title">Diploma in Midwifery</p>
                </div>
              </div>
            </div>
          </div> -->
      </div>
    </div>
  </section>   
</template>

<script lang="ts">
 export default {
    name: 'HeroSectionLayout',
      data() {
        return {
          loading: false,
          loadingTime: 0,
          maxLoadingTime: 3
        }
      },
    }
  import { onMounted, onUnmounted } from 'vue';

// Declare Typed type for TypeScript
declare const Typed: any;

let typed: any = null;

</script>

<script lang="ts" setup>
  
import { Link } from '@inertiajs/vue3';

onMounted(() => {
  // Check if Typed is already loaded (from index.html or previous load)
  if (typeof Typed !== 'undefined') {
    initTyped();
  } else {
    // Dynamically load the script
    const script = document.createElement('script');
    script.src = 'https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js';
    script.onload = initTyped;
    document.head.appendChild(script);
  }
});

const initTyped = () => {
  typed = new Typed(".auto-type", {
    strings: ["connections", "professionals", "futures"], 
    typeSpeed: 150,
    backSpeed: 150,
    loop: true
  });
};

onUnmounted(() => {
  if (typed) {
    typed.destroy();
  }
});
</script>
