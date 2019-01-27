<template>
    <video class="story__video" :src="source" ref="video" preload="metadata"></video>
</template>

<script>
    export default {
        props: {
            source: {
                required: true,
            }
        },
    }
</script>
