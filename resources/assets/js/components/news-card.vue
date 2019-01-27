<template>
    <section class="news-card">
        <p class="news-card__date">
            {{ dateTime }}
        </p>
        <div class="news-card__inner" :style="{backgroundImage: `url(${preview})`}">
            <div class="news-card__content" @mouseenter="open" @mouseleave="close">
                <h2>{{ title }}</h2>
                <transition name="slide" @enter="start" @after-enter="end" @before-leave="start" @after-leave="end">
                    <div class="news-card__content-wrapper" v-show="active">
                        <slot name="description"></slot>
                    </div>
                </transition>
            </div>
        </div>
        <div class="news-card__gallery">
            <slot name="gallery"></slot>
        </div>
    </section>
</template>

<script>
    export default {
        props: {
            title: {
                required: true,
            },
            preview: null,
            dateTime: null,
        },

        data() {
            return {
                active: false
            }
        },

        methods: {
            open() {
              this.active = true;
            },
            close() {
                this.active = false;
            },
            start(el) {
                el.style.height = el.scrollHeight + 'px';
            },
            end(el) {
                el.style.height = '';
            }
        }
    }
</script>
