<template>
    <section class="achievement-card" v-if="active" :class="{'achievement-card--big': big}">
        <div class="achievement-card__inner">

            <div class="achievement-card__inner-header">
                <div class="achievement-card__inner-column">
                    <div class="achievement-card__medal" :style="{backgroundImage: `url(${medal})`}">
                    </div>
                </div>

                <div class="achievement-card__inner-column">
                    <slot name="title"></slot>
                    <slot name="note" v-if="!big"></slot>
                    <slot name="description" v-if="big"></slot>
                </div>
            </div>

            <div class="achievement-card__content" v-if="!big">
                <slot name="description"></slot>
            </div>


        </div>
    </section>
</template>

<script>
    export default {
        props: {
            medal: null,
            big: {
                default: false,
            }
        },
        data() {
            return {
                active: false,
            }
        },

        methods: {}
    }
</script>
