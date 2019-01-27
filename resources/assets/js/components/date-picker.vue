<template>
    <vue-pikaday v-model="date" :options="options" v-p-visible="visible" placeholder="Select..." readonly ref="input"/>
</template>

<script>
    import VuePikaday from '@enrian/vue-pikaday';
    import '@enrian/vue-pikaday/dist/vue-pikaday.min.css';
    import moment from 'moment';

    export default {
        data() {
            return {
                date: null,
                visible: false,
                options: {
                    toString(date) {
                        let today = new Date();
                        let birthday = date;
                        let todayD = (today.getFullYear()*12 + today.getMonth())*31+today.getDate()-1;
                        let birthdayD = (birthday.getFullYear()*12 + birthday.getMonth())*31+birthday.getDate()-1;
                        let age = (todayD - birthdayD)/31/12;

                        return `${Math.round(age)} years`;
                    },

                    minDate: moment('1975-01-01').toDate(),
                    maxDate: moment().toDate(),
                    yearRange: 100,
                    defaultDate: moment('1975-01-01').toDate(),
                    setDefaultDate: moment('1975-01-01').toDate()
                }
            }
        },
    }
</script>