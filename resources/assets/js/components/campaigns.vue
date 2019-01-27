<template>
    <section class="main-directions">
        <div class="main-directions__inner">

            <figure class="main-directions__image">
                <slot></slot>
             </figure>

             <ul class="main-directions__list">
                <li v-for="campaign in campaigns" :class="{ 'active': campaign.isActive }">
                    <a href="#!" @click.prevent="selectCampaign(campaign)">
                        {{ campaign.name }}
                    </a>
                </li>
             </ul>
        </div>
    </section>
</template>

<script>
    export default {
        data() {
            return {
                campaigns: []
            }
        },
        created() {
            this.campaigns = this.$children;
        },

        methods: {
            selectCampaign(selected) {
                this.campaigns.forEach( item => {
                    item.isActive = (item == selected);
                });
            }
        }
    }
</script>
