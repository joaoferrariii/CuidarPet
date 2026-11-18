<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="flex min-h-svh flex-col items-center justify-center gap-6 bg-background p-6 md:p-10">
        <div class="w-full max-w-sm">
            <div class="flex flex-col gap-8">
                
                <!-- CABEÇALHO COM A LOGO AMPLIADA E FRASE INSTITUCIONAL -->
                <div class="flex flex-col items-center gap-3 text-center">
                    <Link :href="route('home')" class="flex items-center gap-3.5 group">
                        <!-- Quadrado Arredondado Ampliado (h-14 w-14) -->
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#176B87] text-white shadow-md transition-transform group-hover:scale-105 shrink-0">
                            <AppLogoIcon class="size-9 text-white" />
                        </div>

                        <!-- Nome da Clínica Ampliado (text-3xl) -->
                        <div class="text-left">
                            <span class="text-3xl font-extrabold leading-none tracking-tight text-[#176B87] dark:text-[#A8DCCF]">
                                Cuidar<span class="text-[#36A69A]">Pet</span>
                            </span>
                            <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground mt-1">
                                Clínica Veterinária
                            </p>
                        </div>
                    </Link>

                    <!-- Frase Institucional Ampliada (text-sm) -->
                    <p class="text-sm text-muted-foreground italic font-normal mt-0.5">
                        "Cuidando de quem faz parte da família"
                    </p>

                    <!-- Título e Subtítulo da Tela -->
                    <div class="space-y-1.5 text-center mt-3">
                        <h1 class="text-2xl font-bold tracking-tight">{{ title }}</h1>
                        <p class="text-center text-sm text-muted-foreground">{{ description }}</p>
                    </div>
                </div>

                <!-- Formulário da tela ativa -->
                <slot />
            </div>
        </div>
    </div>
</template>