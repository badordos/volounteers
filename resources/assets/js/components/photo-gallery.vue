<template>
    <section class="photo-gallery">
        <div class="photo-gallery__inner">

            <div class="photo-gallery__thumbs" :class="{'more': countOfPhotos > 4}">
                <slot></slot>

                <div class="photo-gallery__thumbs-item photo-gallery__thumbs-item--count" v-if="countOfPhotos > 4" @click="openGallery">
                    + {{ countOfPhotos - 3}}
                </div>
            </div>


            <transition-group tag="div" class="photo-gallery__content">
                <!--<div class="photo-gallery__content-item" v-for="(item, index) in photos" :style="{backgroundImage: 'url(' + item.preview + ')'}" v-if="item.indicator" v-show="item.isActive" :key="index">-->
                    <!--<img :src="item.preview" :alt="`photo${index + 1}`" v-img:group1 ref="img">-->
                <!--</div>-->
                <div class="photo-gallery__content-item" v-for="(item, index) in photos" v-if="item.indicator" v-show="item.isActive" :key="index">
                    <img :src="item.preview" :alt="`photo${index + 1}`" v-img:group1 ref="img">
                </div>
            </transition-group>

        </div>
    </section>
</template>

<script>

    export default {
        data() {
            return {
                photos: [],
                activePhoto: null
            }
        },

        computed: {
            countOfPhotos() {
                return this.photos.length - 1;
            }
        },

        methods: {
            showInContent(selected) {
                this.photos.forEach(item => {
                    item.isActive = (item == selected);
                });
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
        },
    }
</script>
