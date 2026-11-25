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
import type { BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, CheckCircle2, Syringe } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    pets: Array<{ id: number; nome: string; cliente?: { id: number; nome: string } }>;
    vacinas: Array<{ id: number; descricao: string }>;
    pet_id?: number | null;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Vacinações', href: '/vacinacoes' },
    { title: 'Nova Aplicação', href: '/vacinacoes/create' },
];

const isConfirmOpen = ref(false);

const form = useForm({
    pet_id: props.pet_id || (props.pets.length > 0 ? props.pets[0].id : ''),
    vacina_id: props.vacinas.length > 0 ? props.vacinas[0].id : '',
    data_aplicacao: new Date().toISOString().split('T')[0],
    validade: '',
});

function requestSubmit() {
    form.clearErrors();
    if (!form.pet_id || !form.vacina_id || !form.data_aplicacao) return;
    isConfirmOpen.value = true;
}

function confirmSubmit() {
    form.transform((data) => ({
        pet_id: data.pet_id,
        vacina_id: data.vacina_id,
        data: data.data_aplicacao,
        validade: data.validade || null,
    })).post('/vacinacoes', {
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
    <Head title="Registrar Vacinação" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-6 p-4 md:p-6 max-w-xl mx-auto w-full">
            <Link href="/vacinacoes" class="inline-flex items-center gap-1.5 text-xs text-muted-foreground hover:text-[#176B87] dark:hover:text-[#A8DCCF] transition">
                <ArrowLeft class="h-4 w-4" />
                <span>Voltar para Lista de Vacinas</span>
            </Link>

            <Card class="rounded-2xl border border-border/80 shadow-sm">
                <CardHeader class="pb-4 border-b border-border/60">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#176B87]/15 text-[#176B87] dark:text-[#A8DCCF] flex items-center justify-center font-bold">
                            <Syringe class="h-5 w-5" />
                        </div>
                        <div>
                            <CardTitle class="text-base font-bold text-[#176B87] dark:text-[#A8DCCF]">
                                Registrar Aplicação de Vacina
                            </CardTitle>
                            <CardDescription class="text-xs">
                                Adicione a dose aplicada e configure a data para o próximo reforço.
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
                            <Label for="vacina_id" class="text-xs font-semibold">Vacina Aplicada *</Label>
                            <select
                                id="vacina_id"
                                v-model="form.vacina_id"
                                required
                                class="w-full text-xs bg-background border border-border rounded-xl p-2.5 focus:outline-none focus:ring-1 focus:ring-[#36A69A]"
                            >
                                <option disabled value="">Selecione a vacina</option>
                                <option v-for="vac in vacinas" :key="vac.id" :value="vac.id">
                                    {{ vac.descricao }}
                                </option>
                            </select>
                            <span v-if="form.errors.vacina_id" class="text-[11px] text-destructive font-medium">{{ form.errors.vacina_id }}</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <Label for="data" class="text-xs font-semibold">Data da Aplicação *</Label>
                                <Input id="data" type="date" v-model="form.data_aplicacao" required class="text-xs rounded-xl" />
                                <span v-if="form.errors.data" class="text-[11px] text-destructive font-medium">{{ form.errors.data }}</span>
                            </div>

                            <div class="space-y-1.5">
                                <Label for="validade" class="text-xs font-semibold">Validade / Próximo Reforço</Label>
                                <Input id="validade" type="date" v-model="form.validade" class="text-xs rounded-xl" />
                                <span v-if="form.errors.validade" class="text-[11px] text-destructive font-medium">{{ form.errors.validade }}</span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-border/60 flex items-center justify-end gap-3">
                            <Link href="/vacinacoes">
                                <Button type="button" variant="outline" class="text-xs rounded-xl">Cancelar</Button>
                            </Link>
                            <Button type="submit" class="bg-[#176B87] hover:bg-[#125368] text-white text-xs font-bold rounded-xl px-5">
                                Salvar Vacinação
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
                        Confirmar Vacinação
                    </DialogTitle>
                    <DialogDescription class="text-xs">
                        Confirma o registro desta vacinação para a data indicada? A carteira de saúde do paciente será atualizada.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter class="pt-3">
                    <Button type="button" variant="outline" @click="isConfirmOpen = false" :disabled="form.processing" class="text-xs rounded-xl">Voltar</Button>
                    <Button @click="confirmSubmit" :disabled="form.processing" class="bg-[#176B87] hover:bg-[#125368] text-white text-xs rounded-xl font-bold">
                        {{ form.processing ? 'Gravando no Banco...' : 'Sim, Gravar' }}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
