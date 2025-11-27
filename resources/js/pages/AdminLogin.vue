<template>
    <div class="page-container">
    <div class="login-container">
        <div class="login-header">
            <h1>SLSU Admin Login</h1>
        </div>
        
        <div class="login-card">
            <div v-if="errorMessage" class="error-message">
                {{ errorMessage }}
            </div>
            
            <div v-if="successMessage" class="success-message">
                {{ successMessage }}
            </div>
            
            <form @submit.prevent="handleLogin">
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="form-input"
                        placeholder="admin@slsu.edu.ph"
                        required
                    />
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="form-input"
                        placeholder="Enter your password"
                        required
                    />
                </div>

                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" v-model="form.rememberMe" />
                        Remember me
                    </label>
                    <a href="#" class="forgot-password">Forgot password?</a>
                </div>

                <button 
                    type="submit" 
                    class="login-button"
                    :disabled="loading"
                >
                    <span v-if="loading">Signing in...</span>
                    <span v-else>Sign In</span>
                </button>
            </form>
        </div>
        
        <div class="brand-footer">
            <p>South Luzon State University <strong>Admin Portal</strong></p>
        </div>
    </div>
    </div>
    
</template>
<script lang="ts" setup>
import { ref } from 'vue';
import axios, { AxiosError } from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();

// Type definitions
interface LoginForm {
    email: string;
    password: string;
    rememberMe: boolean;
}

const form = ref<LoginForm>({
    email: '',
    password: '',
    rememberMe: false
});

const loading = ref<boolean>(false);
const errorMessage = ref<string>('');
const successMessage = ref<string>('');

const handleLogin = async (): Promise<void> => {
    loading.value = true;
    errorMessage.value = '';
    successMessage.value = '';

    try {
        axios.defaults.withCredentials = true;

        // CSRF cookie (required by Laravel Sanctum)
        await axios.get('/sanctum/csrf-cookie');

        // Attempt login
        await axios.post('/login', {
            email: form.value.email,
            password: form.value.password,
            remember: form.value.rememberMe
        });

        successMessage.value = "Login successful! Redirecting...";

        // Redirect to admin dashboard
        setTimeout(() => {
            router.push('/admin/dashboard');
        }, 800);

    } catch (err) {
        const error = err as AxiosError;

        if (error.response?.status === 422) {
            errorMessage.value = "Invalid email or password.";
        } else {
            errorMessage.value = "Something went wrong. Please try again.";
        }
    } finally {
        loading.value = false;
    }
};
</script>

<style scoped>
    .page-container{
        background: var(--color-light-bg);
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: var(--space-4);
    }
    .login-container {
        width: 100%;
        max-width: 48rem;
    }

    .login-header {
        text-align: center;
        margin-bottom: var(--space-8);
    }

    .login-header h1 {
        color: var(--color-primary);
        font-size: var(--font-size-h4);
        margin-bottom: var(--space-2);
        font-weight: 600;
    }

    .login-header p {
        color: var(--color-foreground);
        opacity: 0.7;
        font-size: var(--font-size-p);
        font-family: var(--font-alt);
    }

    .login-card {
        background-color: var(--color-background);
        border-radius: var(--radius-l);
        box-shadow: var(--elevation-3);
        padding: var(--space-10) var(--space-8);
        border-top: var(--space-1) solid var(--color-secondary);
    }

    .form-group {
        margin-bottom: var(--space-5);
    }

    .form-label {
        display: block;
        margin-bottom: var(--space-2);
        color: var(--color-foreground);
        font-weight: 500;
        font-size: var(--font-size-p);
    }

    .form-input {
        width: 100%;
        padding: var(--space-4) var(--space-5);
        border: 0.1rem solid var(--color-neutral);
        border-radius: var(--radius-m);
        font-size: var(--font-size-p);
        transition: all 0.3s;
        font-family: var(--font-alt);
    }

    .form-input:focus {
        outline: none;
        border-color: var(--color-primary-light);
        box-shadow: 0 0 0 0.3rem var(--color-bright-green-25);
    }

    .form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-6);
    }

    .checkbox-label {
        display: flex;
        align-items: center;
        cursor: pointer;
        color: var(--color-foreground);
        font-size: var(--font-size-p);
        font-family: var(--font-alt);
    }

    .checkbox-label input {
        margin-right: var(--space-2);
    }

    .forgot-password {
        color: var(--color-primary);
        text-decoration: none;
        font-size: var(--font-size-p);
        font-family: var(--font-alt);
    }

    .forgot-password:hover {
        text-decoration: underline;
        color: var(--color-primary-light);
    }

    .login-button {
        width: 100%;
        padding: var(--space-4);
        background: var(--color-primary);
        color: white;
        border: none;
        border-radius: var(--radius-m);
        font-size: var(--font-size-p);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        margin-bottom: var(--space-5);
        font-family: var(--font-sans);
    }

    .login-button:hover {
        transform: translateY(-0.2rem);
        box-shadow: var(--elevation-2);
    }

    .login-button:disabled {
        background: var(--color-neutral);
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .error-message {
        color: #e74c3c;
        background-color: #fdf0ed;
        padding: var(--space-3);
        border-radius: var(--radius-s);
        margin-bottom: var(--space-5);
        font-size: var(--font-size-p);
        text-align: center;
        border-left: var(--space-1) solid #e74c3c;
        font-family: var(--font-alt);
    }

    .success-message {
        color: var(--color-primary);
        background-color: var(--color-light-bg);
        padding: var(--space-3);
        border-radius: var(--radius-s);
        margin-bottom: var(--space-5);
        font-size: var(--font-size-p);
        text-align: center;
        border-left: var(--space-1) solid var(--color-primary-light);
        font-family: var(--font-alt);
    }

    .brand-footer {
        text-align: center;
        margin-top: var(--space-8);
        color: var(--color-foreground);
        opacity: 0.7;
        font-size: var(--font-size-p);
        font-family: var(--font-alt);
    }

    .brand-footer strong {
        color: var(--color-primary);
    }
</style>