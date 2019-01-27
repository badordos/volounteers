<template>
    <li>
        <section class="personal-user__feature">
            <slot name="title"></slot>
            <slot name="description"></slot>
            <ul v-if="list" ref="flow">
                <slot name="list"></slot>
            </ul>

            <button type="button" class="btn btn--bold personal-user__show" v-if="showButton" @click="openList">Show all</button>
        </section>
    </li>
</template>

<script>
    export default {
        props: {
            list: {
                default: false
            },
        },

        data() {
            return {
                listOfStrings: [],
                countOfShow: 4,
                activeButton: true
            }
        },

        computed: {
            showButton() {
                return this.listOfStrings.length > 4 && this.activeButton
            }
        },

        methods: {
            openList() {
                this.activeButton = false;

                let flow = this.$refs.flow;

                flow.style.height = flow.offsetHeight + 'px';
                flow.style.overflowX = 'auto';

                this.listOfStrings.forEach(item => {
                    item.active = true;
                });

                this.animateScroll(100, flow);
            },
            animateScroll: function(pos, el) {
                el.scrollTop = 1;

                let start = el.scrollTop,
                    change = pos - start,
                    currentTime = 0,
                    increment = 20,
                    duration = 500;

                let animateScroll = function(){
                    currentTime += increment;
                    var val = Math.easeInOutQuad(currentTime, start, change, duration);
                    el.scrollTop = val;
                    if(currentTime < duration) {
                        setTimeout(animateScroll, increment);
                    }
                };
                animateScroll();
            }
        },

        mounted() {
            this.listOfStrings = this.$children;

            this.$nextTick(() => {
                for (let i = 0; i < this.countOfShow; i++) {
                    if (this.listOfStrings[i]) {
                        this.listOfStrings[i].active = true;
                    }
                }
            });
        },
    }
</script>
