<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';

interface Props {
    class?: string;
}

defineProps<Props>();
</script>

<template>
    <div class="flex aspect-square size-8 items-center justify-center rounded-lg bg-[#176B87] text-white">
        <AppLogoIcon class="size-5 text-white" />
    </div>
    <div class="ml-1.5 grid flex-1 text-left text-sm">
        <span class="truncate font-bold leading-none text-[#176B87] dark:text-[#A8DCCF]">
            Cuidar<span class="text-[#36A69A]">Pet</span>
        </span>
        <span class="text-[10px] text-muted-foreground mt-0.5">Clínica Veterinária</span>
    </div>
</template>
