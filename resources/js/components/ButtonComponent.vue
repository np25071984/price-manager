<template>
    <span>
        <a :href="getHref" @click="btnClick" :title="title"><i :class="btnClass"></i></a>
    </span>
</template>

<script>
    export default {
        props: {
            href: {
                type: String,
            },
            btnClass: {
                type: Array,
                default: [],
            },
            title: {
                type: String,
            },
            clickEvent: {
                type: Object,
                default () {
                    return {}
                }
            },
        },
        data() {
            return {
            };
        },
        methods: {
            btnClick: function(event) {
                if (this.clickEvent) {
                    event.preventDefault();
                    this.$emit('click', this.clickEvent);
                }
            }
        },
        computed: {
            getHref: function() {
                return this.href ? this.href : '#';
            }
        },
        mounted() {
        }
    }
</script>
