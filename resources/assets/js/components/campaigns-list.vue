<template>
    <section class="campaigns-list">
        <div class="campaigns-list__inner">
            
            <slot></slot>
            

            <div class="campaigns-list__inner-row">
                <button type="button" class="btn btn--bold about-campaigns__btn" @click="showMoreCampaigns" v-if="activeBtnShow">{{ getTextBtn }}</button>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        props: {
            textBtn: {
                default: 'More campaigns'
            }
        },
        data() {
            return {
                countOfVisibleCampaigns: 12,
                campaigns: [],
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
            showMoreCampaigns() {
                let count = this.countOfShowed;

                for (let i = 0; i < this.countOfVisibleCampaigns; i++) {
                    if (this.campaigns[count + i]) {
                        this.campaigns[count + i].active = true;
                        this.countOfShowed++;

                        if (!this.campaigns[count + i + 1]) {
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
                this.campaigns = this.$children;

                for (let i = 0; i < this.countOfVisibleCampaigns; i++) {
                    if (this.campaigns[i]) {
                        this.campaigns[i].active = true;
                        this.countOfShowed++;

                        if (!this.campaigns[i + 1]) {
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