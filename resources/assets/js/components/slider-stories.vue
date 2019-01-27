<template>
    <div class="stories-slider__right">
        <slot></slot>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                swiperTopRef: null,
                swiperThumbsRef: null,
                swiperTop: null,
            }
        },
        methods: {
            activateStoriesOutput() {
                this.swiperTop.active = true;

                this.swiperTop.playStory();
            },
        },
        mounted() {
            this.$nextTick(() => {
                this.swiperTop = this.$children[0];

                this.swiperTopRef = this.$children[0].$refs.swiperTop.swiper;
                this.swiperThumbsRef = this.$children[1].$refs.mySwiper.swiper;

                this.swiperTopRef.controller.control = this.swiperThumbsRef;
                this.swiperThumbsRef.controller.control = this.swiperTopRef;
            });
        },

        created() {
            this.$on('show-stories-output', () => {
                this.activateStoriesOutput();
            });
        }
    }
</script>
