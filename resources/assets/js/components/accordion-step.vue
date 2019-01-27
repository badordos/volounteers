<template>
    <div class="accordion__item" ref="accordionItem" :class="{'disabled' : !selected}">

        <div class="accordion__item-step">
            Step {{ step }}
            <button type="submit" class="accordion__item-toggle" v-if="!disabledToggle">
                <label class="toggle">
                    <input type="checkbox" :checked="getChecked">
                    <span class="toggle__btn"></span>
                </label>
            </button>
            {{ getActiveState }}
            <input type="hidden" value="" ref="id" class="visually-hidden" style="display: none;">
        </div>
        <div class="accordion__trigger" :class="{ 'active': active }" @click="open">
            <h1>{{ title }}</h1>
        </div>

        <transition name="accordion" @enter="start" @after-enter="end" @before-leave="start" @after-leave="end">
            <div class="accordion__wrapper create-steps" v-show="active">
                <div class="accordion__content">
                    <slot></slot>

                    <button type="button" class="btn btn--bold create-steps__buttons-btn" v-if="!vote && !createVote && showAddVote" @click="addVote">Add vote</button>
                    <create-vote v-if="createVote"
                                 :save-vote-btn="true"
                                 :save-route="saveRoute"
                                 :id="toggleId"/>
                </div>
            </div>
        </transition>

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
            selected: {
                default: false
            },
            vote: {
                default: false
            },
            showAddVote: {
                default: false
            },
            toggleId: null,
            saveRoute: {
                default: ''
            },
            disabledToggle: {
                default: false
            },
        },
        data() {
            return {
                active: false,
                step: null,
                createVote: false,
            }
        },
        computed: {
            getChecked() {
                return this.selected;
            },

            getActiveState() {
                if (this.selected) {
                    return 'Act' +
                        'ive step';
                }
            }
        },
        methods: {
            open() {
                if (this.active) {
                    this.$parent.$emit('close', this);
                } else {
                    this.$parent.$emit('open', this);
                }
            },
            start(el) {
                el.style.height = el.scrollHeight + 'px'
            },
            end(el) {
                el.style.height = ''
            },
            addVote() {
                this.createVote = true;
            }
        },

        mounted() {
            this.active = this.selected;
        },

        created() {
            this.$parent.$emit('count', this);

            this.$on('delete-vote', () => {
                this.createVote = false;
            });

            this.$nextTick(() => {
               this.$refs.id.value = this.toggleId;
            });

            this.$on('deleteCreatedVoteOnSingleCampaign', () => {
               this.createVote = false;
            });
        }
    }
</script>
