<template>
    <section class="mini-gallery">
        <div class="mini-gallery__inner">

            <slot></slot>

            <div class="mini-gallery__item mini-gallery__item--count" v-if="countOfPhotos > 4">
                + {{ countOfPhotos - 3}}
            </div>
            <div class="mini-gallery__image">
                <div class="mini-gallery__image-item" v-for="(photo, index) in photos">
                    <img :src="photo.image" :alt="`photo${index + 1}`" v-img="{ group: `${id}` }">
                </div>
            </div>
        </div>
    </section>
</template>

<script>

    export default {
        props: ['id'],
        data() {
            return {
                photos: [],
            }
        },
        computed: {
            countOfPhotos() {
                return this.photos.length - 1;
            }
        },
        created() {
            this.photos = this.$children;
        }
    }
</script>