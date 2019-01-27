<template>
    <div class="upload-block__main" ref="uploadBlockMain" :style="{backgroundImage: `url(${srcImage})`}" :class="{'upload-block__main--video' : videoUploaded}">
        <transition name="fade">
            <button type="button" class="btn btn--close popup__close" v-if="showDeleteButton" @click="deleteContent"></button>
        </transition>
        <transition name="fade">
            <div class="upload-block__video-upload" v-if="videoUploaded">
                <b class="upload-block__video-upload--text">Video uploaded ({{ videoName }})</b>
                <div class="file-upload">
                    <label>
                        <input type="file" @change="addMainVideoPreviewCampaign" name="main_video_preview" accept=".jpg, .jpeg, .png, .bmp, .gif" size="10000000" ref="videoPreviewUploadMain">
                        <span class="btn">Change preview for video</span>
                    </label>
                </div>
            </div>
        </transition>

        <transition name="fade">
            <div class="preloader-mini" v-if="activePreloader">
                <div class="preloader-mini__rotating-border"></div>
            </div>
        </transition>

        <ul class="upload-block__main-buttons">
            <li>
                <div class="file-upload">
                    <label>
                        <input type="file" @change="addMainImageCampaign" name="main_image" accept=".jpg, .jpeg, .png, .bmp, .gif" size="10000000" ref="imageUploadMain">

                        <transition name="fade" mode="out-in">
                            <span class="btn btn--bold" v-if="!activeChangeImage" key="upload">Upload image</span>
                            <span class="btn btn--bold" v-else key="change">Change image</span>
                        </transition>
                    </label>
                </div>
            </li>
            <li>
                <div class="file-upload">
                    <label>
                        <input type="file" name="main_video" accept=".mp4, .webm" @change="addMainVideoCampaign" ref="videoUploadMain">

                        <transition name="fade" mode="out-in">
                            <span class="btn btn--bold" v-if="!activeChangeVideo" key="upload">Upload video (mp4, webm)</span>
                            <span class="btn btn--bold" v-else key="change">Change video (mp4, webm)</span>
                        </transition>
                    </label>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
    const axios = require('axios');
    const token = document.querySelector('meta[name="csrf-token"]').content;

    import { EventBus } from './state-of-events';

    export default {
        props: {
            preloadImage: {
                default: ''
            },
            preloadVideo: {
                default: false,
            },
            preloadVideoPreview: null,
            preloadVideoName: null,
            routeImage: null,
            routeVideo: null,
            routeUploadVideo: null,
            maxSizeImage: {
                default: 10000
            },
            maxSizeVideo: {
                default: 50000
            }
        },
        data() {
            return {
                videoUploaded: false,
                activeDeleteButton: false,
                preloadedImage: false,
                preloadedVideo: false,
                srcImage: null,
                activePreloader: false,
                videoName: null,

                activeChangeImage: false,
                activeChangeVideo: false,
            }
        },
        computed: {
            showDeleteButton() {
                return this.preloadedImage || this.preloadedVideo || this.activeDeleteButton;
            }
        },
        methods: {
            readURL(input) {
                if (input.files && input.files[0]) {
                    let reader = new FileReader();

                    reader.onload = (e) => {
                        //this.$refs.uploadBlockMain.style.backgroundImage = `url(${e.target.result})`;
                        this.srcImage = e.target.result;
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            },
            addMainImageCampaign(e) {
                let file = e.target.files[0];

                if (typeof file !== 'undefined') {
                    if (Math.ceil(file.size / 1024) > this.maxSizeImage) {
                        e.target.value = '';
                        EventBus.$emit('show-error-upload');

                        return;
                    }
                }

                this.readURL(e.target);
                this.$refs.videoUploadMain.value = '';
                this.videoUploaded = false;
                this.activeDeleteButton= true;
                this.activeChangeImage = true;
                this.activeChangeVideo = false;
            },
            addMainVideoCampaign(e) {
                let file = e.target.files[0];

                this.videoUploaded = false;

                if (typeof file !== 'undefined') {
                    if (Math.ceil(file.size / 1024) > this.maxSizeVideo) {
                        e.target.value = '';
                        EventBus.$emit('show-error-upload');

                        return;
                    }
                }

                this.videoName = file.name;
                let formData = new FormData();

                formData.append('type', 'upload');
                formData.append('file', file, file.name);
                formData.append('_token', token);

                this.activePreloader = true;

                axios.post(this.routeUploadVideo, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(res => {
                    this.activePreloader = false;

                    this.videoUploaded = true;
                    this.$refs.imageUploadMain.value = '';
                    this.$refs.videoUploadMain.value = '';
                    this.activeDeleteButton= true;
                    this.activeChangeImage = false;
                    this.activeChangeVideo = true;

                    if (res.data['preview']) {
                        this.srcImage = res.data['preview'];
                    }
                }).catch(error => {
                    this.activePreloader = false;
                    this.srcImage = '';

                    EventBus.$emit('show-alert-error');
                    console.log(error);
                });
            },
            addMainVideoPreviewCampaign(e) {
                let file = e.target.files[0];

                if (typeof file !== 'undefined') {
                    if (Math.ceil(file.size / 1024) > this.maxSizeImage) {
                        e.target.value = '';
                        EventBus.$emit('show-error-upload');

                        return;
                    }
                }

                this.readURL(e.target);
            },

            deleteContent() {
                if (this.preloadedImage) {
                    axios.post(this.routeImage, {
                        _token: token,
                    }).then(() => {
                        this.preloadedImage = false;
                        this.srcImage = '';
                        this.activeDeleteButton= false;
                        this.activeChangeImage = false;
                        this.preloadedVideo = false;
                        this.videoUploaded = false;
                        this.activeChangeVideo = false;
                    }).catch(error => {
                        EventBus.$emit('show-alert-error');
                        console.log(error);
                    });
                } else if (this.preloadedVideo) {
                    axios.post(this.routeVideo, {
                        _token: token,
                    }).then(() => {
                        this.preloadedVideo = false;
                        this.videoUploaded = false;
                        this.activeDeleteButton= false;
                        this.srcImage = '';
                        this.activeChangeVideo = false;
                        this.preloadedImage = false;
                        this.srcImage = '';
                        this.activeChangeImage = false;
                    }).catch(error => {
                        EventBus.$emit('show-alert-error');
                        console.log(error);
                    });
                } else {
                    this.$refs.videoUploadMain.value = '';
                    this.$refs.imageUploadMain.value = '';
                    this.srcImage = '';
                    if (this.videoUploaded) {
                        this.$refs.videoPreviewUploadMain.value = '';
                    }
                    this.srcImage = '';
                    this.videoUploaded = false;
                    this.activeDeleteButton= false;
                    this.activeChangeImage = false;
                    this.activeChangeVideo = false;
                }
            }
        },
        created() {
            if (this.preloadImage) {
                this.srcImage = this.preloadImage;
                this.activeChangeImage = true;
            }

            if (this.preloadImage.length > 0) {
                this.preloadedImage = true;
            } else if (this.preloadVideo) {
                this.preloadedVideo = true;
                this.videoUploaded = true;
                this.videoName = this.preloadVideoName;
                this.activeChangeVideo = true;

                if (this.preloadVideoPreview) {
                    this.srcImage = this.preloadVideoPreview;
                }
            }
        }
    }
</script>