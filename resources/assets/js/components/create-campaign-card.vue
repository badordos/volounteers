<template>
    <section class="create-campaign-card">
        <div class="create-campaign-card__inner">

            <div class="create-campaign-card__inner-column">
                <span class="logo create-campaign-card__logo">
                    <img src="/images/logo.svg" alt="Dreamachine">
                </span>
                <div class="create-campaign-card__photo" :style="{backgroundImage: `url(${preloadImage})`}" ref="photoCard">
                    <ul class="create-campaign-card__buttons">
                        <li>
                            <div class="file-upload">
                                <label>
                                    <input type="file" name="preview_image" accept="image/*" @change="addImage">

                                    <transition name="fade" mode="out-in">
                                        <span class="btn" v-if="!activeChangeImage" key="upload">Upload image</span>
                                        <span class="btn" v-else key="change">Change image</span>
                                    </transition>
                                </label>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="create-campaign-card__inner-title">
                <slot name="title"></slot>
                <div class="create-campaign-card__content">
                    <slot name="content"></slot>
                </div>
            </div>

        </div>
    </section>
</template>

<script>
    export default {
        props: {
            preloadImage: {
                default: ''
            }
        },
        data() {
            return {
                activeSaveBtn: false,
                preloadedImage: false,
                activeChangeImage: false,
            }
        },

        methods: {
            readURL(input) {
                if (input.files && input.files[0]) {
                    let reader = new FileReader();

                    reader.onload = (e) => {
                        this.$refs.photoCard.style.backgroundImage = `url(${e.target.result})`;
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            },
            addImage(e) {
                this.readURL(e.target);
                this.activeChangeImage = true;
            },
        },
        created() {
            if (this.preloadImage.trim().length > 0) {
                this.preloadedImage = true;
                this.activeChangeImage = true;
            }
        }
    }
</script>
