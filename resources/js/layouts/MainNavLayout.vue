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
                            <Link href="#qualifications" class="center underline-animation primary">
                                    SIGN IN
                            </Link>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </section>
  

</template>

<script lang="ts" setup>
import { Link } from '@inertiajs/vue3';
import '../../css/app.css';

</script>

<script lang="ts">
export default {
    name: 'MainNavLayout',
  
}
</script>