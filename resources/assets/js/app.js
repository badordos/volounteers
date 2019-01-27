(function() {

    // проверяем поддержку
    if (!Element.prototype.matches) {

        // определяем свойство
        Element.prototype.matches = Element.prototype.matchesSelector ||
            Element.prototype.webkitMatchesSelector ||
            Element.prototype.mozMatchesSelector ||
            Element.prototype.msMatchesSelector;

    }

    // проверяем поддержку
    if (!Element.prototype.closest) {

        // реализуем
        Element.prototype.closest = function(css) {
            var node = this;

            while (node) {
                if (node.matches(css)) return node;
                else node = node.parentElement;
            }
            return null;
        };
    }

})();

(function() {
    var arr = [window.Element, window.CharacterData, window.DocumentType];
    var args = [];

    arr.forEach(function (item) {
        if (item) {
            args.push(item.prototype);
        }
    });

    // from:https://github.com/jserz/js_piece/blob/master/DOM/ChildNode/remove()/remove().md
    (function (arr) {
        arr.forEach(function (item) {
            if (item.hasOwnProperty('remove')) {
                return;
            }
            Object.defineProperty(item, 'remove', {
                configurable: true,
                enumerable: true,
                writable: true,
                value: function remove() {
                    this.parentNode.removeChild(this);
                }
            });
        });
    })(args);
})();

if (!Array.from) {
    Array.from = (function() {
        var toStr = Object.prototype.toString;
        var isCallable = function(fn) {
            return typeof fn === 'function' || toStr.call(fn) === '[object Function]';
        };
        var toInteger = function (value) {
            var number = Number(value);
            if (isNaN(number)) { return 0; }
            if (number === 0 || !isFinite(number)) { return number; }
            return (number > 0 ? 1 : -1) * Math.floor(Math.abs(number));
        };
        var maxSafeInteger = Math.pow(2, 53) - 1;
        var toLength = function (value) {
            var len = toInteger(value);
            return Math.min(Math.max(len, 0), maxSafeInteger);
        };

        // Свойство length метода from равно 1.
        return function from(arrayLike/*, mapFn, thisArg */) {
            // 1. Положим C равным значению this.
            var C = this;

            // 2. Положим items равным ToObject(arrayLike).
            var items = Object(arrayLike);

            // 3. ReturnIfAbrupt(items).
            if (arrayLike == null) {
                throw new TypeError('Array.from requires an array-like object - not null or undefined');
            }

            // 4. Если mapfn равен undefined, положим mapping равным false.
            var mapFn = arguments.length > 1 ? arguments[1] : void undefined;
            var T;
            if (typeof mapFn !== 'undefined') {
                // 5. иначе
                // 5. a. Если вызов IsCallable(mapfn) равен false, выкидываем исключение TypeError.
                if (!isCallable(mapFn)) {
                    throw new TypeError('Array.from: when provided, the second argument must be a function');
                }

                // 5. b. Если thisArg присутствует, положим T равным thisArg; иначе положим T равным undefined.
                if (arguments.length > 2) {
                    T = arguments[2];
                }
            }

            // 10. Положим lenValue равным Get(items, "length").
            // 11. Положим len равным ToLength(lenValue).
            var len = toLength(items.length);

            // 13. Если IsConstructor(C) равен true, то
            // 13. a. Положим A равным результату вызова внутреннего метода [[Construct]]
            //     объекта C со списком аргументов, содержащим единственный элемент len.
            // 14. a. Иначе, положим A равным ArrayCreate(len).
            var A = isCallable(C) ? Object(new C(len)) : new Array(len);

            // 16. Положим k равным 0.
            var k = 0;
            // 17. Пока k < len, будем повторять... (шаги с a по h)
            var kValue;
            while (k < len) {
                kValue = items[k];
                if (mapFn) {
                    A[k] = typeof T === 'undefined' ? mapFn(kValue, k) : mapFn.call(T, kValue, k);
                } else {
                    A[k] = kValue;
                }
                k += 1;
            }
            // 18. Положим putStatus равным Put(A, "length", len, true).
            A.length = len;
            // 20. Вернём A.
            return A;
        };
    }());
}

window.Vue = require('vue');
import('jquery', /* webpackChunkName: "js/jquery" */).then(jq => {
   window.jQuery = jq;
});

import 'promise-polyfill/src/polyfill';
import vSelect from 'vue-select';
import VueImg from 'v-img';

import { swiperSlide } from 'vue-awesome-swiper';

Vue.component('v-select', vSelect);
Vue.use(VueImg);

const token = document.querySelector('meta[name="csrf-token"]').content;
const axios = require('axios');
import { EventBus } from './components/state-of-events';

const componentsHeader = {
    'auth-block': require('./components/auth-block.vue'),
    'auth-user': require('./components/auth-user.vue'),
    'cookies': require('./components/cookies.vue'),
    'alert-error': require('./components/alert-error.vue'),
};

const header = new Vue({
    components: componentsHeader,
    el: '.header',
    data: {
        isActiveAuthBlock: false,
        isActiveMenu: false,
        activeErrorAlert: false,
    },
    computed: {
        toggleClassMenu() {
            return this.isActiveMenu ? 'active' : '';
        }
    },
    methods: {
        toggleMenu() {
            this.isActiveMenu = !this.isActiveMenu;
        }
    },

    created() {
        EventBus.$on('show-alert-error', () => {
           this.activeErrorAlert = true;

           setTimeout(() => {
               this.activeErrorAlert = false;
           }, 5000);
        });
    }
});

const componentsMain = {
    'swiper-slide': swiperSlide,
    'auth-user': require('./components/auth-user.vue'),
    'campaigns': require('./components/campaigns.vue'),
    'campaign': require('./components/campaign.vue'),
    'test': require('./components/test.vue'),
    'test-question': require('./components/test-question.vue'),
    'test-finish': require('./components/test-finish.vue'),
    'slider-campaigns': require('./components/slider-campaigns.vue'),
    'campaign-card': require('./components/campaign-card.vue'),
    'slider-stories': require('./components/slider-stories.vue'),
    'story': require('./components/story.vue'),
    'stories-output': require('./components/stories-output.vue'),
    'stories-thumbs': require('./components/stories-thumbs.vue'),
    'app-video': require('./components/video.vue'),
    'app-filter': require('./components/filter.vue'),
    'slider-vote': require('./components/slider-vote.vue'),
    'vote-card': require('./components/vote-card.vue'),
    'popup': require('./components/popup.vue'),
    'photo-gallery': require('./components/video-gallery.vue'),
    'gallery-item': require('./components/video-gallery-item.vue'),
    'tabs': require('./components/tabs.vue'),
    'tab': require('./components/tab.vue'),
    'accordion-steps': require('./components/accordion-steps.vue'),
    'accordion-step': require('./components/accordion-step.vue'),
    'vote': require('./components/vote.vue'),
    'vote-variant': require('./components/vote-variant.vue'),
    'chat': require('./components/chat.vue'),
    'comment': require('./components/chat-comment.vue'),
    'news-card': require('./components/news-card.vue'),
    'mini-gallery': require('./components/mini-gallery.vue'),
    'mini-gallery-item': require('./components/mini-gallery-item.vue'),
    'slider-news': require('./components/slider-news.vue'),
    'personal-user': require('./components/personal-user.vue'),
    'personal-feature': require('./components/personal-feature.vue'),
    'personal-list-item': require('./components/personal-list-item.vue'),
    'about-campaigns': require('./components/about-campaigns.vue'),
    'achievements': require('./components/achievements.vue'),
    'achievement-card': require('./components/achievement-card.vue'),
    'campaign-card-step': require('./components/campaign-card-step.vue'),
    'create-steps': require('./components/campaign-steps-create.vue'),
    'create-step': require('./components/campaign-step-create.vue'),
    'create-vote': require('./components/create-vote.vue'),
    'create-vote-variant': require('./components/create-vote-variant.vue'),
    'editor': () => import('./components/editor.vue' /* webpackChunkName: "js/editor" */),
    'create-campaign-card': require('./components/create-campaign-card.vue'),
    'registration': () => import('./components/registration.vue' /* webpackChunkName: "js/registration" */),
    'personal-user-editor': require('./components/personal-user-editor.vue'),
    'personal-feature-edit': () => import('./components/personal-feature-edit.vue' /* webpackChunkName: "js/personal-feature-edit" */),
    'app-date-picker': () => import('./components/date-picker.vue' /* webpackChunkName: "js/date-picker" */),
    'input-feature': require('./components/input-feature.vue'),
    'file-upload-mini': require('./components/file-upload-mini.vue'),
    'files-preview-mini': require('./components/files-preview-mini.vue'),
    'file-upload-main': require('./components/file-upload-main.vue'),
    'campaigns-list': require('./components/campaigns-list.vue'),
    'vote-list': require('./components/vote-list.vue'),
};

const main = new Vue({
    components: componentsMain,
    el: '.main',
    data: {
        activePopup: false,
        popupContent: {
            title: '',
            description: '',
            image: '',
            variants: [],
            routeOfVoteCard: null,
        },
        selectedVariant: false,
        valueOfVariant: null,
        currentVoteCard: null,
        idOfGallery: 1,
        activeHide: false,
        activeJoinPopup: false,
        lengthOfPopupTextArea: 150,
        worldFilterCreate: false,
        optionsForSelect: ['Prague', 'Paris', 'Rome', 'New York', 'London'],
        activeCreateCampaignPopup: false,

        campaignDirectionValue: '',
        campaignLocationValue: '',

        activeErrorBlock: false,
        activeErrorEditor: false,
        activeSendDataEditor: false,
        activeErrorUpload: false,

        testCategory: '',

        keyboardSlidersControl: [],
        keyboardSliderActive: null,

        //лимит символов на шаге 1 создания компании
        nameLimit: false,
        descriptionLimit: false,
    },
    methods: {
        vote(e) {
            for (let i = 0; i < e.target.parentElement.children.length; i++) {
                if (e.target.parentElement.children[i].classList.contains('active'))  {
                    e.target.parentElement.children[i].classList.remove('active');
                }
            }

            e.target.classList.add('active');
            this.valueOfVariant = this.popupContent.variants.indexOf(e.target.innerText);

            this.selectedVariant = true;
        },

        sendVote() {
            this.selectedVariant = false;
            this.activePopup = false;

            axios.post(this.popupContent.routeOfVoteCard, {
                selectedVoteVariant: this.valueOfVariant,
                _token: token,
            }).then(() => {
                this.currentVoteCard.$data.activeThank = true;
                this.popupContent.variants = [];
            }).catch(error => {
                console.log(error);
            });

            this.currentVoteCard.$data.activeThank = true;
            this.popupContent.variants = [];
        },

        closeVote() {
            this.activePopup = false;
            this.selectedVariant = false;

            this.popupContent.variants = [];
        },

        closePopup(e) {
            if(!!!e.target.closest('.popup__inner')) {
                this.activePopup = false;
                this.selectedVariant = false;

                this.popupContent.variants = [];
            }
        },

        openHide(e) {
            e.preventDefault();

            this.activeHide = true;
        },

        closeHide() {
            this.activeHide = false;
        },

        closeHidePopup(e) {
            if(!!!e.target.closest('.popup__inner')) {
                this.activeHide = false;
            }
        },

        openJoin() {
            this.activeJoinPopup = true;
        },

        closeJoin() {
            this.activeJoinPopup = false;
        },

        closeJoinPopup(e) {
            if(!!!e.target.closest('.popup__inner')) {
                this.activeJoinPopup = false;
            }
        },

        closeCreateCampaignPopup(e) {
            if(!!!e.target.closest('.popup__inner')) {
                this.activeCreateCampaignPopup = false;
            }
        },

        closeCreateCampaignPopupBtn() {
            this.activeCreateCampaignPopup = false;
        },

        openCreateCampaignPopup() {
            this.activeCreateCampaignPopup = true;
        },

        deleteAccount() {
            if (confirm('Do you really want to delete your account?')) {
                console.log('yes');
            } else {
                console.log('no');
            }
        },
        acceptWorldWideFilter() {
            let select = this.$refs.select;

            if (!this.worldFilterCreate) {
                select.$data.mutableValue = '';
            }
        },
        campaignDirection() {
            return this.$refs.directionCampaign;
        },
        campaignLocation() {
            return this.$refs.select;
        },
        getLabelDirection(id) {
            this.$nextTick(() => {
                let label = this.campaignDirection().mutableOptions.find(item => {
                   return item.value == id;
                });

                if (label) {
                    this.campaignDirection().mutableValue = { label: label.label, value: id};
                }
            });
        },
        getLabelLocation(id) {
            this.$nextTick(() => {
                let label = this.campaignLocation().mutableOptions.find(item => {
                    return item.value == id;
                });

                if (label) {
                    this.campaignLocation().mutableValue = { label: label.label, value: id};
                }
            });
        },
        validateCreateStep1(e) {
            let title = this.$refs.titleCampaign;
            let description = this.$refs.descriptionCampaign;
            let scroll = description.closest('.scroll');
            let volonteers = this.$refs.volonteersCampaign;
            let direction = this.$refs.directionCampaign.$el.querySelector('.dropdown-toggle');
            let location = this.$refs.select.$el.querySelector('.dropdown-toggle');

            if (this.campaignDirection().mutableValue) {
                this.$refs.category_id.value = this.campaignDirection().mutableValue.value || '';
            } else {
                this.$refs.category_id.value = '';
            }
            if (this.campaignLocation().mutableValue) {
                this.$refs.city_id.value = this.campaignLocation().mutableValue.value || '';
            } else {
                this.$refs.city_id.value = '';
            }

            if (title.value.trim() === '') {
                title.classList.add('error');
                title.classList.add('animate-error');

                setTimeout(()=> {
                    title.classList.remove('animate-error');
                }, 1000);

                e.preventDefault();
            } else {
                if (title.classList.contains('error')) {
                    title.classList.remove('error');
                }
            }

            if (description.value.trim() === '') {
                scroll.classList.add('error');
                scroll.classList.add('animate-error');

                setTimeout(()=> {
                    scroll.classList.remove('animate-error');
                }, 1000);

                e.preventDefault();
            } else {
                if (scroll.classList.contains('error')) {
                    scroll.classList.remove('error');
                }
            }

            if (!this.campaignDirection().mutableValue) {
                direction.classList.add('error');
                direction.classList.add('animate-error');

                setTimeout(()=> {
                    direction.classList.remove('animate-error');
                }, 1000);

                e.preventDefault();
            } else {
                if (direction.classList.contains('error')) {
                    direction.classList.remove('error');
                }
            }

            if (!this.campaignLocation().mutableValue && !this.worldFilterCreate) {
                location.classList.add('error');
                location.classList.add('animate-error');

                setTimeout(()=> {
                    location.classList.remove('animate-error');
                }, 1000);

                e.preventDefault();
            } else {
                if (location.classList.contains('error')) {
                    location.classList.remove('error');
                }
            }

            if (volonteers.value.trim() === '') {
                volonteers.classList.add('error');
                volonteers.classList.add('animate-error');

                setTimeout(()=> {
                    volonteers.classList.remove('animate-error');
                }, 1000);

                e.preventDefault();
            } else {
                if (volonteers.classList.contains('error')) {
                    volonteers.classList.remove('error');
                }
            }
        },
        validateCreateStep3(e) {
            EventBus.$emit('validate-created-steps', e);
        },
        validateCreateStep4(e) {
            EventBus.$emit('validate-editor', e);
        },
        maxLengthObserverName(e) {
            let maxLength = e.target.getAttribute('maxlength');

            if (e.target.value.length == maxLength) {
                this.nameLimit = true;
            } else {
                if (this.nameLimit) {
                    this.nameLimit = false;
                }
            }
        },
        maxLengthObserverDescription(e) {
            let maxLength = e.target.getAttribute('maxlength');

            if (e.target.value.length == maxLength) {
                this.descriptionLimit = true;
            } else {
                if (this.descriptionLimit) {
                    this.descriptionLimit = false;
                }
            }
        }
    },
    created() {
        Math.easeInOutQuad = function (t, b, c, d) {
            t /= d/2;
            if (t < 1) return c/2*t*t + b;
            t--;
            return -c/2 * (t*(t-2) - 1) + b;
        };

        this.$on('popup', (content, e) => {
            this.popupContent.title = content.title;
            this.popupContent.description = content.description;
            this.popupContent.image = content.image;
            this.popupContent.variants = content.variants;
            this.popupContent.routeOfVoteCard = content.route;

            this.activePopup = true;

            this.currentVoteCard = e;
        });

        EventBus.$on('show-error-block', () => {
            this.activeErrorBlock = true;
        });

        EventBus.$on('show-error-editor', () => {
            this.activeErrorEditor = true;
        });

        EventBus.$on('hide-error-editor', () => {
            this.activeErrorEditor = false;
            this.activeSendDataEditor = true;
        });

        EventBus.$on('show-error-upload', () => {
            this.activeErrorUpload = true;

            setTimeout(() => {
                this.activeErrorUpload = false;
            }, 2000);
        });

        EventBus.$on('setCategory', category => {
           this.testCategory = category;
        });

        EventBus.$on('show-join-popup', () => {
            this.openJoin();
        });

        EventBus.$on('add-slider-component', slider => {
            this.keyboardSlidersControl.push(slider);
        });

        EventBus.$on('change-keyboard-control', slider => {
            this.keyboardSliderActive = slider;

            for (let i = 0; i < this.keyboardSlidersControl.length; i++) {
                let keyboard = this.keyboardSlidersControl[i].$refs.mySwiper.swiper.keyboard;

                if (this.keyboardSlidersControl[i] == slider) {
                    keyboard.enable();
                } else {
                    keyboard.disable();
                    this.keyboardSlidersControl[i].keyboardActive = false;
                }
            }
        });

        function throttle(fn, wait) {
            var time = Date.now();
            return function() {
                if ((time + wait - Date.now()) < 0) {
                    fn();
                    time = Date.now();
                }
            }
        }

        document.addEventListener('scroll', throttle(() => {
            if (this.keyboardSlidersControl.length === 0) return;

            if (this.keyboardSliderActive === null) {
                this.keyboardSlidersControl.forEach(slider => {
                    slider.$refs.mySwiper.swiper.keyboard.disable();
                });

                let newSearch = true;

                for (let i = 0; i < this.keyboardSlidersControl.length; i++) {
                    let keyboard = this.keyboardSlidersControl[i].$refs.mySwiper.swiper.keyboard;

                    if (this.keyboardSlidersControl[i].Visible() && this.keyboardSlidersControl[i].indicator === 'stories') {
                        keyboard.enable();
                        newSearch = false;

                        return;
                    }
                }

                if (newSearch) {
                    for (let i = 0; i < this.keyboardSlidersControl.length; i++) {
                        let keyboard = this.keyboardSlidersControl[i].$refs.mySwiper.swiper.keyboard;

                        if (this.keyboardSlidersControl[i].Visible()) {
                            keyboard.enable();
                            newSearch = false;

                            return;
                        }
                    }
                }
            } else {
                if (!this.keyboardSliderActive.Visible()) {
                    this.keyboardSlidersControl.forEach(slider => {
                        slider.$refs.mySwiper.swiper.keyboard.disable();
                    });

                    let newSearch = true;

                    for (let i = 0; i < this.keyboardSlidersControl.length; i++) {
                        let keyboard = this.keyboardSlidersControl[i].$refs.mySwiper.swiper.keyboard;

                        if (this.keyboardSlidersControl[i].Visible() && this.keyboardSlidersControl[i].indicator === 'stories') {
                            keyboard.enable();
                            newSearch = false;

                            return;
                        }
                    }

                    if (newSearch) {
                        for (let i = 0; i < this.keyboardSlidersControl.length; i++) {
                            let keyboard = this.keyboardSlidersControl[i].$refs.mySwiper.swiper.keyboard;

                            if (this.keyboardSlidersControl[i].Visible()) {
                                keyboard.enable();
                                newSearch = false;

                                return;
                            }
                        }
                    }
                }
            }

        }, 20));

        //swiper keyboard active
        this.$nextTick(() => {
            for (let i = 0; i < this.keyboardSlidersControl.length; i++) {
                let keyboard = this.keyboardSlidersControl[i].$refs.mySwiper.swiper.keyboard;

                if (this.keyboardSlidersControl[i].Visible()) {
                    keyboard.enable();

                    return;
                }
            }
        });

        //поддержка autofocus
        this.$nextTick(() => {
            document.querySelectorAll('[autofocus]').forEach(item => {
                item.focus();
            });
        });

        //поддержка checked
        this.$nextTick(() => {
            document.querySelectorAll('[checked]').forEach(item => {
                item.click();
            });
        });

        //переносим кнопку edit
        this.$nextTick(() => {
            document.querySelectorAll('.btn--edit').forEach(item => {
                item.previousElementSibling.appendChild(item);
            });
        });

        //уведомляем о том, что превышен лимит символов(max-length) при первой загрузке страницы create-step-1
        this.$nextTick(() => {
            setTimeout(() => {
                if (this.$refs.titleCampaign && this.$refs.descriptionCampaign) {
                    let maxLengthName = this.$refs.titleCampaign.getAttribute('maxlength');
                    let maxLengthDescription = this.$refs.descriptionCampaign.getAttribute('maxlength');

                    if (this.$refs.titleCampaign.value.length == maxLengthName) {
                        this.nameLimit = true;
                    }

                    if (this.$refs.descriptionCampaign.value.length == maxLengthDescription) {
                        this.descriptionLimit = true;
                    }
                }
            }, 100);
        });
    }
});

//отслеживаем клик по сайту, чтобы закрыть окно авторизации

document.body.addEventListener('click', (e) => {
    let target = e.target.closest('.auth-block') || e.srcElement.closest('.auth-block');

    if (!target && header.$data.isActiveAuthBlock) {
        header.$data.isActiveAuthBlock = false;
    }
});

function showSite() {
    let preloader = document.querySelector('.preloader');
    let fps = 25;

    if (preloader) {
        preloader.style.opacity = 1;

        let timer = setInterval(() => {
            preloader.style.opacity -= .25;

            if (preloader.style.opacity <= 0) {
                preloader.remove();

                clearInterval(timer);
            }
        }, 1000 / fps);
    }
}

document.addEventListener('DOMContentLoaded', showSite);
// function img_load() {
//     if (loadedImg === countImages.length - 1) {
//         showSite()
//
//
//     } else {
//         loadedImg += 1;
//     }
// }
//
// let countImages = document.getElementsByTagName('img'),
//     loadedImg = 0;
//
// document.addEventListener('DOMContentLoaded', function() {
//     for (let i = 0; i < countImages.length; i++) {
//         let img = new Image();
//         img.src = countImages[i].src;
//
//         img.onload = img_load;
//         img.onerror = img_load;
//     }
// });

//плавный скролл якорей
function currentYPosition() {
    // Firefox, Chrome, Opera, Safari
    if (self.pageYOffset) return self.pageYOffset;
    // Internet Explorer 6 - standards mode
    if (document.documentElement && document.documentElement.scrollTop)
        return document.documentElement.scrollTop;
    // Internet Explorer 6, 7 and 8
    if (document.body.scrollTop) return document.body.scrollTop;
    return 0;
}


function elmYPosition(eID) {
    var elm = document.getElementById(eID);
    var y = elm.offsetTop;
    var node = elm;
    while (node.offsetParent && node.offsetParent != document.body) {
        node = node.offsetParent;
        y += node.offsetTop;
    } return y;
}


function smoothScroll(eID) {
    var startY = currentYPosition();
    var stopY = elmYPosition(eID);
    var distance = stopY > startY ? stopY - startY : startY - stopY;
    if (distance < 100) {
        scrollTo(0, stopY); return;
    }
    var speed = Math.round(distance / 100);
    if (speed >= 20) speed = 20;
    var step = Math.round(distance / 25);
    var leapY = stopY > startY ? startY + step : startY - step;
    var timer = 0;
    if (stopY > startY) {
        for ( var i=startY; i<stopY; i+=step ) {
            setTimeout("window.scrollTo(0, "+leapY+")", timer * speed);
            leapY += step; if (leapY > stopY) leapY = stopY; timer++;
        } return;
    }
    for ( var i=startY; i>stopY; i-=step ) {
        setTimeout("window.scrollTo(0, "+leapY+")", timer * speed);
        leapY -= step; if (leapY < stopY) leapY = stopY; timer++;
    }
}

const anchors = [].slice.call(document.querySelectorAll('a[href*="scroll"]'));

anchors.forEach(function(item) {
    // каждому якорю присваиваем обработчик события
    item.addEventListener('click', function(e) {
        // убираем стандартное поведение
        e.preventDefault();

        //console.log(item.getAttribute('href'));
        smoothScroll(item.getAttribute('href'));
    });
});