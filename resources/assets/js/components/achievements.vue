<template>
    <section class="achievements" :id="getId">
        <slot name="title"></slot>
        <div class="achievements__inner" ref="flow">
            <slot></slot>
        </div>
        <button type="button" class="btn btn--bold achievements__btn" v-if="showButton" @click="openList">More challenges</button>
    </section>
</template>

<script>
    export default {
        props: {
            count: {
                default: 6,
            },
            id: {
                default: ''
            },
            openAll: {
                default: false,
            }
        },
        data() {
            return {
                achievementsList: [],
                countOfShow: this.count,
                countOfOpen: 9
            }
        },

        computed: {
            showButton() {
                return this.achievementsList.length - 1 > this.countOfShow
            },

            getId() {
                return this.id
            }
        },

        mounted() {
          this.achievementsList = this.$children;

          this.$nextTick(() => {
              for (let i = 0; i < this.countOfShow; i++) {
                  if (this.achievementsList[i]) {
                      this.achievementsList[i].active = true;
                  }
              }
          });
        },
        methods: {
            openList() {
                if (this.openAll) {

                    this.achievementsList.forEach(item => {
                       item.active = true;
                       this.countOfShow++;
                    });

                } else {
                    let flow = this.$refs.flow;
                    let counter = this.countOfOpen;

                    flow.style.height = flow.offsetHeight + 'px';
                    flow.style.overflowX = 'auto';

                    while (counter != 0 && this.countOfShow + 1 < this.achievementsList.length) {
                        this.achievementsList[this.countOfShow + 1].active = true;
                        counter--;
                        this.countOfShow++;
                    }

                    this.animateScroll(flow.scrollHeight + 500, flow);
                }
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
        },
    }
</script>
