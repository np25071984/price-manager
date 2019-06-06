<template>
    <div>
        <p v-if="isLoading">Loading...</p>
        <table v-else class="table">
            <thead>
                <th v-for="column in columns" :class="column.class">{{ column.title }}</th>
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
            apiLink: {
                type: String,
            },
        },
        data() {
            return {
                showLink: {
                    type: String,
                },
                editLink: {
                    type: String,
                },
                deleteLink: {
                    type: String,
                },
                items: [],
                columns: [],
                controlButtons: [],
                paginationLimit: 5,
                paginationShowDiasbled: false,
                isLoading: true,
                data: {},
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
            deleteItem(brandId) {
                if (confirm('Вы уверены что хотите удалить бренд?')) {
                    this.isLoading = true;
                    axios.delete(this.deleteLink.replace('brand_id_ph', brandId))
                        .then(resp => {
                            this.getResults(this.data.current_page);
                        })
                        .catch(error => {
                            console.log(error);
                        })
                }
            },
            getLink(type, brandId) {
                return this[type +'Link'].replace('brand_id_ph', brandId);
            },
            getResults(page) {
                this.isLoading = true;
                if (typeof page === 'undefined') {
                    page = 1;
                }
                const url = this.apiLink + '?page=' + page;
                axios.get(url)
                    .then(response => {
                        this.items = response.data.data;
                        delete response.data.data;

                        this.columns = response.data.columns;
                        delete response.data.columns;

                        this.showLink = response.data.links.show;
                        this.editLink = response.data.links.edit;
                        this.deleteLink = response.data.links.delete;

                        this.controlButtons = response.data.buttons;

                        this.data = response.data;
                        this.isLoading = false;
                    });
            }
        },
        computed: {
            showButtons: function() {
                return this.controlButtons instanceof Object;
            }
        },
        mounted() {
            this.getResults();
        }
    }
</script>
