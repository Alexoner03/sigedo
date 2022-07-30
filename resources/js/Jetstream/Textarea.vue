<template>
    <textarea
        class="
            border-metalgray
            focus:border-secondary
            focus:ring
            focus:ring-secondary
            focus:ring-opacity-50
            rounded-md
            shadow-sm
            h-36
            resize-none
            bg-transparent
        "
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="input"
    ></textarea>
</template>

<script>
export default {
    props: ["modelValue"],

    emits: ["update:modelValue"],

    methods: {
        focus() {
            this.$refs.input.focus();
        },
    },
};
</script>
