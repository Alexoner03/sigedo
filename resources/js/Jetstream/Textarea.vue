<template>
	<textarea class="border-metalgray focus:border-secondary focus:ring focus:ring-secondary focus:ring-opacity-50 rounded-md shadow-sm h-36 resize-none bg-transparent" :value="value" @input="$emit('input', $event.target.value)" ref="input"></textarea>
</template>

<script>
    export default {
        props: ['value'],

        methods: {
            focus() {
                this.$refs.input.focus()
            }
        }
    }
</script>
