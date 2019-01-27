<template>
    <div class="accordion__item">

        <div class="accordion__item-step">
            Step {{ step }}
            <button type="button" class="btn btn--close popup__close" v-if="step > 1" @click="deleteStep"></button>
        </div>

        <div class="accordion__trigger create-steps__data">
            <label class="create-steps__field">
                <h3>Step's name (max 120)</h3>
                <transition name="fade">
                    <span class="create-steps__error-limit" v-show="nameLimit">Words limit reached</span>
                </transition>
                <input type="text" ref="id" :name="`steps[step${step}][id]`" class="visually-hidden" style="display: none;">
                <input type="text" v-model="titleCampaign" ref="title" :name="`steps[step${step}][title]`" maxlength="120" autocomplete="off" @input="maxLengthObserverName" :class="{'complete' : nameLimit}">
            </label>
            <label class="create-steps__field">
                <h3>Step's description (max 1000)</h3>

                <transition name="fade">
                    <span class="create-steps__error-limit" v-show="descriptionLimit">Words limit reached</span>
                </transition>

                <div class="scroll" :class="{'complete' : descriptionLimit}">
                    <textarea v-model="descriptionCampaign" ref="description" :name="`steps[step${step}][description]`" maxlength="1000" autocomplete="off" @input="maxLengthObserverDescription"></textarea>
                </div>
            </label>
            <create-vote v-if="voteIsCreate" :step-number="step" :title-upload="titleVote" :description-upload="descriptionVote" :variants-upload="variantsVote"></create-vote>
            <div class="create-steps__buttons">
                <button type="button" class="btn btn--bold create-steps__buttons-btn" @click="addVote" v-if="!voteIsCreate">Add vote</button>
            </div>
        </div>

    </div>
</template>

<script>
    import { EventBus } from './state-of-events';

    export default {
        components: {
            'create-vote': require('./create-vote.vue'),
        },
        props: {
            title: null,
        },
        data() {
            return {
                id: null,

                step: null,
                titleCampaign: '',
                descriptionCampaign: '',
                voteIsCreate: false,
                dataIsValid: false,

                titleVote: '',
                descriptionVote: '',
                variantsVote: null,
                createdVoteIsValid: false,

                descriptionLimit: false,
                nameLimit: false,
            }
        },

        methods: {
            validate() {
                if (this.titleCampaign === '') {
                    this.$refs.title.classList.add('error');
                    this.$refs.title.classList.add('animate-error');

                    setTimeout(()=> {
                        this.$refs.title.classList.remove('animate-error');
                    }, 1000);
                } else {
                    if (this.$refs.title.classList.contains('error')) {
                        this.$refs.title.classList.remove('error');
                    }
                }

                if (this.descriptionCampaign === '') {
                    let scroll = this.$refs.description.closest('.scroll');

                    scroll.classList.add('error');
                    scroll.classList.add('animate-error');

                    setTimeout(()=> {
                        scroll.classList.remove('animate-error');
                    }, 1000);
                } else {
                    let scroll = this.$refs.description.closest('.scroll');

                    if (scroll.classList.contains('error')) {
                        scroll.classList.remove('error');
                    }
                }

                if (this.voteIsCreate) {
                    EventBus.$emit('validateVoteCreated');
                }

                if (this.titleCampaign !== '' && this.descriptionCampaign !== '') {
                    if (this.voteIsCreate) {
                        if (this.createdVoteIsValid) {
                            this.dataIsValid = true;
                        } else {
                            this.dataIsValid = false;
                        }
                    } else {
                        this.dataIsValid = true;
                    }
                } else {
                    this.dataIsValid = false;
                }
            },

            addVote() {
                this.voteIsCreate = true;
            },

            deleteStep() {
                EventBus.$emit('deleteCreatedStep', this);
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
            EventBus.$emit('getStepNumber', this);

            this.$on('deleteCreatedVote', () => {
                this.voteIsCreate = false;
                this.titleVote = '';
                this.descriptionVote = '';
                this.variantsVote = null;
            });

            EventBus.$on('createdVoteIsValid', () => {
               this.createdVoteIsValid = true;
            });
            EventBus.$on('createdVoteIsNotValid', () => {
                this.createdVoteIsValid = false;
            });
        }
    }
</script>
