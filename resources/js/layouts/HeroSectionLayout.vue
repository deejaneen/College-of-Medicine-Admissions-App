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
          <h1 class="hero__main-typing-banner">CREATING&nbsp;<div><span class="auto-type"></span></div></h1>
          <div class="card__sign-in">
              <h2 class="card__heading">SIGN-IN TO APPLY</h2>
              <p class="card__sign-in__card-description">Welcome to the CAM Admissions Portal. This functions like a Google Form. Please continue with Google when you are ready. </p>
              <a :href="route('login.google')" class="card__signup">
                <img src="../../../storage/app/public/images/icons8-google-logo.svg" alt="Google Logo">
                Sign in with Google
              </a>
          </div>
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
    strings: ["careers", "passions", "futures"], 
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

<style scoped>
.body-container {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}

.hero-container {
    width: 100%;
    position: relative;
}

.hero-container__main {
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    clip-path: ellipse(120% 100% at top);
    background: var(--color-background);
    padding: var(--space-24) 0 var(--space-32) 0;
    position: relative;
    overflow: hidden;
}

.blob-container {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 0;
    filter: blur(8rem);
}

.blob-1,
.blob-2,
.blob-3,
.blob-4 {
    position: absolute;
    border-radius: 50%;
    mix-blend-mode: multiply;
    opacity: 0.6;
    will-change: transform;
}

/* Larger, slower moving blobs */
.blob-1 {
    width: 80rem;
    height: 80rem;
    background: var(--color-blue-green-15);
    top: -20%;
    left: -10%;
    animation: blob1 120s infinite ease-in-out;
}

.blob-2 {
    width: 70rem;
    height: 70rem;
    background: var(--color-secondary-20);
    top: 10%;
    right: -15%;
    animation: blob2 140s infinite ease-in-out;
}

.blob-3 {
    width: 90rem;
    height: 90rem;
    background: var(--color-secondary-20);
    bottom: -25%;
    left: 20%;
    animation: blob3 160s infinite ease-in-out;
}

.blob-4 {
    width: 75rem;
    height: 75rem;
    background: var(--color-blue-green-15);
    bottom: 10%;
    right: 10%;
    animation: blob4 180s infinite ease-in-out;
}

/* Very slow, calming animations */
@keyframes blob1 {
    0%, 100% {
        transform: translate(0, 0) scale(1) rotate(0deg);
    }
    25% {
        transform: translate(10vw, 15vh) scale(1.1) rotate(90deg);
    }
    50% {
        transform: translate(5vw, 5vh) scale(0.95) rotate(180deg);
    }
    75% {
        transform: translate(-5vw, 10vh) scale(1.05) rotate(270deg);
    }
}

@keyframes blob2 {
    0%, 100% {
        transform: translate(0, 0) scale(1) rotate(0deg);
    }
    33% {
        transform: translate(-8vw, -12vh) scale(1.15) rotate(120deg);
    }
    66% {
        transform: translate(12vw, -5vh) scale(0.9) rotate(240deg);
    }
}

@keyframes blob3 {
    0%, 100% {
        transform: translate(0, 0) scale(1) rotate(0deg);
    }
    20% {
        transform: translate(-15vw, 8vh) scale(1.2) rotate(72deg);
    }
    40% {
        transform: translate(10vw, -10vh) scale(0.85) rotate(144deg);
    }
    60% {
        transform: translate(5vw, 15vh) scale(1.1) rotate(216deg);
    }
    80% {
        transform: translate(-8vw, -5vh) scale(0.95) rotate(288deg);
    }
}

@keyframes blob4 {
    0%, 100% {
        transform: translate(0, 0) scale(1) rotate(0deg);
    }
    50% {
        transform: translate(-10vw, -8vh) scale(1.25) rotate(180deg);
    }
}

.hero__main-typing-banner {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--color-foreground);
    text-transform: uppercase;
    margin-bottom: var(--space-10);
    letter-spacing: -2%;
    line-height: 110%;
    font-weight: bold;
    font-size: var(--font-size-h1);
    position: relative;
    z-index: 2;
    text-align: center;
    flex-wrap: wrap;
    gap: var(--space-2);
}

.card__sign-in {
    background: var(--color-background);
    border-radius: var(--radius-l);
    padding: var(--space-8);
    box-shadow: var(--elevation-2);
    max-width: 60rem;
    width: 90%;
    position: relative;
    z-index: 20;
    border: 0.1rem solid var(--color-neutral);
}

.card__heading {
    color: var(--color-primary);
    font-size: var(--font-size-h3);
    margin-bottom: var(--space-4);
    text-align: center;
    font-weight: bold;
    font-family: var(--font-sans);
}

.card__sign-in__card-description {
    color: var(--color-foreground);
    font-size: var(--font-size-p);
    line-height: 160%;
    margin-bottom: var(--space-6);
    text-align: center;
    font-family: var(--font-alt);
}

.card__signup {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: var(--font-size-h6);
    gap: var(--space-4);
    background: var(--color-background);
    border: 0.2rem solid var(--color-neutral);
    border-radius: var(--radius-m);
    padding: var(--space-4) var(--space-6);
    color: var(--color-foreground);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
    text-decoration: none;
    font-family: var(--font-sans);
}

.card__signup:hover {
    background: var(--color-light-bg);
    border-color: var(--color-primary);
    transform: translateY(-0.2rem);
    box-shadow: var(--elevation-1);
}

.card__signup img {
    width: 2.4rem;
    height: 2.4rem;
}

.card__signup a {
    color: inherit;
    text-decoration: none;
    font-weight: inherit;
}

/* Large Desktop (1440px and up) */
@media (min-width: 1440px) {
    .hero-container__main {
        padding: var(--space-20) 0 var(--space-32) 0;
    }
    
    .card__sign-in {
        max-width: 65rem;
    }
}

/* Desktop (1024px to 1440px) */
@media (max-width: 1440px) {
    
    .hero__main-typing-banner {
        font-size: var(--font-size-h2);
    }
    
    .card__sign-in {
        max-width: 55rem;
    }
}

/* Tablet (768px to 1024px) */
@media (max-width: 1024px) {
    .hero-container__main {
        clip-path: ellipse(140% 100% at top);
    }
    
    .hero__main-typing-banner {
        font-size: var(--font-size-h3);
        margin-bottom: var(--space-8);
        flex-direction: column;
    }
    
    .card__sign-in {
        max-width: 50rem;
        padding: var(--space-6);
        width: 85%;
    }
    
    .card__heading {
        font-size: var(--font-size-h4);
    }
    
    .blob-1,
    .blob-2,
    .blob-3,
    .blob-4 {
        filter: blur(6rem);
        opacity: 0.5;
    }
}

/* Small Tablet (481px to 767px) */
@media (max-width: 768px) {
    .hero-container__main {
        clip-path: ellipse(160% 100% at top);
    }
    
    .hero__main-typing-banner {
        font-size: var(--font-size-h4);
        margin-bottom: var(--space-6);
        flex-direction: column;
        gap: 0;
    }
    
    .card__sign-in {
        padding: var(--space-5);
        width: 90%;
        max-width: 40rem;
    }
    
    .card__heading {
        font-size: var(--font-size-h5);
        margin-bottom: var(--space-3);
    }
    
    .card__sign-in__card-description {
        font-size: 1.4rem;
        margin-bottom: var(--space-5);
    }
    
    .card__signup {
        padding: var(--space-3) var(--space-5);
        font-size: var(--font-size-p);
    }
    
    .blob-1,
    .blob-2,
    .blob-3,
    .blob-4 {
        filter: blur(4rem);
        opacity: 0.4;
    }
}

/* Phone (480px and down) */
@media (max-width: 480px) {
    .hero-container__main {
        padding: var(--space-8) 0 var(--space-16) 0;
        clip-path: ellipse(180% 100% at top);
    }
    
    .hero__main-typing-banner {
        font-size: var(--font-size-h4);
        margin-bottom: var(--space-6);
        flex-direction: column;
        gap: 0;
        padding: 0 var(--space-4);
    }
    
    .card__sign-in {
        padding: var(--space-4);
        width: 90%;
        margin: 0 auto;
        max-width: none;
    }
    
    .card__heading {
        font-size: var(--font-size-h6);
        margin-bottom: var(--space-3);
    }
    
    .card__sign-in__card-description {
        font-size: 1.4rem;
        margin-bottom: var(--space-4);
        line-height: 150%;
    }
    
    .card__signup {
        padding: var(--space-3) var(--space-4);
        font-size: 1.4rem;
        gap: var(--space-3);
    }
    
    .card__signup img {
        width: 2rem;
        height: 2rem;
    }
    
    .blob-1,
    .blob-2,
    .blob-3,
    .blob-4 {
        filter: blur(3rem);
        opacity: 0.3;
    }
    
    /* Reduce blob sizes on mobile */
    .blob-1 { width: 40rem; height: 40rem; }
    .blob-2 { width: 35rem; height: 35rem; }
    .blob-3 { width: 45rem; height: 45rem; }
    .blob-4 { width: 38rem; height: 38rem; }
}

/* Very Small Phones (360px and down) */
@media (max-width: 360px) {
    .hero-container__main {
        padding: var(--space-6) 0 var(--space-12) 0;
    }
    
    .hero__main-typing-banner {
        margin-bottom: var(--space-4);
    }
    
    .card__sign-in {
        padding: var(--space-3);
        width: 95%;
    }
    
    .card__sign-in__card-description {
        font-size: 1.3rem;
    }
    
    .card__signup {
        padding: var(--space-2) var(--space-3);
        font-size: 1.3rem;
    }
}
</style>