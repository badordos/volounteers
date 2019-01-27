<template>
    <section class="personal-user" :id="getId">
        <div class="personal-user__inner">
            <div class="personal-user__inner-column">
                <div class="personal-user__photo" :style="{backgroundImage: `url(${photo})`}">

                    <div class="file-upload" v-if="!photo">
                        <label>
                            <input type="file" name="file">
                            <span class="btn btn--bold">Download new</span>
                        </label>
                    </div>
                </div>
                <slot name="social"></slot>
            </div>

            <div class="personal-user__inner-column">
                <div class="personal-user__inner-header">
                    <h1 class="personal-user__name">{{ name }}</h1>
                    <p class="auth-user__location" v-if="location">
                        <svg>
                            <use xlink:href="#icon-location" />
                        </svg> {{ location }}
                    </p>
                </div>

                <ul class="personal-user__inner-features">
                    <slot></slot>
                </ul>
            </div>

        </div>
    </section>
</template>

<script>
    export default {
        props: {
            photo: null,
            location: null,
            name: null,
            id: {
                default: ''
            }
        },

        computed: {
            getId() {
                return this.id
            }
        },

        methods: {}
    }
</script>
