<template>
    <section class="vote-list">
        <div class="vote-list__inner">

            <slot></slot>

            <div class="campaigns-list__inner-row">
                <button type="button" class="btn btn--bold about-campaigns__btn" @click="showMoreVotes" v-if="activeBtnShow">{{ getTextBtn }}</button>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        props: {
            textBtn: {
                default: 'More votes'
            }
        },
        data() {
            return {
                countOfVisibleVotes: 12,
                votes: [],
                countOfShowed: 0,
                activeBtnShow: true,
            }
        },
        computed: {
            getTextBtn() {
                return this.textBtn;
            }
        },
        methods: {
            showMoreVotes() {
                let count = this.countOfShowed;

                for (let i = 0; i < this.countOfVisibleVotes; i++) {
                    if (this.votes[count + i]) {
                        this.votes[count + i].active = true;
                        this.countOfShowed++;

                        if (!this.votes[count + i + 1]) {
                            this.activeBtnShow = false;
                        }
                    } else {
                        this.activeBtnShow = false;

                        return;
                    }
                }
            }
        },
        created() {
            this.$nextTick(() => {
               this.votes = this.$children;

               for (let i = 0; i < this.countOfVisibleVotes; i++) {
                   if (this.votes[i]) {
                       this.votes[i].active = true;
                       this.countOfShowed++;

                       if (!this.votes[i + 1]) {
                           this.activeBtnShow = false;
                       }
                   } else {
                       this.activeBtnShow = false;

                       return;
                   }
               }
            });
        }
    }
</script>