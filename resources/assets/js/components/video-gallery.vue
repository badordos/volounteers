<template>
    <section class="photo-gallery">
        <div class="photo-gallery__inner">

            <div class="photo-gallery__thumbs" :class="{'more': countOfPhotos > 4}"  v-show="countOfPhotos > 1">
                <slot></slot>

                <div class="photo-gallery__thumbs-item photo-gallery__thumbs-item--count" v-if="countOfPhotos > 4" @click="openGallery">
                    + {{ countOfPhotos - 3}}
                </div>
            </div>


            <transition-group tag="div" class="photo-gallery__content" id="photo-gallery" name="fade">
                <a :href="item.type === 'video' ? '' : item.preview" class="photo-gallery__content-item" v-for="(item, index) in photos" v-if="item.indicator" v-show="item.isActive" :key="index" :data-poster="item.type === 'video' ? item.preview : ''" :data-html="item.type === 'video' ? '#photo-gallery-video' : ''" :style="{backgroundImage: `url(${item.preview})`}" :class="{'photo-gallery__content-item--video' : item.type === 'video'}">
                    <!--<template v-if="item.preview === '' || item.preview === null">-->
                        <!--<img src="/images/icon-camera.svg" :alt="`photo${index + 1}`" ref="img" v-if="item.type === 'video'" class="photo-gallery__content-item&#45;&#45;image">-->
                    <!--</template>-->
                    <!--<template v-else>-->
                       <!---->
                    <!--</template>-->
                    <svg class="photo-gallery__content-icon" v-if="item.type === 'video'">
                        <use xlink:href="#icon-play"/>
                    </svg>
                    <img class="photo-gallery__content-image" :src="item.preview" :alt="`photo${index + 1}`" ref="img">
                </a>
            </transition-group>
            <div style="display: none" id="photo-gallery-video">
                <video class="lg-video-object lg-html5" controls preload="metadata">
                    <source :src="srcVideo" :type="typeVideo">
                    Your browser does not support HTML5 video.
                </video>
            </div>
        </div>
    </section>
</template>

<script>
    import 'lightgallery.js/dist/css/lightgallery.css';

    import 'lightgallery.js';
    import 'lg-thumbnail.js';
    import 'lg-video.js';

    export default {
        data() {
            return {
                photos: [],
                activePhoto: null,
                srcVideo: null,
                typeVideo: null,
            }
        },

        computed: {
            countOfPhotos() {
                return this.photos.length - 1;
            }
        },

        methods: {
            showInContent(selected) {
                let index = 0;

                for (let i = 0; i < this.photos.length; i++) {
                    if (this.photos[i] == selected) {
                        index = i;
                    }
                    this.photos[i].isActive = false;
                }

                this.photos[index].isActive = true;
            },

            openGallery() {
                this.$refs.img[3].click();
            }
        },

        created() {
            this.photos = this.$children;

            this.$on('show', (e) => {
                this.showInContent(e);
            });

            this.$on('setDataVideo', (src, type) => {
                this.srcVideo = src;
                this.typeVideo = type;
            });

            this.$nextTick(() => {
                setTimeout(() => {
                    let gallery = document.getElementById('photo-gallery');

                    window.lightGallery(gallery, {
                        thumbnail: true,
                        download: false,
                        enableDrag: false,
                    });

                    gallery.addEventListener('onAfterOpen', e => {
                        let video = document.querySelector('.lg-video');

                        if (video) {
                            video.click();
                        }
                    });
                }, 100);
            });
        },
    }
</script>
