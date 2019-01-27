<template>
    <transition name="fade" mode="out-in">
        <div class="tabs__content" v-show="isActive"><slot></slot></div>
    </transition>
</template>

<script>
    export default {
        props: {
            name: { required: true },
            selected: { default: false },
            indicatorSwiper: {
                default: false
            }
        },
        data() {
            return {
                isActive: false,
                hash: this.name,
            }
        },

        mounted() {
            this.isActive = this.selected;
        },
    }
</script>
