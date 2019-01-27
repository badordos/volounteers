<template>
    <section class="chat">
        <h1 v-if="title">{{ title }}</h1>
        <div class="chat__flow" ref="flow">
            <slot></slot>
        </div>
        <button type="button" class="btn btn--bold chat__more" @click="open">More comments</button>
        <div class="chat__user">
            <slot name="current-user"></slot>
            <button type="button" class="btn btn--bold chat__add">Add comment</button>
        </div>

    </section>
</template>

<script>
    export default {
        props: {
            title: null,
            countOfComments: {
                default: 3
            }
        },
        data() {
            return {
                comments: [],
            }
        },

        methods: {
            open() {
                let flow = this.$refs.flow;
                flow.style.height = flow.offsetHeight + 'px';
                flow.style.overflowX = 'auto';

                this.animateScroll(100, flow);

                this.comments.forEach(item => {
                    item.active = true;
                });
            },

            animateScroll: function(pos, el) {
                el.scrollTop = 1;

                let start = el.scrollTop,
                    change = pos - start,
                    currentTime = 0,
                    increment = 20,
                    duration = 300;

                var animateScroll = function(){
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

        created() {
            this.comments = this.$children;

            this.$nextTick(() => {
                for (let i =0; i < this.countOfComments; i++) {
                    this.comments[i].active = true;
                }
            });
        }
    }
</script>
