<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, FormaPagamento, Servico } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, CheckCircle2, ClipboardList } from 'lucide-vue-next';
import { ref } from 'vue';

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

const isConfirmOpen = ref(false);

const form = useForm({
    pet_id: props.pet_id || (props.pets.length > 0 ? props.pets[0].id : ''),
    servico_id: props.servicos.length > 0 ? props.servicos[0].id : '',
    forma_pagamento_id: props.formasPagamento.length > 0 ? props.formasPagamento[0].id : '',
    data_registro: new Date().toISOString().split('T')[0],
});

function requestSubmit() {
    form.clearErrors();
    if (!form.pet_id || !form.servico_id || !form.forma_pagamento_id || !form.data_registro) return;
    isConfirmOpen.value = true;
}

function confirmSubmit() {
    form.transform((data) => ({
        pet_id: data.pet_id,
        servico_id: data.servico_id,
        forma_pagamento_id: data.forma_pagamento_id,
        data: data.data_registro,
    })).post('/registros-servicos', {
        preserveScroll: true,
        onSuccess: () => {
            isConfirmOpen.value = false;
        },
        onError: () => {
            isConfirmOpen.value = false;
        },
    });
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
                    <form @submit.prevent="requestSubmit" class="space-y-4">
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
                            <span v-if="form.errors.pet_id" class="text-[11px] text-destructive font-medium">{{ form.errors.pet_id }}</span>
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
                            <span v-if="form.errors.servico_id" class="text-[11px] text-destructive font-medium">{{ form.errors.servico_id }}</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <Label for="data" class="text-xs font-semibold">Data do Atendimento *</Label>
                                <Input id="data" type="date" v-model="form.data_registro" required class="text-xs rounded-xl" />
                                <span v-if="form.errors.data" class="text-[11px] text-destructive font-medium">{{ form.errors.data }}</span>
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
                                <span v-if="form.errors.forma_pagamento_id" class="text-[11px] text-destructive font-medium">{{ form.errors.forma_pagamento_id }}</span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-border/60 flex items-center justify-end gap-3">
                            <Link href="/registros-servicos">
                                <Button type="button" variant="outline" class="text-xs rounded-xl">Cancelar</Button>
                            </Link>
                            <Button type="submit" class="bg-[#36A69A] hover:bg-[#2c8d82] text-white text-xs font-bold rounded-xl px-5">
                                Concluir Atendimento
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>

        <!-- Pop-up de Confirmação de Cadastro -->
        <Dialog v-model:open="isConfirmOpen">
            <DialogContent class="sm:max-w-sm rounded-2xl">
                <DialogHeader>
                    <DialogTitle class="text-[#176B87] dark:text-[#A8DCCF] flex items-center gap-2">
                        <CheckCircle2 class="h-5 w-5 text-[#36A69A]" />
                        Confirmar Atendimento
                    </DialogTitle>
                    <DialogDescription class="text-xs">
                        Confirma o registro deste procedimento clínico no prontuário do paciente?
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter class="pt-3">
                    <Button type="button" variant="outline" @click="isConfirmOpen = false" :disabled="form.processing" class="text-xs rounded-xl">Voltar</Button>
                    <Button @click="confirmSubmit" :disabled="form.processing" class="bg-[#36A69A] hover:bg-[#2c8d82] text-white text-xs rounded-xl font-bold">
                        {{ form.processing ? 'Gravando no Banco...' : 'Sim, Registrar' }}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
