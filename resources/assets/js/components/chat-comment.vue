<template>
    <div class="chat__comment" v-show="active">
        <div class="chat__comment-left">
            <slot name="user"></slot>
        </div>

        <div class="chat__comment-right">
            <span class="chat__comment-status" v-if="statusExpert" title="expert">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 53.867 53.867"><path fill="#efce4a" d="M26.934 1.318l8.322 16.864 18.611 2.705L40.4 34.013l3.179 18.536-16.645-8.751-16.646 8.751 3.179-18.536L0 20.887l18.611-2.705z"/></svg>
            </span>
            <slot name="comment"></slot>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            show: null,
            statusExpert: false,
        },
        data() {
            return {
                active: false || this.show,
            }
        }
    }
</script>
