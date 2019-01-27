<template>
    <div class="registration">
        <div class="registration__inner">

            <div class="registration__bot">
                <div class="registration__bot-image" :class="{ 'registration__bot-image--transform': activeThankSection}" ref="transform">
                    <img :src="robotImage" alt="robot">
                </div>
                <transition name="slider-fade" @after-leave="deleteTransform">
                    <div class="registration__bot-dialog" v-if="!activeThankSection">
                        <p>
                            {{ currentDialog }}
                        </p>
                    </div>
                </transition>
            </div>

            <transition name="slider" mode="out-in">

                <div class="registration__form" v-if="!activeThankSection" key="form">
                    <div class="registration__inner-row">
                        <h2>Sign-up</h2>
                        <slot name="social"></slot>
                    </div>

                    <form :action="action" method="POST" @submit="validateData" ref="form">
                        <input name="_token" type="hidden" class="visually-hidden" style="display: none;" ref="token">
                        <label class="label">
                            <h3>Username</h3>
                            <input type="text" name="name" @focus="inputName" @blur="blurName" @mouseenter="hoverName" v-model="userName" ref="userName" v-validate="namePattern" :class="{'error': errors.has('name') && userNameIsBlurry}">
                        </label>
                        <label class="label">
                            <h3>E-mail</h3>
                            <input type="text" name="email" @input="inputEmail" @blur="blurEmail" @mouseenter="hoverEmail" v-model="userEmail" ref="userEmail" v-validate="emailPattern" :class="{'error': errors.has('email') && userEmailIsBlurry}">
                        </label>
                        <label class="label">
                            <h3>Password</h3>
                            <input type="password" name="password" @input="inputPass" @blur="blurPass" v-model="userPass" ref="userPass" v-validate="passPattern" :class="{'error': errors.has('password') && userPassIsBlurry}">
                        </label>
                        <label class="label">
                            <h3>Repeat password</h3>
                            <input type="password" name="password_confirmation" @focus="inputRepeatPass" @blur="blurRepeatPass" v-model="userRepeatPass" ref="userRepeatPass" v-validate="passPattern" :class="{'error': errors.has('password_confirmation') && userRepeatPassIsBlurry}">
                        </label>

                        <div class="registration__inner-row">
                            <button type="submit" class="btn" :class="{'disabled' : !policy || !terms}" :disabled="!policy || !terms">Sign-up</button>

                            <div class="registration__checkboxes">
                                <label class="checkbox-container">
                                    <input type="checkbox" v-model="terms">
                                    <span class="checkbox"></span>
                                    I agree with <a target="_blank" href="/terms-of-service.pdf">terms</a> and conditions
                                </label>
                                <label class="checkbox-container">
                                    <input type="checkbox" v-model="policy">
                                    <span class="checkbox"></span>
                                    I agree to the privacy <a target="_blank" href="/privacy-policy.pdf">policy</a>
                                </label>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="registration__thank" v-else key="thank">
                    <slot name="thank"></slot>
                </div>


            </transition>
        </div>
    </div>
</template>

<script>
    const token = document.querySelector('meta[name="csrf-token"]').content;

    import VeeValidate from 'vee-validate';
    import Vue from 'vue';

    Vue.use(VeeValidate);

    export default {
        props: {
            robotImage: null,
            validation: null,
            dialog: null,
            action: {
                default: ''
            },
            oldValues: null,
            errorsData: null,
            finalScreen: {
                default: false,
            }
        },
        data() {
            return {
                policy: false,
                terms: false,
                dialogSteps: this.dialog || {
                    'welcome': `Hello, I am DREAMBOT.
                                Welcome to DREAMMACHINE-
                                Decentralize Volonteer
                                Campaign service.`,
                    'focus-name': 'I am DREAMBOT, wat’s your name?',
                    'blur-name': 'Nice to meet you ',
                    'taken-name': 'Sorry this username is already taken(',
                    'focus-email': 'Please write your mail this is important!',
                    'blur-email': 'Wow! Beautifull e-mail!',
                    'focus-pass': 'Oh) I dont look!',
                    'focus-repeat-pass': 'Repeat the password I really do not look)',
                    'warning': 'Please check your details.',
                    'passwords not equal': 'Passwords do not match',
                    'final': 'Congratulation!'
                },
                currentDialog: '',
                userName: '',
                userEmail: '',
                userPass: '',
                userRepeatPass: '',
                warnings: [],
                activeThankSection: false,
                namePattern: this.validation['name'] || 'required|alpha',
                emailPattern: this.validation['email'] || 'required|email',
                passPattern: this.validation['password'] || 'required|alpha_num|min:6',

                userNameIsBlurry: false,
                userEmailIsBlurry: false,
                userPassIsBlurry: false,
                userRepeatPassIsBlurry: false,
            }
        },

        methods: {
            hoverName() {
                if(this.errors.items.length) {
                    return;
                }

                this.currentDialog = this.dialogSteps['focus-name'];
            },
            inputName() {
                this.currentDialog = this.dialogSteps['focus-name'];
            },
            blurName(e) {
                this.userNameIsBlurry = true;

                let value = e.target.value;

                if (!this.errors.has('name')) {
                    if (e.target.classList.contains('error')) {
                        e.target.classList.remove('error');
                    }
                }


                if (this.errors.has('name')) {
                    this.currentDialog = this.dialogSteps['warning'];

                    return;
                }

                this.currentDialog = this.dialogSteps['blur-name'] + value;
            },
            hoverEmail() {
                if(this.errors.items.length) {
                    return;
                }

                this.currentDialog = this.dialogSteps['focus-email'];
            },
            inputEmail() {
                this.currentDialog = this.dialogSteps['focus-email'];
            },
            blurEmail() {
                this.userEmailIsBlurry = true;

                if (this.errors.has('email')) {
                    this.currentDialog = this.errors.first('email');

                    return;
                }

                this.currentDialog = this.dialogSteps['blur-email'];
            },
            inputPass() {
                this.currentDialog = this.dialogSteps['focus-pass'];
            },
            blurPass() {
                this.userPassIsBlurry = true;

                if (this.errors.has('password')) {
                    //this.currentDialog = this.dialogSteps['warning'];
                    this.currentDialog = this.errors.first('password');

                    return;
                }
            },
            inputRepeatPass() {
                if (this.errors.has('password')) {
                    this.currentDialog = this.errors.first('password');

                    return;
                }

                this.currentDialog = this.dialogSteps['focus-repeat-pass'];
            },
            blurRepeatPass() {
                this.userRepeatPassIsBlurry = true;

                if (this.errors.has('password_confirmation')) {
                    this.currentDialog = this.errors.first('password_confirmation').replace(/password_confirmation/, 'repeat password');

                    return;
                }
            },

            checkPasswords() {
                if (this.userPass !== this.userRepeatPass) {
                    this.$refs.userPass.classList.add('error');
                    this.$refs.userRepeatPass.classList.add('error');

                    return false;
                }

                return true;
            },
            validateData(e) {
                this.userNameIsBlurry = true;
                this.userEmailIsBlurry = true;
                this.userPassIsBlurry = true;
                this.userRepeatPassIsBlurry = true;

                this.$validator.validateAll().then(result => {
                   if (result) {
                       if (this.warnings.length) {
                           e.preventDefault();

                           this.currentDialog = this.dialogSteps['warning'];

                           return;
                       }

                       if (!this.checkPasswords()) {
                           e.preventDefault();

                           this.currentDialog = this.dialogSteps['passwords not equal'];

                           return;
                       }

                       this.currentDialog = this.dialogSteps['final'];
                   } else {
                       e.preventDefault();

                       this.currentDialog = this.dialogSteps['warning'];
                   }
                });
            },

            deleteTransform() {
                this.$refs.transform.classList.remove('registration__bot-image--transform');
            }
        },

        created() {
            this.currentDialog = this.dialogSteps['welcome'];

            this.$nextTick(() => {
               this.$refs.token.value = token;

               if (this.oldValues) {
                   if (this.oldValues.hasOwnProperty('name')) {
                       this.userName = this.oldValues['name']
                   }
                   if (this.oldValues.hasOwnProperty('email')) {
                       this.userEmail = this.oldValues['email']
                   }
               }

               if (this.errorsData) {
                   let data = this.errorsData,
                       errors = data['errors'],
                       text = data['text-error'];

                   if (errors.includes('name')) {
                       this.currentDialog = text;
                       this.$refs.userName.classList.add('error');
                   }

                   if (errors.includes('email')) {
                       this.currentDialog = text;
                       this.$refs.userEmail.classList.add('error');
                   }

                   if (errors.includes('password')) {
                       this.currentDialog = text;
                       this.$refs.userPass.classList.add('error');
                   }

                   if (errors.includes('repeated-password')) {
                       this.currentDialog = text;
                       this.$refs.userRepeatPass.classList.add('error');
                   }
               }

               if (this.finalScreen) {
                   setTimeout(() => {
                       this.activeThankSection = true;
                   }, 1000);
               }
            });
        }
    }
</script>
