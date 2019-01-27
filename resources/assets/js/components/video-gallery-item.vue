<template>
    <div class="photo-gallery__thumbs-item" :style="{backgroundImage: `url(${bgImage})`}" @click="sendDataToParent">
        <svg class="photo-gallery__thumbs-icon" v-if="type === 'video'">
            <use xlink:href="#icon-play"/>
        </svg>
    </div>
</template>

<script>
    export default {
        props: {
            preview: { required: true },
            selected: { default: false },
            type: { default: 'image'},
            srcVideo: null,
            typeVideo: null,
        },
        data() {
            return {
                isActive: false,
                indicator: true,
                bgImage: ''
            }
        },

        methods: {
            sendDataToParent() {
                this.$parent.$emit('show', this);
            }
        },

        mounted() {
            this.isActive = this.selected;

            if (this.type === 'video') {
                this.$parent.$emit('setDataVideo', this.srcVideo, this.typeVideo);
            }

            if (this.preview) {
                this.bgImage = this.preview;
            }
        },
    }
</script>
