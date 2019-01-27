<template>
    <div class="mini-gallery__item" :style="{backgroundImage: `url(${image})`}">

    </div>
</template>

<script>
    export default {
        props: {
          image: {
              required: true
          }
        },
    }
</script>