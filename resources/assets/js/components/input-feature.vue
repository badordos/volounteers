<template>
    <div class="input-feature">
        <div class="input-feature__input">
            <input type="text" placeholder="Input..." @keyup.enter="inputData" @focus="keysIsEnable" @blur="keysIsDisable" @keydown.down="toggleDown" @keydown.up="toggleUp" @keydown.delete="remove">
        </div>
        <div class="input-feature__list" :class="{ 'active' : keysIsActive}" ref="list" v-if="listData.length > 0">
            <ul>
                <input-feature-item v-for="(item, index) in listData"
                                    :value="item"
                                    :key="index"
                                    :index="index"
                                    :items="listData"
                                    :children="items" />
            </ul>
        </div>
    </div>
</template>

<script>
    import inputFeatureItem from './input-feature-item.vue';

    export default {
        components: {
            inputFeatureItem
        },
        data() {
            return {
                listData: [],
                items: [],
                keysIsActive: false,
                currentIndex: 0,
                activateCurrentIndex: false
            }
        },
        methods: {
            inputData(e) {
                let value = e.target.value;

                if (value === '') return;

                this.listData.push(value);

                e.target.value = '';
            },
            keysIsEnable() {
                this.keysIsActive = true;
            },
            keysIsDisable() {
                this.keysIsActive = false;
                this.items.forEach(item => {
                    item.active = false;
                });
            },
            toggleDown() {
                if (!this.keysIsActive || this.listData.length === 0) return;

                if (!this.activateCurrentIndex) {
                    this.activateCurrentIndex = true;

                    this.items[this.currentIndex].active = true;

                    return;
                }
                if (this.items[this.currentIndex + 1]) {
                    this.items.forEach(item => {
                        item.active = false;
                    });

                    this.items[this.currentIndex + 1].active = true;
                    this.$refs.list.scrollTo(0, this.items[this.currentIndex + 1].$el.offsetTop - 10);

                    this.currentIndex++;
                } else {
                    this.items.forEach(item => {
                        item.active = false;
                    });
                    this.currentIndex  = 0;

                    this.$refs.list.scrollTo(0, this.items[this.currentIndex].$el.offsetTop - 10);
                    this.items[this.currentIndex].active = true;
                }
            },
            toggleUp() {
                if (!this.keysIsActive || this.listData.length === 0) return;

                if (this.items[this.currentIndex - 1]) {
                    this.items.forEach(item => {
                        item.active = false;
                    });

                    this.items[this.currentIndex - 1].active = true;
                    this.$refs.list.scrollTo(0, this.items[this.currentIndex - 1].$el.offsetTop - 10);

                    this.currentIndex--;
                } else {
                    this.currentIndex = this.listData.length - 1;

                    this.items.forEach(item => {
                        item.active = false;
                    });

                    this.items[this.currentIndex].active = true;
                    this.$refs.list.scrollTo(0, this.items[this.currentIndex - 1].$el.offsetTop - 10);
                }
            },

            remove() {
                if (!this.keysIsActive) return;

                this.listData.splice(this.currentIndex, 1);
            }
        },

        created() {
            this.items = this.$children;
        }
    }
</script>