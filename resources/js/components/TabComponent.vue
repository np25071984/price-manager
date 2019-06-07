<template>
    <div>

        <input v-model.trim="searchQuery"
               v-on:keydown.enter.prevent.stop
               @input="searchQueryChange"
               class="form-control mb-1"
               type="text"
               placeholder="Поиск" />

        <ul class="nav nav-tabs mt-2 mb-1" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#items">Предложения из прайса</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#contractor_items">Предложения поставщиков</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane container active" id="items">

                <table-component ref="item" :show-search="false" :api-link="itemApiLink"></table-component>

            </div>

            <div class="tab-pane container" id="contractor_items">

                <table-component ref="contractor" :show-search="false" :api-link="contractorApiLink"></table-component>

            </div>
        </div>

    </div>
</template>

<script>
    export default {
        props: {
            initQuerySearch: {
                type: String,
            },
            itemApiLink: {
                type: String,
            },
            contractorApiLink: {
                type: String,
            },
        },
        data() {
            return {
                searchQuery: this.initQuerySearch,
            };
        },
        methods: {
        },
        created () {
            this.searchQueryChange = _.debounce(() => {
                if (this.searchQuery === '' || this.searchQuery.length > 2) {
                    this.$refs.item.searchQuery = this.searchQuery;
                    this.$refs.item.getResults();
                    this.$refs.contractor.searchQuery = this.searchQuery;
                    this.$refs.contractor.getResults();
                }
            }, 500)
        },
        mounted() {
        }
    }
</script>
