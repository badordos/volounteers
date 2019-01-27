<template>
    <section class="filter" :class="{'default': lineUp,
                                     'filter--filter': onlyFilter
                                    }">
        <div class="filter__inner" v-if="!onlyFilter">
            <div class="filter__inner-row">
                <slot name="title"></slot>
                <div class="filter__input">

                    <v-select :options="variants" label="title" max-height="261px" v-model="variants[0]" ref="select" :disabled="activeWorldWide">
                    </v-select>

                    <label>
                        <input type="checkbox" v-model="activeWorldWide" @click="acceptWorldWideFilter"><span class="checkbox"></span> Worldwide
                    </label>
                </div>

                <ul class="filter__verification" v-if="verification">
                    <li>
                        <a href="#!" @click.prevent="setVerification" :class="{'active': verified}">Verified</a>
                    </li>
                    <li>
                        <a href="#!" @click.prevent="setVerification" :class="{'active': notVerified}">Not verified</a>
                    </li>
                </ul>
            </div>

            <div class="filter__flags-inner">
                <button class="btn btn--filter" type="button" @click="activeFlags = !activeFlags" v-if="!lineUp && !disabledBtnFilter">Filter</button>

                <div class="filter__flags" v-show="activeFlags" v-if="!lineUp">
                    <button class="button-back" type="button" @click="activeFlags = !activeFlags">back</button>
                    <ul>
                        <li v-for="item in flags">
                            <label>
                                <input type="checkbox"><span class="checkbox"></span> {{ item }}
                            </label>
                        </li>
                    </ul>
                </div>

                <div class="filter__flags row" @click="activeFlagLink">
                    <ul v-if="lineUp">
                        <li v-for="item in flags">
                            <a href="#!" class="filter__flags-link" :class="{'active': filters[item]}"> {{ item }} </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>


        <div class="filter__inner" v-if="onlyFilter">
            <slot name="title"></slot>

            <div class="filter__flags-inner">
                <button class="btn btn--filter" type="button" @click="activeFlags = !activeFlags" v-if="!lineUp && !disabledBtnFilter">Filter</button>

                <div class="filter__flags" v-show="activeFlags" v-if="!lineUp">
                    <button class="button-back" type="button" @click="activeFlags = !activeFlags">back</button>
                    <ul>
                        <li v-for="item in flags">
                            <label>
                                <input type="checkbox"><span class="checkbox"></span> {{ item }}
                            </label>
                        </li>
                    </ul>
                </div>
            </div>

            <slot name="create-campaign"></slot>
        </div>
    </section>
</template>

<script>
    export default {
        props: {
            variants: {
                type: Array
            },
            flags: {
                type: Array
            },
            settings: {
                type: Array,
                default: () => []
            },
            disabledBtnFilter: {
                default: false,
            }
        },

        data() {
            return {
                filters: {},
                activeFlags: false,
                lineUp: this.settings.indexOf('row') !== -1,
                verification: this.settings.indexOf('verification') !== -1,
                onlyFilter: this.settings.indexOf('filter') !== -1,
                verified: false,
                notVerified: false,
                activeWorldWide: false,
            }
        },

        methods: {
            open() {
                this.achievementsActive = true;
            },
            close() {
                this.achievementsActive = false;
            },
            activeFlagLink(e) {
                e.preventDefault();

                if (e.target.classList.contains('filter__flags-link')) {
                    for (let key in this.filters) {
                        if (key == e.target.innerText) {
                            continue;
                        }

                        this.filters[key] = false;
                    }

                    this.filters[e.target.innerText] = !this.filters[e.target.innerText];

                }
            },
            setVerification(e) {
                if (e.target.innerText === 'Verified') {
                    this.verified = !this.verified;
                    this.notVerified = false;

                } else {
                    this.notVerified = !this.notVerified;
                    this.verified = false;
                }
            },
            acceptWorldWideFilter() {
                let select = this.$refs.select;

                if (!this.activeWorldWide) {
                    select.$data.mutableValue = '';
                }
            },
        },
        created() {
            this.flags.forEach((elem) => {
                this.$set(this.filters, elem, false);
            });
        }
    }
</script>
