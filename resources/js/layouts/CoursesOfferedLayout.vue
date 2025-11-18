<template>
    <section class="courses-wrapper center">
        <div class="courses-container">
            <h3 class="courses-offered">COURSES OFFERED</h3>
            <div class="courses__item-container">
                <div class="courses__item">
                    <div class="courses__group">
                        <div class="courses__card-icon__bg">
                            <div class="courses__card-icon">
                                <img src="../../../storage/app/public/images/nurse-line.svg" alt="Icon for Bachelor of Science in Nursing">
                            </div>
                        </div>
                    </div>
                    <div class="courses__group">
                        <div class="course__title">
                            Bachelor of Science in Nursing
                        </div>
                    </div>
                </div>
                <div class="courses__item">
                    <div class="courses__group">
                        <div class="courses__card-icon__bg">
                            <div class="courses__card-icon"> 
                                <img src="../../../storage/app/public/images/lungs-fill.svg" alt="Icon for Bachelor of Science in Radiologic Technology">
                            </div>
                        </div>
                    </div>
                    <div class="courses__group">
                        <div class="course__title">
                            Bachelor of Science in Radiologic Technology
                        </div>
                    </div>
                </div>
                <div class="courses__item">
                    <div class="courses__group">
                        <div class="courses__card-icon__bg">
                            <div class="courses__card-icon"><img src="../../../storage/app/public/images/empathize-line.svg" alt="Icon for Diploma in Midwifery">
                            </div>
                        </div>
                    </div>
                <div class="courses__group">
                    <div class="course__title">
                        Diploma in Midwifery
                    </div>
                </div>
            </div>
            </div>
        </div>
     
    </section>
</template>

<script lang="ts">
export default {
    name: "CoursesOfferedLayout"
}
</script>

<style scoped>
/* COURSES LAYOUT */
.courses-wrapper {
    justify-content: center;
    color: var(--light-bg);
    padding: 10rem 0;
}
.courses-container {
    max-width: 111em;
    width: 100%;
    display: flex;
    align-items: center;
    flex-direction: column;
    justify-content: space-between;
}
.courses__item-container {
    display: flex;
    align-items: center;
    gap: 9rem;
    
}
.courses-offered {
    animation: appear linear;
    animation-timeline: view();
    animation-range: entry 0% cover 40%;
}
.card__info-description {
    padding: 1rem 0;
}
.courses__item {
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    border-radius: 2rem;
    height: 20rem;
    width: 31rem;
    position: relative;
    isolation: isolate;
    flex-direction: column;
    margin-top: 1rem;
    animation: appear linear;
    animation-timeline: view();
    animation-range: entry 0% cover 20%;
}

.courses__item::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: var(--green-gradient-main-bg);
    border-radius: 2rem;
    opacity: 0.6;
    transition: opacity 0.3s ease;
    z-index: -1;
}

.courses__item:hover::before {
    opacity: 1;
}

.courses__group {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}
.courses__card-icon__bg {
    background: var(--background);
    height: 4.8rem;
    width: 4.8rem;
    border-radius: 1rem;
    box-shadow: var(--box-shadow);
}
.courses__card-icon img {
    width: 4rem;
    height: 4rem;
    position: absolute;
    z-index: 2;
    transform: translate(10%, 10%);
}

.course__title {
    padding-top: 2rem;
    width: 80%;
    font-size: var(--font-h6);
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

@media (max-width: 768px) {
    .courses__item-container {
        flex-direction: column;
        gap: 1rem;
    }
    .courses__item{
        width: 100%;
    }
}

</style>