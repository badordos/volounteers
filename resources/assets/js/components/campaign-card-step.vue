<template>
    <section class="campaign-card campaign-card-step" :style="{backgroundImage: `url(${image})`}" v-if="active">
        <div class="campaign-card__inner">

            <div class="campaign-card__content">
                <slot name="title"></slot>
                <p class="campaign-card-step__step">
                    Now, step <span>{{ step }}</span>
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

                <a :href="linkView" class="btn reverse btn--arrow-right" v-if="notVerified">
                    Edit
                    <span class="arrow"></span>
                </a>
                <a target="_blank" :href="linkView" class="btn btn--bold btn--arrow-right" v-else>View <span class="arrow"></span></a>
            </div>
        </div>


    </section>
</template>

<script>
    export default {
        props: {
            step: null,
            linkView: null,
            image: null,
            notVerified: {
                default: false,
            }
        },

        data() {
            return {
                active: true,
            }
        }
    }
</script>
