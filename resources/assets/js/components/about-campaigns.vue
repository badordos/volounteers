<template>
    <section class="about-campaigns" :id="getId">
        <slot name="filter"></slot>

        <div class="about-campaigns__inner" ref="flow" :class="{'about-campaigns__inner--no-paddings': hidePaddings}">
            <slot name="campaigns"></slot>
        </div>

        <button type="button" class="btn btn--bold about-campaigns__btn" v-if="showButton" @click="openList">More campaigns</button>
    </section>
</template>

<script>
    export default {
        props: {
            hidePaddings: {
                default: false,
            },
            id: {
                default: ''
            }
        },

        data() {
            return {
                campaigns: [],
                countOfShow: 6,
                countOfOpen: 9
            }
        },

        computed: {
            showButton() {
                return this.campaigns.length - 1 > this.countOfShow
            },

            getId() {
                return this.id
            }
        },

        mounted() {
            this.campaigns = this.$children;

            let count = this.countOfShow;

            this.$nextTick(() => {
                for (let i = count + 1; i < this.campaigns.length; i++) {
                    this.campaigns[i].active = false;
                }
            });
        },

        methods: {
            openList() {
                let flow = this.$refs.flow;
                let counter = this.countOfOpen;

                flow.style.height = flow.offsetHeight + 'px';
                flow.style.overflowY = 'auto';
                flow.classList.add('active');

                while (counter != 0 && this.countOfShow + 1 < this.campaigns.length) {
                    this.campaigns[this.countOfShow + 1].active = true;
                    counter--;
                    this.countOfShow++;
                }

                this.animateScroll(flow.scrollHeight + 500, flow);
            },
            animateScroll: function(pos, el) {
                let start = el.scrollTop || 1,
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
        }
    }
</script>
