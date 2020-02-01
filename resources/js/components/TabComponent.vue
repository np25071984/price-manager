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
                <slot name="items"></slot>
            </div>

            <div class="tab-pane container" id="contractor_items">

                <slot name="contractors"></slot>


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
        },
        data() {
            return {
                searchQuery: this.initQuerySearch,
            };
        },
        created () {
            this.searchQueryChange = _.debounce(() => {
                if (this.searchQuery === '' || this.searchQuery.length > 2) {
                    this.$slots.items[0].children[0].child.searchQuery = this.searchQuery;
                    this.$slots.items[0].children[0].child.getResults();
                    this.$slots.items[1].children[0].child.searchQuery = this.searchQuery;
                    this.$slots.items[1].children[0].child.getResults();
                }
            }, 500)
        },
    }
</script>
