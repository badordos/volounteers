<template>
    <div class="auth-block" :class="isActive ? 'active' : ''">
        <slot></slot>
    </div>
</template>

<script>
    export default {
        props: {
            isActive: {
                name: 'is-active',
                default: 'false'
            }
        },

        data() {
            return {
                active: 'true'
            }
        },
        created(){
            this.$nextTick(()=>{
                document.querySelector("#screen").setAttribute('value', screen.width+'/'+screen.height);
            });
        }
    }
</script>
