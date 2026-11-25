<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" :class="className" v-bind="$attrs">
        <ellipse cx="12" cy="14" rx="4.5" ry="3.5" fill="currentColor" />
        <path d="M12 11.8 C10.8 11.8 10 12.6 10 13.5 C10 14.8 12 16.2 12 16.2 C12 16.2 14 14.8 14 13.5 C14 12.6 13.2 11.8 12 11.8 Z" fill="#ffffff" />
        <circle cx="7" cy="8.5" r="2" fill="currentColor" />
        <circle cx="17" cy="8.5" r="2" fill="currentColor" />
        <circle cx="10" cy="5.5" r="1.8" fill="currentColor" />
        <circle cx="14" cy="5.5" r="1.8" fill="currentColor" />
        <path d="M5.5 19.5 C8.5 22.5 15.5 22.5 18.5 19.5" stroke="#36A69A" stroke-width="2.2" stroke-linecap="round" fill="none" />
    </svg>
</template>
