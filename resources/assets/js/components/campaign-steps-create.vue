<template>
    <section class="accordion create-steps">
        <div class="accordion__inner">

            <slot></slot>

            <create-step v-for="item in createdSteps" :key="item"></create-step>
            <div class="accordion__item accordion__item--finish">
                <button type="button" class="btn btn--bold create-steps__buttons-btn create-steps__buttons-btn--add" @click="addStep"><span class="plus">+</span>Add step</button>
            </div>

        </div>

    </section>
</template>

<script>
    import { EventBus } from './state-of-events';

    export default {
        components: {
            'create-step': require('./campaign-step-create.vue'),
        },
        props: {
            uploadData: {
                default: null,
            },
        },
        data() {
            return {
                count: 0,
                id: 1,
                steps: [],
                createdSteps: [],
                dataIsValid: false,
            }
        },

        methods: {
            addStep() {
                this.createdSteps.push(this.id++);
            }
        },
        created() {
            this.steps = this.$children;

            if (this.uploadData) {
                this.$nextTick(() => {
                    let data = this.uploadData;

                    data.forEach((item, index) => {
                        if (index === 0) {
                            let maxLenTitle = this.steps[index].$refs.title.getAttribute('maxlength');
                            let maxLenDescription = this.steps[index].$refs.description.getAttribute('maxlength');

                            if (data[index].title.length == maxLenTitle) {
                                this.steps[index].nameLimit = true;
                            }

                            if (data[index].description.length == maxLenDescription) {
                                this.steps[index].descriptionLimit = true;
                            }

                            this.steps[index].$refs.id.value = data[index].id;
                            this.steps[index].titleCampaign = data[index].title;
                            this.steps[index].descriptionCampaign = data[index].description;

                            if (data[index].hasOwnProperty('vote')) {
                                this.steps[index].voteIsCreate = true;
                                this.steps[index].titleVote = data[index].vote.title;
                                this.steps[index].descriptionVote = data[index].vote.description;
                                this.steps[index].variantsVote = data[index].vote.variants;
                            }
                        } else {
                            this.addStep();

                            this.$nextTick(() => {
                                let maxLenTitle = this.steps[index].$refs.title.getAttribute('maxlength');
                                let maxLenDescription = this.steps[index].$refs.description.getAttribute('maxlength');

                                if (data[index].title.length == maxLenTitle) {
                                    this.steps[index].nameLimit = true;
                                }

                                if (data[index].description.length == maxLenDescription) {
                                    this.steps[index].descriptionLimit = true;
                                }

                                this.steps[index].$refs.id.value = data[index].id;
                                this.steps[index].titleCampaign = data[index].title;
                                this.steps[index].descriptionCampaign = data[index].description;

                                if (data[index].hasOwnProperty('vote')) {
                                    this.steps[index].voteIsCreate = true;
                                    this.steps[index].titleVote = data[index].vote.title;
                                    this.steps[index].descriptionVote = data[index].vote.description;
                                    this.steps[index].variantsVote = data[index].vote.variants;
                                }
                            });
                        }
                    });
                });
            }

            EventBus.$on('getStepNumber', (step) => {
               this.count++;
               step.step = this.count;
            });

            EventBus.$on('deleteCreatedStep', (step) => {
                let index = step.step - 2;

                this.createdSteps.splice(index, 1);
                this.count--;

                this.$nextTick(() => {
                    for (let i = 0; i < this.steps.length; i++) {
                        this.steps[i].step = i + 1;
                    }
                });
            });

            EventBus.$on('validate-created-steps', (e) => {
                if (e.target.dataset.back) {
                    let exit = true;

                    this.steps.forEach(item => {
                        if (item.titleCampaign.trim() !== '' || item.descriptionCampaign.trim() !== '' || item.titleVote.trim() !== '' || item.descriptionVote.trim() !== '') {
                            exit = false;

                            return;
                        }
                    });

                    if (exit) return;
                }

                this.steps.forEach((step) => {
                    step.validate();
                });

                let validate = this.steps.every(item => item.dataIsValid === true);

                if (!validate) {
                    e.preventDefault();

                    EventBus.$emit('show-error-block');
                }
            });


        }
    }
</script>
