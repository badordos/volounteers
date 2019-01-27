<template>
    <swiper :options="swiperOption" ref="mySwiper">
        <slot></slot>
        <div class="swiper-scrollbar" slot="scrollbar"></div>
    </swiper>
</template>

<script>
    export default {
        data() {
            return {
                swiperOption: {
                    direction: 'horizontal',
                    slidesPerView: 'auto',
                    spaceBetween: 57,
                    autoHeight: true,
                    freeMode: true,
                    scrollbar: {
                        el: '.swiper-scrollbar',
                        draggable: true,
                        dragSize: 100
                    },
                    mousewheel: false,
                    shortSwipes: false
                }
            }
        },
        computed: {
            swiper() {
                return this.$refs.mySwiper.swiper
            }
        },
    }
</script>
