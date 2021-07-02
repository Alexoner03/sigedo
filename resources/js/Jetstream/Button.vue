<template>
    <button
        :type="type"
        class="
            inline-flex
            items-center
            px-4
            py-2
            rounded-md
            text-xs
            tracking-widest
            active:bg-primary
            focus:outline-none
            focus:border-secondary
            focus:ring focus:ring-secondary
            disabled:opacity-25
            transition
        "
        :class="[outline ? 'bg-transparent border border-secondary hover:bg-secondary hover:text-white text-secondary' : 'text-white bg-secondary border border-transparent hover:bg-opacity-75']"
    >
        <slot></slot>
    </button>
</template>

<script>
export default {
    props: {
        type: {
            type: String,
            default: "submit",
        },
        outline : {
            type : Boolean,
            default : false
        }
    },
};
</script>
