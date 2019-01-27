<template>
    <li v-if="active">
        <slot></slot>
    </li>
</template>

<script>
    export default {
        data() {
            return {
                active: false,
            }
        },

        methods: {}
    }
</script>
