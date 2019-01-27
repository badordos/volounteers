<template>
    <div class="editor" ref="editor">
        <textarea name="about_desc" class="editable medium-editor-textarea editor__inner" ref="data"></textarea>
    </div>
</template>

<script>
    import $ from  'jquery';
    import MediumEditor from 'medium-editor';
    import MediumInsert from 'medium-editor-insert-plugin';

    import { EventBus } from './state-of-events';

    let editor = null;

    export default {
        props: {
            uploadData: {
                default: null,
            },
            maxLength: {
                default: 10000601
            },
            maxSizeImage: {
                default: 5120
            }
        },
        mounted() {
            this.$nextTick(() => {
                window.MediumInsert = MediumInsert.MediumInsert;

                editor = new MediumEditor('.editor__inner', {
                    imageDragging: false,
                    toolbar: {
                        buttons: ['bold', 'italic', 'quote', 'anchor', 'h2', 'h3', 'orderedlist', 'unorderedlist']
                    }
                });
                let $element = $('.editor__inner');
                $element.mediumInsert({
                    editor: editor,
                    addons: {
                        images: {
                            label: '<span class="icon-image"></span>',
                            actions: {
                                remove: {
                                    label: '<span class="icon-remove"></span>'
                                }
                            },
                            captions: false,
                            deleteScript: null,
                            preview: false,
                            autoGrid: null,
                        },
                        embeds: {
                            label: '<span class="icon-video"></span>',
                            actions: {
                                remove: {
                                    label: '<span class="icon-remove"></span>'
                                }
                            }
                        }
                    }
                });

                let images = $element.data('plugin_mediumInsertImages');

                images.add = () => {
                    let $file = $('<input type="file" accept=".jpg, .jpeg, .png, .bmp, .gif">');

                    $file.fileupload({add: (e, data) => {
                        let file = data.files[0];
                        let error = document.querySelector('.editor__error');

                        if (typeof file !== 'undefined') {
                            if (Math.ceil(file.size / 1024) > this.maxSizeImage) {
                                error.classList.add('active');

                                setTimeout(() => {
                                    error.classList.remove('active');
                                }, 1000);

                                return;
                            }
                        }

                        let reader  = new FileReader();

                        let loadListener = (name) => {
                            let result = {
                                files: [{
                                    url: reader.result,
                                }]
                            };
                            images.uploadDone(null, {
                                result: result
                            });
                            // set the upload progress to 1/1 (100%) which hides the progress bar
                            images.uploadProgressall(null, {context: images.$el, loaded: 1, total: 1});
                            images.core.triggerInput();
                        };

                        images.uploadAdd({target: null}, {autoUpload: false, files: data.files});

                        reader.addEventListener("load", loadListener.bind(reader, file.name), false);
                        reader.readAsDataURL(file);
                    }});

                    $file.click();
                };
            });
        },

        created() {
            EventBus.$on('validate-editor', (e) => {
                let editor = this.$refs.editor;
                let length = this.$refs.data.value.length;

                if (length > this.maxLength) {
                    editor.classList.add('error');
                    editor.classList.add('animate-error');

                    EventBus.$emit('show-error-editor');

                    setTimeout(()=> {
                        editor.classList.remove('animate-error');
                    }, 1000);

                    e.preventDefault();
                } else {
                    if (editor.classList.contains('error')) {
                        editor.classList.remove('error');
                    }

                    EventBus.$emit('hide-error-editor');
                }
            });

            if (this.uploadData) {
                this.$nextTick(() => {
                    this.$nextTick(() => {
                        let elem = document.querySelector('div.editable');

                        elem.click();
                        elem.innerHTML = this.uploadData;

                        document.querySelector('textarea.editable').value = this.uploadData;
                    });
                });
            }

            this.$nextTick(() => {
                this.$nextTick(() => {
                    let btn = document.querySelector('.medium-insert-buttons');
                    let error = document.createElement('span');

                    error.classList.add('editor__error');
                    error.innerHTML = 'File size too large';

                    btn.appendChild(error);
                });
            });
        }
    }
</script>