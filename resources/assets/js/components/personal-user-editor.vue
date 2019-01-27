<template>
    <section class="personal-user">
        <div class="personal-user__inner">
            <div class="personal-user__inner-column">
                <div class="personal-user__photo personal-user__photo--editor" :style="{backgroundImage: `url(${photo})`}">

                    <div class="file-upload">
                        <label>
                            <input type="file" name="file">
                            <span class="btn btn--bold">Download new</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="personal-user__inner-column">
                <div class="personal-user__inner-header personal-user__inner-header--editor">
                    <h1 class="personal-user__name">
                        <input type="text" class="personal-user__edit" :value="name" required>
                    </h1>
                    <p class="auth-user__location auth-user__location--editor">
                        <svg>
                            <use xlink:href="#icon-location" />
                        </svg>
                        <span class="label select">
                            <v-select  :options="optionsForSelect" :placeholder="location" ref="select"></v-select>
                        </span>
                    </p>
                </div>

                <ul class="personal-user__inner-features personal-user__inner-features--editor">
                    <div class="personal-user__inner-column">
                        <slot name="column-1"></slot>
                    </div>
                    <div class="personal-user__inner-column">
                        <slot name="column-2"></slot>
                    </div>
                </ul>
            </div>

        </div>
        <button type="submit" class="btn btn--test personal-user__submit reverse">Submit</button>
    </section>
</template>

<script>
    export default {
        props: {
            photo: null,
            location: null,
            name: null,
        },

        data() {
            return {
                optionsForSelect: this.$root.optionsForSelect,
            }
        },
        methods: {},

    }
</script>
