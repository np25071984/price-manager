<template>
    <div>

        <input v-model.trim="searchQuery"
               v-on:keydown.enter.prevent.stop
               @input="searchQueryChange"
               class="form-control mb-1"
               type="text"
               placeholder="Поиск" />

        <p v-if="isLoading">Loading...</p>
        <table v-else class="table">
            <thead>
                <th v-for="column in columns" :class="column.class">
                    <a v-if="column.sortable" href="#"
                            @click.prevent="sortColumn(column.code, column.sort === 'asc' ? 'desc' : 'asc')">
                        {{ column.title }}
                        <i v-if="column.sort === 'asc'" class="fa fa-sort-up"></i>
                        <i v-else-if="column.sort === 'desc'" class="fa fa-sort-down"></i>
                    </a>
                    <span v-else>{{ column.title }}</span>
                </th>
            </thead>
            <tbody>
                <tr v-for="item in items">
                    <td v-for="column in columns" :class="column.class">

                        <span v-if="column.type === 'html'" v-html="item[column.code]"></span>

                        <span v-else-if="column.type === 'component'">
                            <component v-for="(component, key) in item[column.code]"
                                       v-on:click="clickHandler"
                                       :is="component.name"
                                       :href="component.href"
                                       :btn-class="component.class"
                                       :title="component.title"
                                       :click-event="component.clickevent"
                                       :key="key"></component>
                        </span>

                        <span v-else-if="column.type === 'text'">{{ item[column.code] }}</span>

                    </td>
                </tr>
            </tbody>
        </table>

        <pagination
                :data="data"
                :limit="paginationLimit"
                align="center"
                :showDisabled="paginationShowDiasbled"
                @pagination-change-page="getResults">
        </pagination>

    </div>
</template>

<script>
    import axios from "axios";

    export default {
        props: {
            initQuerySearch: {
                type: String,
            },
            apiLink: {
                type: String,
            },
        },
        data() {
            return {
                searchQuery: this.initQuerySearch,

                items: [],
                columns: [],
                data: {},

                paginationLimit: 5,
                paginationShowDiasbled: false,

                isLoading: true,

                sortColCode: null,
                sortOrder: null,
            };
        },
        methods: {
            clickHandler(event) {
                if (confirm(event.text)) {
                    this.isLoading = true;
                    axios.delete(event.link)
                        .then(resp => {
                            this.getResults(this.data.meta.current_page);
                        })
                        .catch(error => {
                            console.log(error);
                        })
                }
            },
            sortColumn(colCode, order) {
                this.sortColCode = colCode;
                this.sortOrder = order;
                this.getResults(1);
            },
            getResults(page) {
                if (typeof page === 'undefined') {
                    page = 1;
                }
                this.isLoading = true;
                axios.get(this.urlWithParams(page))
                    .then(response => {
                        this.items = response.data.data;
                        delete response.data.data;

                        this.columns = response.data.columns;
                        delete response.data.columns;

                        this.data = response.data;
                        this.isLoading = false;
                    });
            },
            urlWithParams(page) {
                const params = [];
                params.push('page=' + encodeURIComponent(page));

                const colCode = this.sortColCode ? this.sortColCode : null;
                const sortOrder = this.sortOrder ? this.sortOrder : null;
                if (colCode && sortOrder) {
                    params.push('column=' + encodeURIComponent(colCode));
                    params.push('order=' + encodeURIComponent(sortOrder));
                }
                if (this.searchQuery) {
                    params.push('q=' + encodeURIComponent(this.searchQuery));
                }
                return this.apiLink + '?' + params.join('&');
            },
        },
        created () {
            this.searchQueryChange = _.debounce(() => {
                if (this.searchQuery === '' || this.searchQuery.length > 2) {
                    this.getResults()
                }
            }, 500)
        },
        mounted() {
            this.getResults();
        }
    }
</script>
