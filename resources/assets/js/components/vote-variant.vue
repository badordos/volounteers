<template>
    <button type="button" class="btn btn--bold section-vote__btn" :class="{'reverse': active}" @click="select"><slot></slot> <span v-if="resultActive" class="section-vote__count">{{ countOfVotes}}</span></button>
</template>

<script>
    export default {
        props: {
            value: null,
            countOfVoting: null
        },
        data() {
            return {
                activeEvent: true,
                active: false,
                resultActive: false,
                countOfVotes: null,
            }
        },

        methods: {
            select() {
                if (this.activeEvent) {
                    this.$parent.$emit('select', this, this.value);
                }
            },
        },

        created() {
            if (this.countOfVoting) {
                this.countOfVotes = this.countOfVoting;
            }
        }
    }
</script>
