<template>
    <div class="auth-user">
        <div class="auth-user__challenges" @mouseenter="open" @mouseleave="close" v-if="challengesTitle">
            <h4>{{ challengesTitle }}</h4>

            <transition name="challenges">
                <div class="auth-user__challenges-show" v-show="achievementsActive">
                    <slot></slot>
                </div>
            </transition>


        </div>
        <a :href="link" class="auth-user__link">
            <div class="auth-user__avatar">
                <div class="auth-user__avatar-photo" :style="{backgroundImage: `url(${avatar})`}"></div>
                <span class="auth-user__avatar-nickname">{{ nickName }}</span>

                <ul class="achievements-list">
                    <li v-for="item in noteOfAchievements" style="background-image: url('/images/achievement.svg')"><span class="achievements-list__note">{{ item }}</span></li>
                </ul>

                <slot name="link"></slot>
            </div>
        </a>

        <p class="auth-user__location" v-if="location">
            <svg>
                <use xlink:href="#icon-location" />
            </svg> {{ location }}
        </p>
    </div>
</template>

<script>
    export default {
        props: {
            challengesTitle: null,
            avatar: null,
            nickName: {
                required: true
            },
            noteOfAchievements: {
                type: Array,
                default: null
            },
            location: null,
            link: null,
        },

        data() {
            return {
                achievementsActive: false
            }
        },

        methods: {
            open() {
                this.achievementsActive = true;
            },
            close() {
                this.achievementsActive = false;
            }
        }
    }
</script>
