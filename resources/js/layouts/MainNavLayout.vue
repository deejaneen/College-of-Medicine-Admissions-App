<template>
    <section class="complete-nav">
        <div>
            <nav class="navbar">
                <div class="navbar-container">
                    <ul class="navbar__nav">
                        <li class="navbar__logo">
                            <Link href="/">
                                <img src="../../../storage/app/public/images/CAMLogo.png" 
                                    alt="CAM Logo" 
                                    class="logo-image">
                            </Link>
                        </li>
                        <li class="navbar__item-qualifications">
                            <Link href="#qualifications">
                                <span class="underline-animation">QUALIFICATIONS</span>
                            </Link>
                        </li>
                        <li class="navbar__item-required-documents">
                            <Link href="#requiredDocuments">
                                <span class="underline-animation">REQUIRED DOCUMENTS</span>
                            </Link>
                        </li>
                    </ul>
                    <ul class="navbar__auth">
                        <li class="navbar__button-sign-in"> 
                            <a :href="route('login.google')" class="underline-animation">
                                    SIGN IN
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </section>
  

</template>

<script lang="ts" setup>
import '../../css/app.css';

</script>

<script lang="ts">
export default {
    name: 'MainNavLayout',
}
</script>
<style scoped>
.navbar {
    display: flex;
    border-bottom: 0.2rem solid var(--color-neutral);
    padding: var(--space-2) var(--space-3);
    font-size: var(--font-size-p);
    letter-spacing: +0.5%;
    line-height: 150%;
    font-weight: bold;
    color: var(--color-foreground);
    background-color: var(--color-background);
    position: relative;
    z-index: 10;
}

.navbar-container {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-family: var(--font-sans);
}

.navbar__nav {
    display: flex;
    align-items: center;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: var(--space-2);
}

.navbar__nav li {
    padding: 0;
}

.navbar__logo {
    display: flex;
    align-items: center;
}

.logo-image {
    width: 3rem;
    height: 3rem;
    flex-shrink: 0;
    object-fit: contain;
}

/* Hide navigation items on mobile */
.navbar__item-qualifications,
.navbar__item-required-documents {
    display: none;
}

.navbar__auth {
    display: flex;
    align-items: center;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: var(--space-3);
}

.navbar__button-sign-in {
    margin: 0;
}
.navbar__button-sign-in .underline-animation {
    display: inline-block;
}

.navbar__button-sign-in a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: var(--space-2) 0;
    border-radius: var(--radius-s);
    transition: all 0.3s ease;
    text-decoration: none;
    font-weight: bold;
}

.navbar__button-sign-in a:hover {
    transform: translateY(-0.1rem);
}

.navbar__button-sign-in b {
    color: var(--color-primary);
}

/* Mobile menu button (hidden by default) */
.navbar__mobile-toggle {
    display: none;
    flex-direction: column;
    justify-content: space-between;
    width: 2rem;
    height: 1.5rem;
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 0;
}

.navbar__mobile-toggle span {
    display: block;
    height: 0.2rem;
    width: 100%;
    background-color: var(--color-foreground);
    border-radius: var(--radius-s);
    transition: all 0.3s ease;
}

/* Small devices (phones, 481px and up) */
@media (min-width: 481px) {
    .navbar {
        padding: var(--space-2) var(--space-4);
        font-size: var(--font-size-p);
    }
    
    .navbar__nav {
        gap: var(--space-3);
    }
    
    .logo-image {
        width: 3.5rem;
        height: 3.5rem;
    }
    
    .navbar__button-sign-in a {
        padding: var(--space-2) var(--space-5);
    }
}

/* Medium devices (tablets, 769px and up) */
@media (min-width: 769px) {
    .navbar {
        padding: var(--space-2) var(--space-8);
        font-size: var(--font-size-h6);
    }
    
    .navbar__nav {
        gap: var(--space-4);
    }
    
    .navbar__nav li {
        padding: 0 var(--space-2);
    }
    
    .logo-image {
        width: 4rem;
        height: 4rem;
    }
    
    /* Show navigation items on tablet and up */
    .navbar__item-qualifications,
    .navbar__item-required-documents {
        display: block;
    }
    
    .navbar__button-sign-in a {
        padding: var(--space-2) var(--space-6);
    }
}

/* Large devices (desktops, 1025px and up) */
@media (min-width: 1025px) {
    .navbar {
        padding: var(--space-2) var(--space-16);
    }
    
    .navbar__nav {
        gap: var(--space-5);
    }
    
    .navbar__nav li {
        padding: 0 var(--space-4);
    }
    
    .navbar__button-sign-in a {
        padding: var(--space-2) var(--space-8);
    }
}

/* Extra large devices (large desktops, 1441px and up) */
@media (min-width: 1441px) {
    .navbar {
        padding: var(--space-2) var(--space-32);
    }
    
    .navbar__nav {
        gap: var(--space-6);
    }
}

/* Mobile menu styles for small screens */
@media (max-width: 768px) {
    .navbar__mobile-toggle {
        display: flex;
    }
    
    /* Optional: Add mobile menu functionality */
    .navbar__nav--mobile {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: var(--color-background);
        border-bottom: 0.2rem solid var(--color-neutral);
        flex-direction: column;
        padding: var(--space-4);
        gap: var(--space-3);
        display: none;
    }
    
    .navbar__nav--mobile.active {
        display: flex;
    }
    
    .navbar__nav--mobile li {
        width: 100%;
        text-align: center;
        padding: var(--space-3) 0;
    }
    
    .navbar__item-qualifications,
    .navbar__item-required-documents {
        display: none;
    }
}

/* Enhanced touch targets for mobile */
@media (max-width: 480px) {
    .navbar__button-sign-in a {
        min-height: 3rem;
        min-width: 8rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .navbar__nav {
        gap: var(--space-3);
    }
}

/* High contrast and accessibility support */
@media (prefers-reduced-motion: reduce) {
    .navbar__button-sign-in a,
    .underline-animation {
        transition: none;
    }
}

</style>