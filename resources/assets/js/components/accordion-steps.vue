<template>
    <section class="accordion">
        <div class="accordion__inner">

            <slot></slot>
            <div class="accordion__item accordion__item--finish">
                <h1>Finish</h1>
            </div>

        </div>
        <slot name="announcement"></slot>
    </section>
</template>

<script>
    export default {
        data() {
            return {
                count: 0,
                steps: [],
            }
        },

        created() {
            this.steps = this.$children;

            this.$on('count', (item) => {
                this.count++;
                item.step = this.count;
            });

            this.$on('open', item => {
                item.active = true;
            });

            this.$on('close', item => {
                item.active = false;
            });
        }
    }
</script>
