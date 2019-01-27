<template>
    <li>
        <section class="personal-user__feature personal-user__feature--editor list" v-if="getList">
            <slot name="title"></slot>
            <list :options="options" :defaultValues="defaultValues" />
        </section>
        <section class="personal-user__feature personal-user__feature--editor date-picker" v-else-if="getDate">
            <slot name="title"></slot>
            <slot></slot>
        </section>
        <section class="personal-user__feature personal-user__feature--editor date-picker" v-else-if="getInput">
            <slot name="title"></slot>
            <slot></slot>
        </section>
    </li>
</template>

<script>
    import list from './list.vue';

    export default {
        components: { list },
        props: {
            list: {
                default: false
            },
            options: null,
            defaultValues: null,
            type: null
        },

        data() {
            return {

            }
        },

        computed: {
            getList() {
                return this.type === 'list';
            },
            getDate() {
                return this.type === 'date';
            },
            getInput() {
                return this.type === 'input';
            }
        }
    }
</script>