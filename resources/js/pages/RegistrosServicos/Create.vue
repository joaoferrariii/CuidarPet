<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, FormaPagamento, Servico } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, ClipboardList } from 'lucide-vue-next';

const props = defineProps<{
    pets: Array<{ id: number; nome: string; cliente?: { id: number; nome: string } }>;
    servicos: Servico[];
    formasPagamento: FormaPagamento[];
    pet_id?: number | null;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Atendimentos', href: '/registros-servicos' },
    { title: 'Novo Atendimento', href: '/registros-servicos/create' },
];

const form = useForm({
    pet_id: props.pet_id || (props.pets.length > 0 ? props.pets[0].id : ''),
    servico_id: props.servicos.length > 0 ? props.servicos[0].id : '',
    forma_pagamento_id: props.formasPagamento.length > 0 ? props.formasPagamento[0].id : '',
    data: new Date().toISOString().split('T')[0],
});

function submit() {
    form.post('/registros-servicos');
}
</script>

<template>
    <Head title="Lançar Atendimento" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-6 p-4 md:p-6 max-w-xl mx-auto w-full">
            <Link href="/registros-servicos" class="inline-flex items-center gap-1.5 text-xs text-muted-foreground hover:text-[#176B87] dark:hover:text-[#A8DCCF] transition">
                <ArrowLeft class="h-4 w-4" />
                <span>Voltar para Lista de Atendimentos</span>
            </Link>

            <Card class="rounded-2xl border border-border/80 shadow-sm">
                <CardHeader class="pb-4 border-b border-border/60">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#176B87]/15 text-[#176B87] dark:text-[#A8DCCF] flex items-center justify-center font-bold">
                            <ClipboardList class="h-5 w-5" />
                        </div>
                        <div>
                            <CardTitle class="text-base font-bold text-[#176B87] dark:text-[#A8DCCF]">
                                Lançar Novo Atendimento / Serviço
                            </CardTitle>
                            <CardDescription class="text-xs">
                                Registre consultas, banhos, exames ou procedimentos clínicos.
                            </CardDescription>
                        </div>
                    </div>
                </CardHeader>

                <CardContent class="pt-6">
                    <form @submit.prevent="submit" class="space-y-4">
                        <div class="space-y-1.5">
                            <Label for="pet_id" class="text-xs font-semibold">Paciente (Pet) *</Label>
                            <select
                                id="pet_id"
                                v-model="form.pet_id"
                                required
                                class="w-full text-xs bg-background border border-border rounded-xl p-2.5 focus:outline-none focus:ring-1 focus:ring-[#36A69A]"
                            >
                                <option disabled value="">Selecione o pet</option>
                                <option v-for="pet in pets" :key="pet.id" :value="pet.id">
                                    {{ pet.nome }} (Tutor: {{ pet.cliente?.nome || 'N/A' }})
                                </option>
                            </select>
                            <span v-if="form.errors.pet_id" class="text-[11px] text-destructive">{{ form.errors.pet_id }}</span>
                        </div>

                        <div class="space-y-1.5">
                            <Label for="servico_id" class="text-xs font-semibold">Procedimento / Serviço *</Label>
                            <select
                                id="servico_id"
                                v-model="form.servico_id"
                                required
                                class="w-full text-xs bg-background border border-border rounded-xl p-2.5 focus:outline-none focus:ring-1 focus:ring-[#36A69A]"
                            >
                                <option disabled value="">Selecione o serviço</option>
                                <option v-for="s in servicos" :key="s.id" :value="s.id">
                                    {{ s.descricao }}
                                </option>
                            </select>
                            <span v-if="form.errors.servico_id" class="text-[11px] text-destructive">{{ form.errors.servico_id }}</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <Label for="data" class="text-xs font-semibold">Data do Atendimento *</Label>
                                <Input id="data" type="date" v-model="form.data" required class="text-xs rounded-xl" />
                                <span v-if="form.errors.data" class="text-[11px] text-destructive">{{ form.errors.data }}</span>
                            </div>

                            <div class="space-y-1.5">
                                <Label for="forma_pagamento_id" class="text-xs font-semibold">Forma de Pagamento *</Label>
                                <select
                                    id="forma_pagamento_id"
                                    v-model="form.forma_pagamento_id"
                                    required
                                    class="w-full text-xs bg-background border border-border rounded-xl p-2.5 focus:outline-none focus:ring-1 focus:ring-[#36A69A]"
                                >
                                    <option disabled value="">Selecione a forma</option>
                                    <option v-for="fp in formasPagamento" :key="fp.id" :value="fp.id">
                                        {{ fp.descricao }}
                                    </option>
                                </select>
                                <span v-if="form.errors.forma_pagamento_id" class="text-[11px] text-destructive">{{ form.errors.forma_pagamento_id }}</span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-border/60 flex items-center justify-end gap-3">
                            <Link href="/registros-servicos">
                                <Button type="button" variant="outline" class="text-xs rounded-xl">Cancelar</Button>
                            </Link>
                            <Button type="submit" :disabled="form.processing" class="bg-[#36A69A] hover:bg-[#2c8d82] text-white text-xs font-bold rounded-xl px-5">
                                Concluir Atendimento
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
