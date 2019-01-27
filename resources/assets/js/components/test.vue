<template>
    <section class="test">
        <transition name="test">
            <section class="test__start" v-if="isStart">
                <h1>
                    {{ title }}
                </h1>
                <p>
                    {{ description }}
                </p>
                <button class="btn btn--test" @click="startTest">Start test</button>
            </section>
        </transition>
        <div class="test__view">
            <slot></slot>
            <transition name="fade">
                <div class="preloader-mini" v-if="activePreloader">
                    <div class="preloader-mini__rotating-border"></div>
                </div>
            </transition>
            <div class="error-block" :class="{'active' : activeError}">
                An error has occurred. Repeat later.
            </div>
        </div>
    </section>
</template>

<script>
    const axios = require('axios');
    const token = document.querySelector('meta[name="csrf-token"]').content;

    import { EventBus } from './state-of-events';

    export default {
        props: {
            title: { required: true },
            description: { required: false},
            route: '',
        },
        data() {
            return {
                questions: [],
                currentQuestion: 0,
                isStart: true,
                answers: [],
                lastScreen: false,
                activePreloader: false,
                activeError: false
            }
        },

        watch: {
            currentQuestion: function(newIndex, oldIndex) {
                if (this.questions[newIndex].hasOwnProperty('isActive')) {
                    this.questions[newIndex].isActive = true;
                    this.questions[oldIndex].isActive = false;
                } else {
                    this.questions[oldIndex].isActive = false;
                    this.activePreloader = true;

                    axios.post(this.route, {
                        answers: this.answers,
                        _token: token,
                    }).then(res => {
                        let link = res.data.link,
                            category = res.data.category;

                        EventBus.$emit('setCategory', category);

                        this.questions.forEach(item => {
                            if (item.indicator === 'finish') {
                                item.href = link;
                            }
                        });

                        this.activePreloader = false;
                        this.lastScreen = true;
                    }).catch(error => {
                        EventBus.$emit('show-alert-error');
                        this.activeError = true;
                        console.log(error);
                    });
                }
            }
        },
        created() {
            this.questions = this.$children;
        },
        methods: {
            startTest() {
                this.isStart = false;

                if (this.questions[this.currentQuestion]) {
                    this.questions[this.currentQuestion].isActive = true;
                }
            },

            hello() {
                console.log('1');
            }
        }
    }
</script>
