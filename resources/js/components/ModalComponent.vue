<template>
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container">

                    <div class="modal-header">
                        <slot name="header">
                            default header
                        </slot>
                    </div>

                    <div class="modal-body">
                        <slot name="body">
                            default body
                        </slot>
                    </div>

                    <div class="modal-footer">
                        <button class="modal-default-button" @click="$emit('cancel')">Cancel</button>
                        <button class="modal-default-button" @click="confirm">OK</button>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    export default {
        data() {
            return {};
        },
        methods: {
            confirm() {
                window.Bus.$emit('modal-confirm', this.$slots.body[0].children[0].child.getSelected());
            },
        },
    }
</script>
