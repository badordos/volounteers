<template>
    <transition name="fade">
        <section class="personal-wrap" v-if="active">
            <div class="personal-wrap__inner">

                <p>
                    On our website we use cookies to collect technical information.
                    In particular, for the personalized work of the site, we process the IP address of the region of your location.
                </p>
                <button type="button" class="btn btn--bold reverse" @click="checkCookies">OK</button>

            </div>
        </section>
    </transition>
</template>

<script>
    export default {
        data() {
            return {
                active: true,
            }
        },

        methods: {
            checkCookies() {
                this.active = false;
                localStorage.setItem('politic_cookie', 'true');
            }
        },

        created() {
            if(localStorage.getItem('politic_cookie') === "true"){
                this.active = false;
            }
        }
    }
</script>
