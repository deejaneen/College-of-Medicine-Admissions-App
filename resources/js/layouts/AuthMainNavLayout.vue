<template>
  <section class="auth-nav">
    <div class="auth-nav-container">
      <div class="auth-navbar-container">

        <Link href="/" class="auth-navbar__logo">
          <img 
            src="../../../storage/app/public/images/CAMLogo.png"
            alt="CAM Logo"
            class="logo-image logo-image__padding"
          >
        </Link>

        <nav class="navbar__auth">
          <ul class="auth-navbar__nav" :class="{ active: isMenuOpen }">

            <li class="auth-navbar__item-home">
              <Link :href="route('dashboard')">
                <span class="underline-animation">HOME</span>
              </Link>
            </li>

            <li class="auth-navbar__item-application-form">
              <Link :href="route('application.form')">
                <span class="underline-animation">APPLICATION FORM</span>
              </Link>
            </li>

            <li class="navbar__button-log-out">
              <button @click="logout" class="center underline-animation primary">
                LOGOUT
              </button>
            </li>

          </ul>
        </nav>

        <div class="menu-toggle-container" id="navbarToggle">
          <button 
            class="menu-toggle" 
            :class="{ active: isMenuOpen }" 
            @click="toggleMenu"
          >
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
          </button>
        </div>

      </div>
    </div>
  </section>
</template>

<script lang="ts" setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const isMenuOpen = ref(false);

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value;
};

const logout = () => {
  Inertia.post(route('logout'));
}
</script>

<script lang="ts">
export default {
  name: "AuthMainNavLayout"
}
</script>

<style scoped>
.auth-nav {
  background: var(--color-background);
  color: var(--color-foreground);
  border-bottom: 0.2rem solid var(--color-neutral);
  position: sticky;
}

.auth-navbar {
  display: flex;
  align-items: center;
  padding: var(--space-2) var(--space-32);
  font-size: var(--font-size-h6);
  letter-spacing: +0.5%;
  font-family: var(--font-sans);
  font-weight: bold;
}

.auth-navbar-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
  padding: 0 var(--space-32);
}

.auth-navbar__nav {
  display: flex;
  align-items: center;
}

.auth-navbar__nav li {
  padding: 0 var(--space-5);
}

.menu-toggle-container {
  display: flex;
  justify-content: flex-end;
  display: none;
}

.menu-toggle {
  display: none;
  background: transparent;
  border: none;
  cursor: pointer;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.bar {
  display: block;
  height: .3rem;
  width: 100%;
  border-radius: var(--radius-s);
  margin: var(--space-2) auto;
  background: var(--color-foreground);
  transition: all 0.3s ease-in-out;
}

.navbar__auth {
  display: flex;
  font-size: var(--font-size-h6);
  letter-spacing: +0.5%;
  line-height: 150%;
  font-weight: bold;
  color: var(--color-foreground);
  background-color: var(--color-background);
  padding: var(--space-4) 0;
  white-space: nowrap;
}

.auth-navbar__nav li:last-child {
  padding-right: 0;
}

.navbar__button-log-out button{
  cursor: pointer;
}

@media (max-width: 640px) {
  .auth-navbar__nav li {
    font-size: 1.75rem;
  }
}

@media (max-width: 768px) {
  .auth-nav-container {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 var(--space-5);
  }

  .auth-navbar {
    padding: var(--space-2) var(--space-5);
  }

  .auth-navbar-container {
    padding: 0;
  }

  .auth-navbar__logo {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: var(--space-4) 0;
  }

  .menu-toggle-container {
    display: block;
    position: relative;
  }

  .menu-toggle {
    width: 3rem;
    height: 3rem;
    position: relative;
    z-index: 1000;
    display: block;
  }

  .auth-navbar__nav {
    position: absolute;
    top: 4.2rem;
    right: 0;
    height: 100dvh;
    width: fit-content;
    z-index: 999;
    background-color: var(--color-background);
    box-shadow: var(--elevation-1);
    flex-direction: column;
    align-items: flex-start;
    justify-content: flex-start;
    display: none;
    opacity: 0.9;
    backdrop-filter: blur(1rem);
    padding-top: var(--space-10);
    gap: var(--space-5);
    transform: translateY(-4rem);
    transition: width 2s ease-in-out;
  }

  .auth-navbar__nav.active {
    display: flex;
  }

  .auth-navbar__nav li {
    padding: 0 var(--space-5);
    transition: all 0.3s ease-in-out 0.1s;
    font-size: 2rem;
  }

  .menu-toggle.active .bar:nth-child(2) {
    opacity: 0;
  }

  .menu-toggle.active .bar:nth-child(1) {
    transform: translateY(0.8rem) rotate(45deg);
  }

  .menu-toggle.active .bar:nth-child(3) {
    transform: translateY(-0.8rem) rotate(-45deg);
  }
}

@media (max-width: 1024px) {
  .auth-navbar {
    padding: var(--space-2) var(--space-10);
  }
  
  .auth-navbar-container {
    padding: 0 var(--space-10);
  }
}

@media (max-width: 1280px) {
  .auth-navbar {
    padding: var(--space-2) var(--space-16);
  }
  
  .auth-navbar-container {
    padding: 0 var(--space-16);
  }
}
</style>
