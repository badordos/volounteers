<template>
    <section class="tabs">
        <div class="tabs__inner">

            <ul class="tabs-list" ref="tabsList">
                <li v-for="tab in tabs" :class="{ 'active': tab.isActive }" >
                    <a href="#!" @click.prevent="selectTab(tab)">
                        {{ tab.name }}
                    </a>
                </li>
            </ul>

            <slot></slot>

        </div>
    </section>
</template>

<script>
    export default {
        data() {
            return {
                tabs: []
            }
        },

        methods: {
            getCoords(elem) {
                return elem.getBoundingClientRect().top + pageYOffset;
            },

            animateScroll: function(pos, el) {
                let start = el.scrollTop || 1,
                    change = pos - start,
                    currentTime = 0,
                    increment = 20,
                    duration = 500;

                let animateScroll = function(){
                    currentTime += increment;
                    var val = Math.easeInOutQuad(currentTime, start, change, duration);
                    el.scrollTop = val;
                    if(currentTime < duration) {
                        setTimeout(animateScroll, increment);
                    }
                };
                animateScroll();
            },

            selectTab(selected) {
                location.hash = selected.hash;

                this.tabs.forEach( item => {
                    item.isActive = (item == selected);
                });

                if (selected.indicatorSwiper) {
                    this.$nextTick(()=> {
                        selected.$children[0].$children[1].$refs.swiperThumbs.update(true);
                        selected.$children[0].$children[0].$refs.swiperTop.update(true);
                        selected.$children[1].$refs.mySwiper.update(true);
                    });
                }
                this.animateScroll(this.getCoords(document.querySelector('.tabs-list')), document.getElementsByTagName('html')[0]);
            },

            update(hash) {
                this.tabs.forEach(item => {
                    item.isActive = (item.hash == hash);

                    if (item.indicatorSwiper) {
                        this.$nextTick(()=> {
                            item.$children[0].$children[1].$refs.swiperThumbs.update(true);
                            item.$children[0].$children[0].$refs.swiperTop.update(true);
                            item.$children[1].$refs.mySwiper.update(true);
                        });
                    }
                });
            }
        },
        created() {
            this.$nextTick(() => {
                this.tabs = this.$children;

                if (location.hash && location.hash != '#!') {
                    this.update(location.hash.slice(1));
                }
            });
        }
    }
</script>
