@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-12">
                <a href="{{ route('item.upload_form') }}"><button type="button" class="btn btn-link float-right">Згарузить прайс</button></a>
                <a href="{{ route('item.create') }}"><button type="button" class="btn btn-link float-right">Добавить товар</button></a>
            </div>
        </div>

        <tab-component>
            <div slot="items">
                <table-component
                    ref="item"
                    :show-search="false"
                    api-link="{{ $itemApiLink }}"
                    :routes="{
                        'api.item.shop.assign': '{{ route('api.item.shop.assign') }}',
                        'api.item.shop.remove': '{{ route('api.item.shop.remove') }}',
                        'api.items.destroy': '{{ route('api.items.destroy') }}',
                    }"
                    :multi-actions="[addToShopAction, removeFromShopAction, itemDestroyAction]">

                    <div slot="clarifying">
                        <modal v-if="showModal" @cancel="showModal = false">
                            <h3 slot="header">Выберите магазины</h3>
                            <div slot="body">
                                <table-component
                                        api-link="{{ route('api.shop.index') }}"
                                        :multi="true"></table-component>
                            </div>
                        </modal>
                    </div>

                </table-component>
            </div>
            <div slot="items">
                <table-component ref="contractor" :show-search="false" api-link="{{ $contractorApiLink }}"></table-component>
            </div>
        </tab-component>

    </div>
@endsection
