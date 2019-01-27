<template>
    <swiper :options="swiperOption" ref="mySwiper" class="vote__slider">
        <slot></slot>
        <div class="swiper-scrollbar" slot="scrollbar"></div>
    </swiper>
</template>

<script>
    import { swiper } from 'vue-awesome-swiper';
    import { EventBus } from './state-of-events';

    export default {
        components: {
            swiper
        },
        data() {
            return {
                swiperOption: {
                    direction: 'horizontal',
                    slidesPerView: 'auto',
                    spaceBetween: 45,
                    autoHeight: true,
                    scrollbar: {
                        el: '.swiper-scrollbar',
                        draggable: true,
                        snapOnRelease: false,
                        dragSize: 100
                    },
                    mousewheel: false,
                },
                keyboardActive: false
            }
        },
        computed: {
            swiper() {
                return this.$refs.mySwiper.swiper
            }
        },
        methods: {
            Visible() {
                let target = this.$refs.mySwiper.$el;

                // Все позиции элемента
                var targetPosition = {
                        top: window.pageYOffset + target.getBoundingClientRect().top,
                        left: window.pageXOffset + target.getBoundingClientRect().left,
                        right: window.pageXOffset + target.getBoundingClientRect().right,
                        bottom: window.pageYOffset + target.getBoundingClientRect().bottom
                    },
                    // Получаем позиции окна
                    windowPosition = {
                        top: window.pageYOffset,
                        left: window.pageXOffset,
                        right: window.pageXOffset + document.documentElement.clientWidth,
                        bottom: window.pageYOffset + document.documentElement.clientHeight
                    };

                if (targetPosition.bottom > windowPosition.top && // Если позиция нижней части элемента больше позиции верхней чайти окна, то элемент виден сверху
                    targetPosition.top < windowPosition.bottom && // Если позиция верхней части элемента меньше позиции нижней чайти окна, то элемент виден снизу
                    targetPosition.right > windowPosition.left && // Если позиция правой стороны элемента больше позиции левой части окна, то элемент виден слева
                    targetPosition.left < windowPosition.right) { // Если позиция левой стороны элемента меньше позиции правой чайти окна, то элемент виден справа
                    // Если элемент полностью видно, то запускаем следующий код

                    return true;
                } else {
                    // Если элемент не видно, то запускаем этот код

                    return false;
                };
            }
        },
        created() {
            EventBus.$emit('add-slider-component', this);

            this.$nextTick(() => {
                this.$refs.mySwiper.swiper.on('slideChange', () => {
                    if (this.keyboardActive) return;

                    this.keyboardActive = true;
                    EventBus.$emit('change-keyboard-control', this);
                });
            });
        }
    }
</script>
