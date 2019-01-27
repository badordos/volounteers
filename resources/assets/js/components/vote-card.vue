<template>
    <section class="vote-card" :style="{backgroundImage: `url(${image})`}" :class="{ 'vote-card--slider' : slider }" v-if="active">
        <div class="vote-card__inner">

            <div class="vote-card__inner-row">
                <h3>
                    {{ title }}
                </h3>
                <slot name="social"></slot>
            </div>

            <div class="vote-card__content">
                <p>
                    {{ description }}
                </p>
            </div>
            <slot name="more"></slot>

            <transition name="fade">
                <div class="vote-card__question" v-show="!activeThank" @click="getVote">
                    <slot name="button-vote"></slot>
                </div>
            </transition>


            <transition name="fade">
                <div class="vote-card__thank" v-show="activeThank">
                <span>
                    Thank you
                </span>
                </div>
            </transition>

        </div>
    </section>
</template>

<script>
    export default {
        props: {
            title: {
                required: true
            },
            description: {
                required: true
            },
            image: {
                required: true
            },
            popupTitle: null,
            popupDescription: null,
            popupImage: null,
            popupVariants: null,
            slider: {
                default: false
            },
            route: null,
            disabled: {
                default: false,
            },
            activated: {
                default: false,
            }
        },
        data() {
            return {
                activeThank: false,
                active: false,
            }
        },
        methods: {
            getVote(e) {
                if (!e.target.hasAttribute('href')) {
                    this.$root.$emit('popup', {
                        'title': this.popupTitle,
                        'description': this.popupDescription,
                        'image': this.popupImage,
                        'variants': this.popupVariants,
                        'route': this.route
                    }, this);
                }
            }
        },
        created() {
            if (this.disabled) {
                this.activeThank = true;
            }

            if (this.activated) {
                this.active = true;
            }
        }
    }
</script>
