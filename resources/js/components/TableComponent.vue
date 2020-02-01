<template>
    <div>
        <slot name="clarifying"></slot>

        <input v-if="showSearch"
               v-model.trim="searchQuery"
               v-on:keydown.enter.prevent.stop
               @input="searchQueryChange"
               class="form-control mb-1"
               type="text"
               placeholder="Поиск" />

        <div v-if="isMultiselect" class="row">
            <div class="col-4 my-auto">
                Выбрано элементов: {{ selectedElements.length }}
            </div>
            <div class="col-8">
                <div v-if="multiActions.length > 0" class="form-inline float-right mb-1">
                    <select class="form-control mr-1" v-model="action">
                        <option disabled selected value="-1">Выберите действие</option>
                        <option v-for="(mAction, index) in multiActions" :value="index">{{ mAction.label }}</option>
                    </select>
                    <button class="form-control btn btn-primary" v-on:click="applyAction()" :disabled=isActionButtonDisabled>Применить</button>
                </div>
            </div>
        </div>

        <p v-if="isLoading">Loading...</p>

        <table v-else class="table">
            <thead>
                <th v-if="isMultiselect">
                    <input type="checkbox" @change="topCheckboxHandler($event)" />
                </th>
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
                    <td v-if="isMultiselect">
                        <input type="checkbox" class="cb_item_check" :value="item.id" v-model="selectedElements" />
                    </td>
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
            showSearch: {
                type: Boolean,
                default: true,
            },
            multiActions: {
                type: Array,
                default: () => [],
            },
            routes: {
                type: Object,
                default: () => {},
            },
            multi: {
                type: Boolean,
                default: false,
            }
        },
        data() {
            return {
                searchQuery: this.initQuerySearch,

                items: [],
                columns: [],
                selectedElements: [],
                action: -1,
                data: {},

                paginationLimit: 5,
                paginationShowDiasbled: false,

                isLoading: true,

                sortColCode: null,
                sortOrder: null,
            };
        },
        methods: {
            processAction(clariData) {
                const action = this.multiActions[this.action];

                if (!action) {
                    return;
                }

                let selectedData = {
                    ids: this.selectedElements,
                    clarifyingStep: clariData,
                };

                if (action.hasOwnProperty("confirm") && (action.confirm === true)) {
                    if (!confirm('Вы уверены, что хотите выполнить действие')) {
                        return;
                    }
                }

                action.parent = this;
                action.actionHandler(selectedData);
            },
            applyAction() {
                const action = this.multiActions[this.action];

                if (action.hasOwnProperty("clarifyingStep")) {
                    switch (action.clarifyingStep.type) {
                        case 'simple':
                            this.processAction(null);
                            break;
                        case 'prompt':
                            const userData = prompt(action.clarifyingStep.data.text);

                            if (userData != null) {
                                this.processAction(userData);
                            }
                            break;
                        case 'component':
                            this.$root.$data.showModal = true;
                            break;
                        default:
                            console.error('Unsupported action type "' + action.clarifyingStep.type + '"!');
                            return;
                    }
                }
            },
            topCheckboxHandler: function(e) {
                document.querySelectorAll(".cb_item_check").forEach(function(cbox) {
                    const elementVal = parseInt(cbox.value, 10);
                    const elementValIndex = this.selectedElements.indexOf(elementVal);
                    if (e.target.checked) {
                        if (elementValIndex === -1) {
                            this.selectedElements.push(elementVal);
                        }
                    } else {
                        if (elementValIndex !== -1) {
                            this.selectedElements.splice(elementValIndex, 1);
                        }
                    }
                }, this);
            },
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
        computed: {
            isActionButtonDisabled: function() {
                return (this.action === -1) || (this.selectedElements.length === 0);
            },
            isMultiselect: function() {
                return this.multiActions.length > 0 || this.multi;
            }
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
