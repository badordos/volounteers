<template>
    <span class="create-steps__vote-variants">
       <input type="text" class="create-steps__vote-variant" ref="input" v-model="val" :name="`steps[step${stepNumber}][vote][variants][]`" maxlength="60" autocomplete="off" @input="maxLengthObserver" :class="{'complete' : variantLimit}">
        <button type="button" class="btn btn--close popup__close" @click="deleteVariant" v-if="createdVariant"></button>
    </span>

</template>

<script>
    import { EventBus } from './state-of-events';

    export default {
        props: {
            stepNumber: null,
        },
        data() {
            return {
                elem: '',
                val: '',
                createdVariant: false,

                variantLimit: false,
            }
        },

        methods: {
            deleteVariant() {
                if (this.variantLimit) {
                    EventBus.$emit('removeVariantLimit');
                }

                this.$parent.$emit('delete', this);
            },
            maxLengthObserver(e) {
                let maxLength = e.target.getAttribute('maxlength');

                if (this.variantLimit && e.target.value.length == maxLength) return;

                if (e.target.value.length == maxLength) {
                    this.variantLimit = true;
                    this.$parent.limitVariants++;
                } else {
                    if (this.variantLimit) {
                        this.variantLimit = false;
                        this.$parent.limitVariants--;
                    }
                }
            }
        },

        created() {
            this.$nextTick(() => {
                this.elem = this.$refs.input;

                let maxLength = this.elem.getAttribute('maxlength');

                if (this.val.length == maxLength) {
                    this.variantLimit = true;
                    this.$parent.limitVariants++;
                }
            });
        }
    }
</script>
