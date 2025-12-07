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
              <Link :href="route('application.create')">
                <span class="underline-animation">APPLICATION FORM</span>
              </Link>
            </li>

           <li class="navbar__button-log-out">
              <!-- Changed from logout() to showLogoutConfirmation() -->
              <button @click="showLogoutConfirmation" class="center underline-animation primary">
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
    <!-- Logout Confirmation Modal -->
    <div v-if="showLogoutModal" class="modal-overlay">
      <div class="modal-content">
        <div class="modal-icon warning">
          <span class="material-icons">logout</span>
        </div>
        
        <h3 class="modal-title">Confirm Logout</h3>
        
        <div class="modal-message">
          <p>Are you sure you want to logout from your account?</p>
        </div>

        <div class="modal-actions">
          <button class="btn-secondary" @click="cancelLogout" :disabled="isLoggingOut">
            Cancel
          </button>
          <button class="btn-error" @click="logout" :disabled="isLoggingOut">
            <span v-if="isLoggingOut" class="spinner"></span>
            {{ isLoggingOut ? 'Logging out...' : 'Yes, Logout' }}
          </button>
        </div>
      </div>
    </div>
  </section>
</template>

<script lang="ts" setup>
import { Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const isMenuOpen = ref(false);
const showLogoutModal = ref(false);
const isLoggingOut = ref(false);

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value;
};

const showLogoutConfirmation = () => {
  showLogoutModal.value = true;
  // Close mobile menu if open
  if (isMenuOpen.value) {
    isMenuOpen.value = false;
  }
};

const logout = () => {
  isLoggingOut.value = true;
  showLogoutModal.value = false;
  Inertia.post(route('logout'));
};

const cancelLogout = () => {
  showLogoutModal.value = false;
};

// Load material icons if not already loaded
onMounted(() => {
  if (!document.querySelector('link[href*="material-icons"]')) {
    const link = document.createElement('link');
    link.href = 'https://fonts.googleapis.com/icon?family=Material+Icons';
    link.rel = 'stylesheet';
    document.head.appendChild(link);
  }
});
</script>

<script lang="ts">
export default {
  name: "AuthMainNavLayout"
}
</script>

<style scoped>
  /* --- MODAL STYLES --- */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
  backdrop-filter: blur(4px);
  font-family: var(--font-sans);
}

.modal-content {
  background: white;
  border-radius: 12px;
  padding: 2rem;
  max-width: 500px;
  width: 90%;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  animation: slideUp 0.3s ease;
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.modal-icon {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1.5rem;
  background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.modal-icon .material-icons {
  font-size: 2.5rem;
  color: white;
}

.modal-title {
  font-size: var(--font-size-h4);
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 1rem;
  text-align: center;
  font-family: var(--font-sans);
}

.modal-message {
  color: #4b5563;
  line-height: 1.6;
  margin-bottom: 2rem;
  text-align: center;
}

.modal-message p {
  margin-bottom: 1rem;
  font-size: var(--font-size-p);
}

.modal-actions {
  display: flex;
  justify-content: center;
  gap: 1rem;
  margin-top: 2rem;
}

.btn-primary, .btn-secondary, .btn-error {
  padding: 0.75rem 1.5rem;
  border-radius: 8px;
  font-weight: 500;
  cursor: pointer;
  border: none;
  transition: all 0.2s ease;
  min-width: 150px;
  font-size: var(--font-size-p);
  font-family: var(--font-sans);
}

.btn-primary {
  background: #2563eb;
  color: white;
}

.btn-secondary {
  background: white;
  color: #374151;
  border: 1px solid #d1d5db;
}

.btn-error {
  background: #ef4444;
  color: white;
}

.btn-primary:hover:not(:disabled),
.btn-error:hover:not(:disabled) {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-secondary:hover:not(:disabled) {
  background: #f9fafb;
  border-color: #9ca3af;
}

.btn-primary:disabled,
.btn-secondary:disabled,
.btn-error:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

/* Spinner for loading state */
.spinner {
  display: inline-block;
  width: 1rem;
  height: 1rem;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  border-top-color: white;
  animation: spin 1s ease-in-out infinite;
  margin-right: 0.5rem;
  vertical-align: middle;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

/* Responsive styles for modal */
@media (max-width: 640px) {
  .modal-content {
    padding: 1.5rem;
    margin: 1rem;
  }
  
  .modal-actions {
    flex-direction: column;
  }
  
  .btn-primary, .btn-secondary, .btn-error {
    width: 100%;
    min-width: unset;
    font-size: var(--font-size-p);
  }
  
  .modal-icon {
    width: 60px;
    height: 60px;
  }
  
  .modal-icon .material-icons {
    font-size: 2rem;
  }
  
  .modal-title {
    font-size: var(--font-size-h5);
  }
  
  .modal-message p {
    font-size: var(--font-size-p);
  }
}
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
