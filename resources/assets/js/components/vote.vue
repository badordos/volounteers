<template>
    <section class="section-vote" v-if="active" ref="sectionVote">
        <h1>
            Vote <button type="button" class="btn btn--close popup__close" v-if="activeDeleteButton" @click="deleteVote"></button>
        </h1>
        <div class="section-vote__inner">
            <h3><slot name="title"></slot></h3>
            <slot name="description"></slot>
        </div>
        <div class="section-vote__inner section-vote__inner--center">
            <slot name="variants"></slot>
            <transition name="fade">
                <button type="button" class="btn section-vote__vote" :class="{'active' : showButtonSend}" @click="showResult">Vote</button>
            </transition>
        </div>
    </section>
</template>

<script>
    const axios = require('axios');
    import { EventBus } from './state-of-events';

    export default {
        props: {
            disabled: {
                default: false,
            },
            route: null,
            deleteRoute: null,
            deleteId: null,
            token: null,
            activeDeleteButton: {
                default: false,
            },
            showJoin: {
                default: false,
            },
            voted: {
                default: false,
            },
            activeVariant: {
                default: null,
            }
        },
        data() {
            return {
                active: true,
                variants: [],
                stateOfVote: false,
                selectedValue: null,
            }
        },

        computed: {
            showButtonSend() {
                return this.stateOfVote && !this.disabled;
            }
        },
        methods: {
            showResult() {
                axios.post(this.route, {
                    selectedVoteVariant: this.selectedValue,
                    _token: this.token,
                }).then(res => {
                    for (let i = 0; i < res.data.length; i++) {
                        this.variants[i].resultActive = true;
                        this.variants[i].activeEvent = false;
                        this.variants[i].countOfVotes = res.data[i]['number_of_votes'];
                    }

                    this.stateOfVote = false;
                }).catch(error => {
                    EventBus.$emit('show-alert-error');
                    console.log(error);
                });
            },
            deleteVote() {
                if (confirm('Do you really want to delete this voting?')) {
                    axios.post(this.deleteRoute, {
                        _token: this.token,
                        id: this.deleteId
                    }).then(res => {
                        this.active = false;
                        document.location.reload();
                    }).catch(error => {
                        EventBus.$emit('show-alert-error');
                        console.log(error);
                    });
                } else {
                    return;
                }
            }
        },

        created() {
            this.variants = this.$children;

            this.$nextTick(() => {
                if (this.disabled) {
                    this.variants.forEach(item => {
                        item.activeEvent = false;
                        //item.resultActive  = true;
                    });

                    this.stateOfVote = false;
                }

                if (this.voted) {
                    this.variants.forEach(item => {
                        item.activeEvent = false;
                        item.resultActive  = true;
                    });

                    this.stateOfVote = false;
                }

                if (this.showJoin) {
                    this.$refs.sectionVote.addEventListener('click', (e) => {
                        let target = e.target;

                        if (target.closest('.section-vote__btn')) {
                            EventBus.$emit('show-join-popup');
                        }
                    });
                }

                if (this.activeVariant) {
                    this.variants[this.activeVariant].active = true;
                }
            });

            this.$on('select', (item, value) => {
                this.variants.forEach(elem => {
                   if (elem != item) {
                       elem.active = false;
                   } else {
                       elem.active = true;
                   }
                });

                this.stateOfVote = true;
                this.selectedValue = value;
            });
        }
    }
</script>
