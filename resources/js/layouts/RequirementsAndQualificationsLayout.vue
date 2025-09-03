<template>
    <section class="reqs-and-quals-wrapper" id="qualifications">
        <div class="reqs-and-quals-container">
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

                    <!-- <div >
                        <div >
                            <div class="bar"></div>
                            <div class="reqs-and-quals__card-header">
                                <h3 class="reqs-and-quals__card-title">
                                    QUALIFICATIONS
                                </h3>
                            </div>
                            <div class="reqs-and-quals__list-container">
                                <ul class="reqs-and-quals__list" >
                                    <li class="reqs-and-quals__list-item" v-for="(qualification, index) in qualifications" :key="index">
                                        <div class="side-emphasis"></div>
                                        <p>
                                            {{ qualification }}
                                        </p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                            <div class="reqs-and-quals__card-image-1">
                            <img src="../../../storage/app/public/images/CAMAppCardPhoto2.jpg" alt="CAM App drawing of a photo of 2 medical employees hanging out and being happy">
                        </div>
                    </div> -->
                </div>
                <div class="reqs-and-quals__card-image-1">
                    <img src="../../../storage/app/public/images/CAMAppCardPhoto2.jpg" alt="CAM App drawing of a photo of 2 medical employees hanging out and being happy">
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
// const requirements = [
//     "1. Transcript of Records (TOR)",
//     "2. National Medical Admission Test (NMAT) results",
//     "3. Parent's proof of income",
//     "4. Certificate of Residency from the baranggay", 
//     "5. Certificate of General Weighted Average (GWA) from the Registrar's Office",
// ];
</script>