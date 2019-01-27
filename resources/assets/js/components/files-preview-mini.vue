<template>
    <ul class="create-campaign__company-photos">
        <li v-for="(item, index) in preloadData" :style="{backgroundImage: `url(${item.src})`}" :key="item.id">
            <button type="button" class="btn btn--close popup__close" @click="deletePreload(item, index)"></button>
        </li>
        <li v-for="(item, index) in previews" :class="{ 'active' : index === previews.length - 1}" :key="item">
            <button type="button" class="btn btn--close popup__close" @click="deleteImage(index)"></button>
        </li>
    </ul>
</template>

<script>
    import { EventBus } from './state-of-events';
    const axios = require('axios');
    const token = document.querySelector('meta[name="csrf-token"]').content;

    export default {
        props: {
            preload: {
                default: () => [],
            },
            route: null,
        },
        data() {
            return {
                previews: [],
                id: 0,
                preloadData: [],
            }
        },
        methods: {
            readURL(input) {
                if (input.files && input.files[0]) {
                    let reader = new FileReader();

                    reader.onload = (e) => {
                        this.previews.push(this.id++);

                        this.$nextTick(() => {
                            let preview = document.querySelector('.create-campaign__company-photos .active');

                            preview.style.backgroundImage = `url(${e.target.result})`;
                        });
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            },

            deleteImage(index) {
                this.previews.splice(index, 1);

                EventBus.$emit('delete-preview', index);
            },

            deletePreload(item, index) {
                axios.post(this.route, {
                    id: item.id,
                    _token: token,
                }).then(() => {
                    this.preloadData.splice(index, 1);
                }).catch(error => {
                    EventBus.$emit('show-alert-error');
                    console.log(error);
                });
            }
        },
        created() {
            EventBus.$on('add-preview', (info) => {
                this.readURL(info);
            });

            if (this.preload.length) {
                this.preloadData = this.preload;
            }
        }
    }
</script>