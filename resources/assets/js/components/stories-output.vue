<template>
    <transition name="fade">
        <swiper :options="swiperOptionTop" class="stories-slider__output" ref="swiperTop" :class="{ 'active' : active }" @click="closeStories">
            <slot></slot>

            <div class="stories-slider__output-next" slot="button-next"></div>
            <div class="stories-slider__output-prev" slot="button-prev"></div>
        </swiper>
    </transition>
</template>

<script>
    import { swiper } from 'vue-awesome-swiper';

    export default {
        components: {
            swiper
        },
        props: {
            progress: null,
        },
        data() {
            return {
                active: false,
                timerVideo: null,
                timerProgress: null,

                swiperOptionTop: {
                    slidesPerView: 'auto',
                    spaceBetween: 10,
                    centeredSlides: true,
                    navigation: {
                        nextEl: '.stories-slider__output-next',
                        prevEl: '.stories-slider__output-prev'
                    },
                    autoHeight: true,
                    mousewheel: false,
                    shortSwipes: false,
                    on: {
                        slideChange: () => {
                            if (!this.active) {
                                return
                            }

                            this.$nextTick(() => {
                                this.playStory();
                            });
                        }
                    }
                },
            }
        },
        methods: {
            closeStories(e) {
                if (e.target.classList) {
                    let classes = e.target.classList || e.srcElement.classList;

                    if (!classes.contains('story__inner') && !classes.contains('stories-slider__output-next') && !classes.contains('stories-slider__output-prev') && !classes.contains('story__video')) {
                        this.active = false;
                        document.querySelector('html').classList.remove('no-scroll');

                        let slider = document.querySelector('.stories-slider__output');

                        if (slider) {
                            let current__video = slider.querySelector('.swiper-slide-active video'),
                                progress = slider.querySelector('.swiper-slide-active .progress');

                            if (current__video) {
                                current__video.pause();
                                current__video.currentTime = 0;
                                progress.style.width = 0;

                                let id = window.setTimeout(function() {}, 0);

                                while (id--) {
                                    window.clearTimeout(id);
                                }
                            } else if (progress) {
                                progress.style.width = 0;

                                let id = window.setTimeout(function() {}, 0);

                                while (id--) {
                                    window.clearTimeout(id);
                                }
                            }
                        }
                    }
                }

            },

            playStory() {
                let slider = document.querySelector('.stories-slider__output'),
                    video__prev = slider.querySelector('.swiper-slide-prev video'),
                    video__next = slider.querySelector('.swiper-slide-next video'),
                    current__video = slider.querySelector('.swiper-slide-active video');

                let current__progress = slider.querySelector('.swiper-slide-active .progress'),
                    progress__prev = slider.querySelector('.swiper-slide-prev .progress'),
                    progress__next = slider.querySelector('.swiper-slide-next .progress');

                if (current__video) {
                    playVideo(current__video, this);
                } else if(current__progress) {
                    startProgress(current__progress, this);
                }

                if (video__prev) {
                    videoStop(video__prev, '.swiper-slide-prev');
                }

                if (video__next) {
                    videoStop(video__next, '.swiper-slide-next');
                }

                if (progress__prev) {
                    stopProgress(progress__prev);
                }

                if (progress__next) {
                    stopProgress(progress__next);
                }
            }
        },

        created() {
            document.addEventListener('keyup', (e) => {
               if (e.keyCode === 27 && this.active) {

                   this.active = false;
                   document.querySelector('html').classList.remove('no-scroll');

                   let slider = document.querySelector('.stories-slider__output');

                   if (slider) {
                       let current__video = slider.querySelector('.swiper-slide-active video'),
                           progress = slider.querySelector('.swiper-slide-active .progress');

                       if (current__video) {
                           current__video.pause();
                           current__video.currentTime = 0;
                           progress.style.width = 0;

                           let id = window.setTimeout(function() {}, 0);

                           while (id--) {
                               window.clearTimeout(id);
                           }
                       } else if (progress) {
                           progress.style.width = 0;

                           let id = window.setTimeout(function() {}, 0);

                           while (id--) {
                               window.clearTimeout(id);
                           }
                       }
                   }
               }
            });
        }
    }

    function playVideo(video, ctx) {
        let progress = document.querySelector('.swiper-slide-active .progress');

        let fps = 120;
        let time = 15;

        video.currentTime = 0;
        video.play();

        clearInterval(ctx.timerVideo);
        clearInterval(ctx.timerProgress);

        ctx.timerVideo = setInterval(() => {

            progress.style.width = video.currentTime / time * 100 + '%';

            if (video.currentTime >= time) {
                clearInterval(ctx.timerVideo);
                video.pause();
                ctx.$children[0].swiper.slideNext();
            }
        }, 1000 / fps);
    }

    function videoStop(video, selector) {
        let progress = document.querySelector(selector + ' .progress');

        video.pause();
        video.currentTime = 0;
        progress.style.width = 0;
    }

    function startProgress(progress, ctx) {
        let i = 0;

        let fps = 120;
        let time = 15;

        clearInterval(ctx.timerProgress);
        clearInterval(ctx.timerVideo);

        ctx.timerProgress = setInterval(() => {

            progress.style.width = i / (time * 1000) * 100 + '%';

            if (i >= time * 1000) {
                clearInterval(ctx.timerProgress);
                ctx.$children[0].swiper.slideNext();
            }

            i += 1000 / fps;
        }, 1000 / fps);
    }

    function stopProgress(progress) {
        progress.style.width = 0;
    }
</script>
