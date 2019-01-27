<template>
    <treeselect v-model="value" :multiple="true" :options="options" :searchable="false" />
</template>

<script>
    import Treeselect from '@riophae/vue-treeselect';
    import '@riophae/vue-treeselect/dist/vue-treeselect.css';

    export default {
        components: { Treeselect },
        props: {
            options: null,
            defaultValues: null,
        },
        data() {
            return {
                value: [],
            }
        },
        created() {
            this.value = this.defaultValues;
        }
    }
</script>