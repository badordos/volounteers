<template>
    <section class="story">
        <h3>
            <template v-if="dateTime">
                {{ dateTime }}
            </template>
            <template v-else>
                {{ title }}
            </template>
        </h3>
        <div class="story__inner" :style="{backgroundImage: `url(${preview})`}">
            <div class="progress-bar__inner" v-if="progress">
                <video-progress></video-progress>
            </div>
            <slot></slot>
        </div>
    </section>
</template>

<script>
    export default {
        components: {
            'video-progress': require('./video-progress.vue')
        },
        props: {
            title: {
                default: ''
            },
            preview: {
                default: ''
            },
            dateTime: null,
            progress: {
                default: false
            }
        },

        data() {
            return {

            }
        }
    }
</script>
