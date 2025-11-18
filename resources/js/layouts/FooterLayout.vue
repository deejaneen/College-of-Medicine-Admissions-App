<template>
    <section class="footer-container center">
        <div class="footer__main">
            <div class="footer-description">
                <div class="bar"></div>
                <h4> <img src="../../../storage/app/public/images/CAMLogo.png" alt="College of Allied Medicine Logo"></h4>
                <p>
                    Southern Luzon State University (SLSU) is the premier higher education institution in Quezon Province in the Philippines. Its main campus is in Lucban.
                </p>
               
            </div>
            <div class="footer__quicklinks">
                <div class="bar"></div>
                <h4>QUICKLINKS</h4>
                <ul>
                    <li><Link class="underline-animation">University Directory</Link></li>
                    <li><Link class="underline-animation">Transparency Seal</Link></li>
                    <li><Link class="underline-animation">Bids and Awards</Link></li>
                    <li><Link class="underline-animation">Citizen's Charter</Link></li>
                </ul>
                <ul class="footer__quicklinks-social">
                    <li><Link class="underline-animation">Facebook</Link></li>
                </ul>
            </div>
            <div class="footer__other-info-and-links">
                <div class="bar"></div>
                <h4>OTHER INFO</h4>
                <ul>
                    <li class="university-info">
                        <b>
                            Southern Luzon State University
                        </b>
                        <i>
                            Quezon Avenue, Lucban, Philippines
                        </i>
                    </li>
                    <li>Maintained by: MIS-ICT</li>
                </ul>
            </div>
        </div>
        <div class="footer__copyright">
            <p>© 2025 Southern Luzon State University | Lucban, Quezon, Philippines</p>
        </div>
    </section>
</template>

<script lang="ts" setup>
import { Link } from '@inertiajs/vue3';
</script>
<script lang="ts">
export default {
    name: 'FooterLayout'
}
</script>

<style scoped>
.footer-container {
    flex-direction: column;
    background: var(--color-background);
    color: var(--color-foreground);
    padding: var(--space-8);
    font-family: var(--font-alt);
    border-top: 0.2rem solid var(--color-neutral);
}

.footer__main {
    width: 100%;
    max-width: 111em;
    display: flex;
    justify-content: center;
    text-align: left;
    gap: var(--space-20);
}

.footer__main div h4 {
    padding-bottom: var(--space-10);
    font-size: var(--font-size-h5);
    font-weight: bold;
    font-family: var(--font-sans);
    color: var(--color-primary);
}

.footer-description,
.footer__quicklinks,
.footer__other-info-and-links {
    flex: 1 0 0%;
}

.footer-description p {
    line-height: 160%;
    padding: var(--space-1) 0;
    font-size: var(--font-size-p);
    color: var(--color-foreground);
}

.footer-description img {
    width: calc(3.8rem * 1.32);
    height: calc(3.8rem * 1.32);
}

.footer__quicklinks ul,
.footer__other-info-and-links ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer__quicklinks ul li {
    cursor: pointer;
    margin-bottom: var(--space-3);
}

.footer__quicklinks ul li,
.footer__other-info-and-links ul li {
    font-size: var(--font-size-p);
    font-family: var(--font-alt);
}

.footer__quicklinks-social {
    margin-top: var(--space-6);
    padding-top: var(--space-4);
    border-top: 0.1rem solid var(--color-neutral);
}

.underline-animation {
    color: var(--color-foreground);
    text-decoration: none;
    position: relative;
    transition: color 0.3s ease;
}

.underline-animation:hover {
    color: var(--color-primary);
}

.underline-animation::after {
    content: '';
    position: absolute;
    width: 0;
    height: 0.2rem;
    bottom: -0.2rem;
    left: 0;
    background: var(--color-primary);
    transition: width 0.3s ease;
}

.underline-animation:hover::after {
    width: 100%;
}

.footer__copyright {
    padding: var(--space-8) 0 0 0;
    border-top: 0.1rem solid var(--color-neutral);
    margin-top: var(--space-6);
    text-align: center;
    font-size: var(--font-size-p);
    color: var(--color-foreground);
}

.university-info b,
.university-info i {
    display: block;
}

.university-info b {
    font-weight: bold;
    margin-bottom: var(--space-2);
    color: var(--color-primary);
}

.university-info i {
    font-style: italic;
    color: var(--color-foreground);
    margin-bottom: var(--space-4);
}

.university-info {
    padding-bottom: var(--space-5);
}

.bar {
    background: var(--color-primary);
    height: 0.3rem;
    margin-bottom: var(--space-2);
    width: 4rem;
    border-radius: var(--radius-s);
}

/* Tablet (768px and down) */
@media (max-width: 768px) {
    .footer__main {
        flex-direction: column;
        gap: var(--space-8);
    }
    
    .footer-container {
        padding: var(--space-6);
    }
    
    .footer__main div h4 {
        padding-bottom: var(--space-6);
        font-size: var(--font-size-h6);
    }
    
    .footer__copyright {
        padding: var(--space-6) 0 0 0;
        margin-top: var(--space-4);
    }
}

/* Phone (480px and down) */
@media (max-width: 480px) {
    .footer-container {
        padding: var(--space-3);
    }
    
    .footer__main {
        gap: var(--space-6);
    }
    
    .footer__main div h4 {
        padding-bottom: var(--space-4);
    }
    
    .footer__copyright {
        padding: var(--space-4) 0 0 0;
        font-size: 1.4rem;
    }
    
    .footer-description p,
    .footer__quicklinks ul li,
    .footer__other-info-and-links ul li {
        font-size: 1.4rem;
    }
}
</style>