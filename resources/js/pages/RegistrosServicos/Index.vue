<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, PaginatedData, RegistroServico } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { ClipboardList, Plus, Trash2 } from 'lucide-vue-next';

defineProps<{
    registros: PaginatedData<RegistroServico>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Atendimentos & Serviços', href: '/registros-servicos' },
];

function deleteRegistro(id: number) {
    if (confirm('Deseja realmente remover este registro de atendimento?')) {
        router.delete(`/registros-servicos/${id}`);
    }
}
</script>

<template>
    <Head title="Atendimentos & Serviços" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-5 p-4 md:p-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl font-bold tracking-tight text-[#176B87] dark:text-[#A8DCCF] flex items-center gap-2">
                        <ClipboardList class="h-6 w-6 text-[#36A69A]" />
                        Atendimentos & Serviços
                    </h1>
                    <p class="text-xs text-muted-foreground mt-0.5">
                        Histórico completo de consultas, procedimentos clínicos, cirurgias e serviços gerais.
                    </p>
                </div>

                <Link href="/registros-servicos/create">
                    <Button class="bg-[#36A69A] hover:bg-[#2c8d82] text-white text-xs h-9 rounded-xl font-semibold gap-1.5 shrink-0">
                        <Plus class="h-4 w-4" />
                        <span>Novo Atendimento</span>
                    </Button>
                </Link>
            </div>

            <Card class="rounded-2xl border border-border/80 shadow-sm overflow-hidden">
                <CardHeader class="px-5 py-4 border-b border-border/60">
                    <CardTitle class="text-sm font-semibold text-[#176B87] dark:text-[#A8DCCF]">
                        Serviços Realizados ({{ registros.total }})
                    </CardTitle>
                    <CardDescription class="text-xs">
                        Lançamentos financeiros e clínicos da clínica.
                    </CardDescription>
                </CardHeader>
                <CardContent class="p-0">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-muted/40 text-[10px] font-bold text-muted-foreground uppercase tracking-wider border-b border-border/60">
                                <tr>
                                    <th class="py-3 px-5">Paciente (Pet)</th>
                                    <th class="py-3 px-5">Tutor</th>
                                    <th class="py-3 px-5">Procedimento Realizado</th>
                                    <th class="py-3 px-5">Data</th>
                                    <th class="py-3 px-5">Forma de Pagamento</th>
                                    <th class="py-3 px-5 text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border/60">
                                <tr v-for="reg in registros.data" :key="reg.id" class="hover:bg-muted/30 transition">
                                    <td class="py-3.5 px-5 font-semibold text-[#176B87] dark:text-[#A8DCCF]">
                                        <Link v-if="reg.pet" :href="`/pets/${reg.pet.id}`" class="hover:underline flex items-center gap-2">
                                            <span>🐕</span>
                                            <span>{{ reg.pet.nome }}</span>
                                        </Link>
                                    </td>
                                    <td class="py-3.5 px-5 text-muted-foreground">
                                        {{ reg.pet?.cliente?.nome || 'Não informado' }}
                                    </td>
                                    <td class="py-3.5 px-5 font-medium text-foreground">
                                        <span class="bg-[#176B87]/10 text-[#176B87] dark:text-[#A8DCCF] px-2 py-0.5 rounded-full text-[11px] font-semibold">
                                            {{ reg.servico?.descricao || 'Serviço Clínico' }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-5 text-muted-foreground">{{ reg.data }}</td>
                                    <td class="py-3.5 px-5">
                                        <span class="bg-muted text-foreground px-2 py-0.5 rounded-md text-[11px] font-medium">
                                            {{ reg.forma_pagamento?.descricao || 'Pendente' }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-5 text-right">
                                        <Button @click="deleteRegistro(reg.id)" variant="ghost" size="sm" class="text-xs h-7 text-destructive hover:bg-destructive/10 rounded-lg p-1.5">
                                            <Trash2 class="h-3.5 w-3.5" />
                                        </Button>
                                    </td>
                                </tr>
                                <tr v-if="registros.data.length === 0">
                                    <td colspan="6" class="py-8 text-center text-muted-foreground text-xs">
                                        Nenhum atendimento registrado até o momento.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
