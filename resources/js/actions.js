import axios from "axios";

const setDiscountAction = {
    label: 'Установить скидку',
    route: 'api.shop.item.create.discount',
    parent: null,
    confirm: true,
    clarifyingStep: {
        type: 'prompt',
        data: {
            text: 'Укажите размер желаемой скидки',
        }
    },
    actionHandler: function(data) {
        axios.post(this.parent.routes[this.route], data)
            .then(response => {
                this.parent.getResults(this.parent.data.meta.current_page);
            })
            .catch(function (error) {
                console.error(error);
            });
    },
};

const removeDiscountAction = {
    label: 'Сбросить скидку',
    route: 'api.shop.item.destroy.discount',
    confirm: true,
    clarifyingStep: {
        type: 'simple',
        data: {
        }
    },
    actionHandler: function(data) {
        axios.delete(this.parent.routes[this.route], {data: data})
            .then(response => {
                this.parent.getResults(this.parent.data.meta.current_page);
            })
            .catch(function (error) {
                console.error(error);
            });
    },
};

const addToShopAction = {
    label: 'Добавить в магазин',
    route: 'api.item.shop.assign',
    confirm: true,
    clarifyingStep: {
        type: 'component',
    },
    actionHandler: function(data) {
        axios.post(this.parent.routes[this.route], data)
            .then(response => {
                this.parent.getResults(this.parent.data.meta.current_page);
            })
            .catch(function (error) {
                console.error(error);
            });
    },
};

const removeFromShopAction = {
    label: 'Удалить из магазина',
    route: 'api.item.shop.remove',
    confirm: true,
    clarifyingStep: {
        type: 'component',
    },
    actionHandler: function(data) {
        axios.delete(this.parent.routes[this.route], {data: data})
            .then(response => {
                this.parent.getResults(this.parent.data.meta.current_page);
            })
            .catch(function (error) {
                console.error(error);
            });
    },
};

export { setDiscountAction, removeDiscountAction, addToShopAction, removeFromShopAction };
