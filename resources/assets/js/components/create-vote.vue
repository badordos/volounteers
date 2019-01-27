<template>
    <div class="create-steps__vote-inner">

        <section class="section-vote create-steps__vote">
            <h1>
                Vote
                <button type="button" class="btn btn--close popup__close" @click="deleteVote"></button>
            </h1>
            <div class="section-vote__inner">
                <label class="create-steps__field">
                    <h3>Votes's name (max 120)</h3>
                    <transition name="fade">
                        <span class="create-steps__error-limit" v-show="nameLimit">Words limit reached</span>
                    </transition>
                    <input type="text" v-model="titleVote" ref="title" :name="`steps[step${stepNumber}][vote][title]`" maxlength="120" autocomplete="off" @input="maxLengthObserverName" :class="{'complete' : nameLimit}">
                </label>
                <label class="create-steps__field">
                    <h3>Votes's description (max 1000)</h3>
                    <transition name="fade">
                        <span class="create-steps__error-limit" v-show="descriptionLimit">Words limit reached</span>
                    </transition>
                    <div class="scroll" :class="{'complete' : descriptionLimit}">
                        <textarea name="description" v-model="descriptionVote" ref="description" :name="`steps[step${stepNumber}][vote][description]`" maxlength="1000" autocomplete="off" @input="maxLengthObserverDescription"></textarea>
                    </div>
                </label>
            </div>
            <div class="section-vote__inner section-vote__inner--center section-vote__variants">
                <transition name="fade">
                    <span class="create-steps__error-limit" v-show="limitVariants > 0">Words limit reached</span>
                </transition>
                <create-vote-variant :step-number="stepNumber"></create-vote-variant>
                <create-vote-variant :step-number="stepNumber"></create-vote-variant>
                <create-vote-variant v-for="item in createdVariants" :step-number="stepNumber" :key="item" ></create-vote-variant>

                <button type="button" class="btn btn--bold create-steps__buttons-btn" @click="addVariant" v-if="hideAddVariant">Add variant</button>
            </div>

            <button type="button" class="btn btn--bold create-steps__buttons-btn" v-if="saveVoteBtn" @click="sendCreatedVote">Save vote</button>
        </section>

    </div>
</template>

<script>
    const token = document.querySelector('meta[name="csrf-token"]').content;
    const axios = require('axios');

    import { EventBus } from './state-of-events';

    export default {
        components: {
            'create-vote-variant': require('./create-vote-variant.vue'),
        },
        props: {
            stepNumber: null,
            titleUpload: '',
            descriptionUpload: '',
            variantsUpload: null,
            saveVoteBtn: {
                default: false,
            },
            saveRoute: {
                default: ''
            },
            id: null,
        },
        data() {
            return {
                variants: [],
                stateOfVote: false,

                titleVote: '',
                descriptionVote: '',
                countOfVariants: 2,
                createdVariants: [],

                descriptionLimit: false,
                nameLimit: false,
                limitVariants: 0,
            }
        },

        computed: {
            hideAddVariant() {
                return this.countOfVariants < 4
            }
        },

        methods: {
            addVariant() {
                if (this.countOfVariants > 3) {
                    return;
                } else {
                    this.createdVariants.push(this.countOfVariants);
                    this.$nextTick(() => {
                        this.variants[this.countOfVariants].createdVariant = true;
                        this.countOfVariants++;
                    });
                }
            },

            deleteVote() {
                if (confirm('Do you really want to delete this voting?')) {
                    if (this.saveVoteBtn) {
                        this.$parent.$emit('deleteCreatedVoteOnSingleCampaign');
                    } else {
                        this.$parent.$emit('deleteCreatedVote')
                    }

                } else {
                    return;
                }
            },

            sendCreatedVote() {
                if (this.titleVote === '') {
                    if (this.$refs.title) {
                        this.$refs.title.classList.add('error');
                        this.$refs.title.classList.add('animate-error');

                        setTimeout(()=> {
                            if (this.$refs.title) {
                                this.$refs.title.classList.remove('animate-error');
                            }
                        }, 1000);
                    }
                } else {
                    if (this.$refs.title) {
                        if (this.$refs.title.classList.contains('error')) {
                            this.$refs.title.classList.remove('error');
                        }
                    }
                }

                if (this.descriptionVote === '') {
                    if (this.$refs.description) {
                        let scroll = this.$refs.description.closest('.scroll');

                        scroll.classList.add('error');
                        scroll.classList.add('animate-error');

                        setTimeout(()=> {
                            if (this.$refs.description) {
                                scroll.classList.remove('animate-error');
                            }
                        }, 1000);
                    }
                } else {
                    if (this.$refs.description) {
                        let scroll = this.$refs.description.closest('.scroll');

                        if (scroll.classList.contains('error')) {
                            scroll.classList.remove('error');
                        }
                    }
                }

                this.variants.forEach(item => {
                    let elem = item.$refs.input;

                    if (elem) {
                        if (elem.value === '') {
                            elem.classList.add('error');
                            elem.classList.add('animate-error');

                            setTimeout(() => {
                                elem.classList.remove('animate-error');
                            }, 1000);
                        } else {
                            if (elem.classList.contains('error')) {
                                elem.classList.remove('error');
                            }
                        }
                    }
                });

                let validate = this.variants.every(item => {
                    if (item.$refs.input) {
                        return item.$refs.input.value !== '';
                    }
                });

                if (this.titleVote !== '' && this.descriptionVote !== '' && validate) {
                    let variants = [];
                    this.variants.forEach(item => {
                        variants.push(item.$data.val);
                    });

                    axios.post(this.saveRoute, {
                        data: {
                            'id' : this.id,
                            'title': this.titleVote,
                            'description': this.descriptionVote,
                            'variants': variants
                        },
                        _token: token,
                    }).then(res => {
                        document.location.reload();
                    }).catch(error => {
                        EventBus.$emit('show-alert-error');
                        console.log(error);
                    });
                } else {

                }
            },
            maxLengthObserverDescription(e) {
                let maxLength = e.target.getAttribute('maxlength');

                if (e.target.value.length == maxLength) {
                    this.descriptionLimit = true;
                } else {
                    if (this.descriptionLimit) {
                        this.descriptionLimit = false;
                    }
                }
            },

            maxLengthObserverName(e) {
                let maxLength = e.target.getAttribute('maxlength');

                if (e.target.value.length == maxLength) {
                    this.nameLimit = true;
                } else {
                    if (this.nameLimit) {
                        this.nameLimit = false;
                    }
                }
            }
        },
        created() {
            this.variants = this.$children;

            if (this.titleUpload) {
                this.titleVote = this.titleUpload;
            }

            if (this.descriptionUpload) {
                this.descriptionVote = this.descriptionUpload;
            }

            if (this.variantsUpload) {
                let addCounter = this.variantsUpload.length - this.countOfVariants;

                while (addCounter) {
                    this.addVariant();

                    addCounter--;
                }

                this.$nextTick(() => {
                    this.variants.forEach((item, index) => {
                        item.val = this.variantsUpload[index];
                    });
                });
            }

            this.$on('delete', elem => {
                let index = this.variants.indexOf(elem);

                this.createdVariants.splice(index - 2, 1);
                this.countOfVariants--;
            });

            EventBus.$on('validateVoteCreated', () => {
                if (this.titleVote === '') {
                    if (this.$refs.title) {
                        this.$refs.title.classList.add('error');
                        this.$refs.title.classList.add('animate-error');

                        setTimeout(()=> {
                            if (this.$refs.title) {
                                this.$refs.title.classList.remove('animate-error');
                            }
                        }, 1000);
                    }
                } else {
                    if (this.$refs.title) {
                        if (this.$refs.title.classList.contains('error')) {
                            this.$refs.title.classList.remove('error');
                        }
                    }
                }

                if (this.descriptionVote === '') {
                    if (this.$refs.description) {
                        let scroll = this.$refs.description.closest('.scroll');

                        scroll.classList.add('error');
                        scroll.classList.add('animate-error');

                        setTimeout(()=> {
                            if (this.$refs.description) {
                                let scroll = this.$refs.description.closest('.scroll');

                                scroll.classList.remove('animate-error');
                            }
                        }, 1000);
                    }
                } else {
                    if (this.$refs.description) {
                        let scroll = this.$refs.description.closest('.scroll');

                        if (scroll.classList.contains('error')) {
                            scroll.classList.remove('error');
                        }
                    }
                }

                this.variants.forEach(item => {
                    let elem = item.$refs.input;

                    if (elem) {
                        if (elem.value === '') {
                            elem.classList.add('error');
                            elem.classList.add('animate-error');

                            setTimeout(() => {
                                elem.classList.remove('animate-error');
                            }, 1000);
                        } else {
                            if (elem.classList.contains('error')) {
                                elem.classList.remove('error');
                            }
                        }
                    }
                });

                let validate = this.variants.every(item => {
                    if (item.$refs.input) {
                        return item.$refs.input.value !== '';
                    }
                });

                if (this.titleVote !== '' && this.descriptionVote !== '' && validate) {
                    EventBus.$emit('createdVoteIsValid');
                } else {
                    EventBus.$emit('createdVoteIsNotValid');
                }
            });

            this.$nextTick(() => {
                let maxLenTitle = this.$refs.title.getAttribute('maxlength');
                let maxLenDescription = this.$refs.description.getAttribute('maxlength');

                if (this.titleVote.length == maxLenTitle) {
                    this.nameLimit = true;
                }

                if (this.descriptionVote.length == maxLenDescription) {
                    this.descriptionLimit = true;
                }
            });
        }
    }
</script>
