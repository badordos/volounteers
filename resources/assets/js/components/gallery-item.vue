<template>
    <div class="photo-gallery__thumbs-item" :style="{backgroundImage: `url(${preview})`}" @click="sendDataToParent">
    </div>
</template>

<script>
    export default {
        props: {
            preview: { required: true },
            selected: { default: false }
        },
        data() {
            return {
                isActive: false,
                indicator: true,
            }
        },

        methods: {
            sendDataToParent() {
                this.$parent.$emit('show', this);
            }
        },

        mounted() {
            this.isActive = this.selected;
        }
    }
</script>
