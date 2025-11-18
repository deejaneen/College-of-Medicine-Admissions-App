<template>
    <section class="reqs-and-quals-wrapper" id="qualifications">
        <div class="qualifications-container">
            <div class="qualifications-card">
                <div class="qualifications-card__info">
                    <div class="card__info-header">
                        <div class="bar"></div>
                        <h3>QUALIFICATIONS</h3>
                    </div>
                    <div class="card__info-description">
                        <ul class="card__info-list">
                            <li class="card__info-list-item" v-for="(qualification, index) in qualifications" :key="index">
                                <p>
                                    {{ qualification }}
                                </p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="reqs-and-quals__card-image-container">
                    <div class="reqs-and-quals__card-image-1">
                        <img src="../../../storage/app/public/images/CAMAppCardPhoto2.jpg" alt="CAM App drawing of a photo of 2 medical employees hanging out and being happy">
                    </div>
                </div>
            </div>
        </div>
        <div class="requirements-container" id="requiredDocuments">
            <div class="requirements-card">
                <div class="reqs-and-quals__card-image-container">
                    <div class="reqs-and-quals__card-image-2">
                        <img src="../../../storage/app/public/images/CAMAppCardPhoto4.jpg" alt="CAM App drawing of a photo of 2 medical employees hanging out and being happy">
                    </div>
                </div>
                <div class="requirements-card__info">
                    <div class="card__info-header">
                        <div class="bar"></div>
                        <h3>REQUIRED DOCUMENTS</h3>
                    </div>
                    <div class="card__info-description">
                        <ul class="card__info-list">
                            <li class="card__info-list-item" v-for="(requirement, index) in requirements" :key="index">
                                <p>
                                    {{ requirement }}
                                </p>
                            </li>
                        </ul>
                    </div>
                </div>
               
            </div>
        </div>
    </section>
</template>

<script lang="ts">
export default {
    name: 'RequirementsAndQualificationsLayout',
}
</script>
<script lang="ts" setup>
 const qualifications = [
        "Natural-born Filipino citizen",
        "A General Weighted Average (GWA) of 2.5 or higher, with no failing grades",
        "Completion of at least 3-unit course credits in each of the following subjects: Zoology, Chemistry, or Research",
        "A minimum score of 65 in the National Medical Admission Test (NMAT)",
        "No prior record of dropping out from any medical school",
        "Physically and mentally fit to pursue medical studies"
    ];
const requirements = [
    "Transcript of Records (TOR)",
    "National Medical Admission Test (NMAT) results",
    "Parent's proof of income",
    "Certificate of Residency from the baranggay", 
    "Certificate of General Weighted Average (GWA) from the Registrar's Office",
];
</script>
<style scoped>
.reqs-and-quals-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    transform: translateZ(0);
    position: relative;
    z-index: 100;
    gap: var(--space-8);
    width: 100%;
    padding: var(--space-10) var(--space-6);
    background: var(--gradient-primary);
}

.requirements-container,
.qualifications-container {
    width: 100%;
    max-width: 111em;
    display: flex;
    align-items: center;
    justify-content: center;
}

.reqs-and-quals__card-image-container {
    height: 100%;
    width: 40%;
}

.requirements-card,
.qualifications-card {
    min-height: 60rem;
    background: var(--color-background);
    border-radius: var(--radius-l);
    display: flex;
    justify-content: center;
    align-items: center;
    width: 90%;
    max-width: 100rem;
    padding: var(--space-8);
    gap: var(--space-10);
    animation: appear linear;
    animation-timeline: view();
    animation-range: entry 0% cover 20%;
    box-shadow: var(--elevation-2);
    border: 0.1rem solid var(--color-neutral);
}

.requirements-card__info,
.qualifications-card__info {
    flex-direction: column;
    display: flex;
    flex: 2 0 0%;
    height: 100%;
}

.card__info-header {
    font-weight: bold;
    font-size: var(--font-size-h4);
    color: var(--color-foreground);
    margin-bottom: var(--space-6);
    font-family: var(--font-sans);
    text-align: left;
    line-height: 120%;
}

.card__info-list {
    width: 100%;
    gap: var(--space-4);
    display: flex;
    align-items: center;
    flex-direction: column;
}

.card__info-list-item {
    background: var(--color-background);
    border-radius: var(--radius-m);
    box-shadow: var(--elevation-1);
    min-height: 5rem;
    width: 100%;
    display: flex;
    padding: var(--space-4) var(--space-6);
    align-items: center;
    position: relative;
    overflow: hidden;
    border-left: 0.4rem solid var(--color-primary);
    transition: all 0.3s ease;
    font-family: var(--font-alt);
    font-size: var(--font-size-p);
    color: var(--color-foreground);
    line-height: 150%;
}

.card__info-list-item:hover {
    transform: translateY(-0.3rem);
    box-shadow: var(--elevation-2);
    border-left-color: var(--color-secondary);
}

.card__info-list-item p {
    margin: 0;
    line-height: inherit;
}

.bar {
    background: var(--gradient-primary);
    height: 0.3rem;
    margin-bottom: var(--space-2);
    width: 4rem;
    border-radius: var(--radius-s);
}

.reqs-and-quals__card-image-1,
.reqs-and-quals__card-image-2 {
    height: 100%;
    box-shadow: var(--elevation-2);
    border-radius: var(--radius-l);
    display: flex;
    justify-content: center;
    align-items: center;
    flex: 1 0 0%;
    overflow: hidden;
    border: 0.1rem solid var(--color-neutral);
}

.reqs-and-quals__card-image-1 img,
.reqs-and-quals__card-image-2 img {
    border-radius: var(--radius-l);
    height: 100%;
    width: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.reqs-and-quals__card-image-1:hover img,
.reqs-and-quals__card-image-2:hover img {
    transform: scale(1.05);
}

@keyframes appear {
    from {
        opacity: 0;
        scale: 0.8;
        transform: translateY(-5rem);
    }
    to {
        opacity: 1;
        scale: 1;
        transform: translateY(0rem);
    }
}

/* Large Desktop (1440px and up) */
@media (min-width: 1440px) {
    .requirements-card,
    .qualifications-card {
        min-height: 65rem;
        padding: var(--space-8);
        gap: var(--space-10);
        width: 85%;
        max-width: 110rem;
    }
    
    .card__info-list-item {
        padding: var(--space-5) var(--space-8);
        min-height: 5.5rem;
        font-size: 1.7rem;
    }
    
    .card__info-header {
        font-size: var(--font-size-h3);
    }
}

/* Desktop (1024px to 1439px) */
@media (max-width: 1439px) and (min-width: 1024px) {
    .requirements-card,
    .qualifications-card {
        min-height: 60rem;
        padding: var(--space-7);
        gap: var(--space-8);
        width: 88%;
        max-width: 95rem;
    }
    
    .card__info-list-item {
        padding: var(--space-4) var(--space-7);
        min-height: 5rem;
        font-size: var(--font-size-p);
    }
    
    .card__info-header {
        font-size: var(--font-size-h4);
    }
}

/* Tablet (768px to 1023px) */
@media (max-width: 1023px) and (min-width: 768px) {
    .reqs-and-quals-wrapper {
        padding: var(--space-8) var(--space-4);
        gap: var(--space-6);
    }
    
    .requirements-card,
    .qualifications-card {
        min-height: 55rem;
        padding: var(--space-6);
        gap: var(--space-6);
        width: 92%;
        max-width: 85rem;
    }
    
    .reqs-and-quals__card-image-container {
        width: 35%;
    }
    
    .card__info-list-item {
        padding: var(--space-4) var(--space-6);
        min-height: 4.5rem;
        font-size: 1.5rem;
        line-height: 155%; 
    }
    
    .card__info-header {
        font-size: var(--font-size-h5);
        margin-bottom: var(--space-5);
    }
    
    .card__info-list {
        gap: var(--space-4); 
    }
}

/* Small Tablet (768px and down) - Remove images */
@media (max-width: 768px) {
    .reqs-and-quals__card-image-container {
        display: none;
    }
    
    .requirements-card,
    .qualifications-card {
        min-height: auto;
        padding: var(--space-6); 
        gap: 0;
        width: 90%;
        max-width: 60rem;
    }
    
    .card__info-list-item {
        padding: var(--space-4) var(--space-6); 
        min-height: 4.5rem; 
        font-size: 1.4rem;
        line-height: 150%; 
    }
    
    .card__info-header {
        font-size: var(--font-size-h5);
        margin-bottom: var(--space-5); 
        text-align: left;
    }
    
    .bar {
        margin: 0 0 var(--space-3) 0; 
    }
    
    .card__info-list {
        gap: var(--space-4); 
    }
}

/* Phone (480px and down) */
@media (max-width: 480px) {
    h3 {
        font-size: var(--font-size-h6);
    }
    .reqs-and-quals-wrapper {
        padding: var(--space-7) var(--space-4); 
        gap: var(--space-5); 
    }
    
    .requirements-card,
    .qualifications-card {
        padding: var(--space-5);
        min-height: auto;
        width: 95%;
        max-width: 45rem;
    }
    
    .card__info-list-item {
        padding: var(--space-4) var(--space-5); 
        min-height: 4rem;
        font-size: 1.3rem;
        line-height: 145%; 
        border-radius: var(--radius-m);
    }
    
    .card__info-header {
        font-size: var(--font-size-h6);
        margin-bottom: var(--space-4); 
        text-align: left;
        line-height: 135%; 
    }
    
    .bar {
        margin: 0 0 var(--space-2) 0; 
        width: 3.5rem;
    }
    
    .card__info-list {
        gap: var(--space-3); 
    }
}

/* Very Small Phones (360px and down) */
@media (max-width: 360px) {
    .reqs-and-quals-wrapper {
        padding: var(--space-5) var(--space-2); 
        gap: var(--space-4); 
    }
    
    .requirements-card,
    .qualifications-card {
        padding: var(--space-4); 
        width: 98%;
        max-width: 40rem;
    }
    
    .card__info-list-item {
        padding: var(--space-3) var(--space-4); 
        min-height: 3.5rem; 
        font-size: 1.2rem;
        line-height: 140%; 
        border-radius: var(--radius-s);
    }
    
    .card__info-header {
        font-size: 1.8rem;
        margin-bottom: var(--space-3); 
        text-align: left;
        line-height: 130%; 
    }
    
    .bar {
        margin: 0 0 var(--space-2) 0; 
        width: 3rem;
    }
    
    .card__info-list {
        gap: var(--space-3); 
    }
}
</style>