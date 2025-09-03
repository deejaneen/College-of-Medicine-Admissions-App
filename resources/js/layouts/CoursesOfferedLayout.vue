<template>
    <section class="courses-container">
        <h2 class="courses-offered">COURSES OFFERED</h2>
        <div class="courses__item-container">
            <div class="courses__item" v-for="(icon, index) in icons" :key="index">
                <div>
                    <div class="courses__card-icon__bg">
                        <div class="courses__card-icon">
                            <img src="{{ icons }}" alt="{{ altnames }}">
                        </div>
                    </div>
                </div>
                <div class="courses__group">
                    <p class="course__title">
                        {{ courses }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>

<script lang="ts">
const icons = [
   'Bachelor of Science in Nursing',
   'Bachelor of Science in Radiologic Technology',
   'Diploma in Midwifery'
]
const altnames = [
   'Icon for Bachelor of Science in Nursing',
   'Icon for Bachelor of Science in Radiologic Technology',
   'Icon for Diploma in Midwifery'
]
const courses = [
   '../../../storage/app/public/images/nurse-line.svg',
   '../../../storage/app/public/images/lungs-fill.svg',
   '../../../storage/app/public/images/empathize-line.svg'
]

</script>