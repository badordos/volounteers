<template>
    <section class="campaign-card" :style="{backgroundImage: `url(${image})`}" :class="{ 'campaign-card--slider' : slider }" v-if="active">
        <div class="campaign-card__inner">

            <div class="campaign-card__content">
                <h3>
                    {{ title }}
                </h3>
                <p>
                    {{ description }}
                </p>
            </div>

            <slot></slot>

            <div class="campaign-card__bottom">
                <a href="#!" class="icon-share" @click.prevent>
                    <svg>
                        <use xlink:href="#icon-share" />
                    </svg>
                    Share
                    <div class="social campaign-card__social">
                        <ul>
                            <li>
                                <a target="_blank" href="#!">
                                    <svg>
                                        <use xlink:href="#icon-fb" />
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <a target="_blank" href="#!">
                                    <svg>
                                        <use xlink:href="#icon-twitter" />
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <a target="_blank" href="#!">
                                    <svg>
                                        <use xlink:href="#icon-google" />
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <a target="_blank" href="#!">
                                    <svg>
                                        <use xlink:href="#icon-reddit" />
                                    </svg>
                                </a>
                            </li>
                        </ul>
                    </div>
                </a>
                <a :href="link" class="btn btn--bold">{{ textLink }}</a>
            </div>
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
            link: null,
            textLink: {
                default: 'Read more'
            },
            slider: {
                default: false,
            },
            activated: {
                default: false,
            }
        },

        data() {
            return {
                active: false,
            }
        },

        created() {
            if (this.activated) {
                this.active = true;
            }
        }
    }
</script>
