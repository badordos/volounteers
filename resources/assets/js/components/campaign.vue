<template>
    <transition name="fade" mode="out-in">
        <div class="main-directions__content" v-show="isActive"><slot></slot></div>
    </transition>
</template>

<script>
    export default {
        props: {
            name: { required: true },
            selected: { default: false }
        },
        data() {
            return {
                isActive: false
            }
        },

        computed: {
            href() {
                return '#' + this.name.toLowerCase().replace(/ /g, '-');
            }
        },

        mounted() {
            this.isActive = this.selected;
        },
    }
</script>
