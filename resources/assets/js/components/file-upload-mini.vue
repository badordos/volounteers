<template>
    <div class="file-upload" :class="{'disabled' : count.length > maxImage}">
        <label v-for="item in count" v-show="item.visible" :key="item.id">
            <input type="file" name='files[]' id="file" accept=".jpg, .jpeg, .png, .bmp, .gif" @change="addPreview">
            <span class="plus">+</span>
        </label>
    </div>
</template>

<script>
    import { EventBus } from './state-of-events';

    export default {
        props: {
            name: {
                default: 'file'
            },
            maxImage: {
                default: 10
            },
            maxSizeImage: {
                default: 10000
            }
        },
        data() {
            return {
                count: [{id: 0, visible: true}],
                current: 0,
            }
        },
        methods: {
            addPreview(e) {
                if (this.count.length > this.maxImage) {
                    return;
                }

                let file = e.target.files[0];

                if (typeof file !== 'undefined') {
                    if (Math.ceil(file.size / 1024) > this.maxSizeImage) {

                        e.target.value = '';
                        EventBus.$emit('show-error-upload');

                        return;
                    }
                }

                for (let i = 0; i < this.count.length; i++) {
                    this.count[i].visible = false;
                }

                this.current++;
                this.count.push({id: this.current, visible: true});
                EventBus.$emit('add-preview', e.target);
            }
        },

        created() {
            EventBus.$on('delete-preview', (index) => {
                this.count.splice(index, 1);
            });
        }
    }
</script>