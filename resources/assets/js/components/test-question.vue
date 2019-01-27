<template>
    <transition name="test">
        <div class="test__view-question" v-if="isActive" @click="getAnswer">
            <h1>
                {{ $parent.$data.currentQuestion + 1}}.{{ title }}
            </h1>
            <slot></slot>
        </div>
    </transition>
</template>

<script>
    export default {
        props: {
            title: { required: true }
        },
        data() {
            return {
                isActive: false
            }
        },
        methods: {
            getAnswer(e) {
                let target = e.target;

                if (target.classList.contains('btn--question')) {
                    this.$parent.$data.answers.push(target.value);
                    this.$parent.$data.currentQuestion++;
                }
            }
        }
    }
</script>
