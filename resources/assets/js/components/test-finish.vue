<template>
    <transition name="test-finish">
        <div class="test__view-release" v-if="$parent.$data.lastScreen">
            <slot></slot>
            <a class="btn btn--question" :href="href">Start charity</a>
        </div>
    </transition>
</template>

<script>
    export default {
        data() {
            return {
                href: '',
                indicator: 'finish'
            }
        }
    }
</script>
