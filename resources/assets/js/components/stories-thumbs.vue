<template>
    <swiper :options="swiperOptionThumbs" class="stories-slider__thumbs" ref="mySwiper" @click.prevent="openOutput">
        <slot></slot>
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
                swiperOptionThumbs: {
                    centeredSlides: true,
                    slideToClickedSlide: true,
                    slidesPerView: 'auto',
                    spaceBetween: 41,
                    freeMode: false,
                    navigation: {
                        nextEl: '.stories-slider__next',
                        prevEl: '.stories-slider__prev',
                    },
                    shortSwipes: false,
                    mousewheel: false,
                    roundLengths: true,
                },
                keyboardActive: false,
                indicator: 'stories'
            }
        },
        methods: {
            openOutput(e) {
                if (!!e.target.parentElement.closest('.swiper-slide-active')) {
                    this.$parent.$emit('show-stories-output');

                    document.querySelector('html').classList.add('no-scroll');
                }
            },
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
            let prev, next;

            this.$nextTick(() => {
                prev = document.querySelector('.stories-slider__prev');
                next = document.querySelector('.stories-slider__next');
            });

            document.addEventListener('keypress', (e) => {
                let isVisible = this.Visible();

                if (isVisible && e.keyCode === 13 && this.keyboardActive) {
                    setTimeout(() => {
                        this.$parent.$emit('show-stories-output');
                        document.querySelector('html').classList.add('no-scroll');
                    }, 100);
                }
            });

            EventBus.$emit('add-slider-component', this);

            this.$nextTick(() => {
                this.$refs.mySwiper.swiper.on('slideChange', () => {
                    prev.blur();
                    next.blur();

                    if (this.keyboardActive) return;

                    this.keyboardActive = true;
                    EventBus.$emit('change-keyboard-control', this);
                });
            });
        }
    }
</script>
