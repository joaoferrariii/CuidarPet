<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, Cliente } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, PawPrint } from 'lucide-vue-next';

const props = defineProps<{
    clientes: Array<{ id: number; nome: string; cpf: string }>;
    cliente_id?: number | null;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Pets', href: '/pets' },
    { title: 'Novo Pet', href: '/pets/create' },
];

const form = useForm({
    cliente_id: props.cliente_id || (props.clientes.length > 0 ? props.clientes[0].id : ''),
    nome: '',
    raca: '',
    data_nascimento: '',
    genero: 'M',
    castrado: false,
});

function submit() {
    form.post('/pets');
}
</script>

<template>
    <Head title="Cadastrar Pet" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-6 p-4 md:p-6 max-w-2xl mx-auto w-full">
            <Link href="/pets" class="inline-flex items-center gap-1.5 text-xs text-muted-foreground hover:text-[#176B87] dark:hover:text-[#A8DCCF] transition">
                <ArrowLeft class="h-4 w-4" />
                <span>Voltar para Lista de Pets</span>
            </Link>

            <Card class="rounded-2xl border border-border/80 shadow-sm">
                <CardHeader class="pb-4 border-b border-border/60">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#176B87]/15 text-[#176B87] dark:text-[#A8DCCF] flex items-center justify-center font-bold">
                            <PawPrint class="h-5 w-5" />
                        </div>
                        <div>
                            <CardTitle class="text-base font-bold text-[#176B87] dark:text-[#A8DCCF]">
                                Cadastrar Novo Paciente (Pet)
                            </CardTitle>
                            <CardDescription class="text-xs">
                                Insira as informações do animal e vincule ao tutor responsável.
                            </CardDescription>
                        </div>
                    </div>
                </CardHeader>

                <CardContent class="pt-6">
                    <form @submit.prevent="submit" class="space-y-4">
                        <div class="space-y-1.5">
                            <Label for="cliente_id" class="text-xs font-semibold">Tutor Responsável *</Label>
                            <select
                                id="cliente_id"
                                v-model="form.cliente_id"
                                required
                                class="w-full text-xs bg-background border border-border rounded-xl p-2.5 focus:outline-none focus:ring-1 focus:ring-[#36A69A]"
                            >
                                <option disabled value="">Selecione um tutor</option>
                                <option v-for="cliente in clientes" :key="cliente.id" :value="cliente.id">
                                    {{ cliente.nome }} (CPF: {{ cliente.cpf }})
                                </option>
                            </select>
                            <span v-if="form.errors.cliente_id" class="text-[11px] text-destructive">{{ form.errors.cliente_id }}</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <Label for="nome" class="text-xs font-semibold">Nome do Animal *</Label>
                                <Input id="nome" v-model="form.nome" placeholder="Ex: Bob, Thor, Nina..." required class="text-xs rounded-xl" />
                                <span v-if="form.errors.nome" class="text-[11px] text-destructive">{{ form.errors.nome }}</span>
                            </div>

                            <div class="space-y-1.5">
                                <Label for="raca" class="text-xs font-semibold">Raça / Espécie *</Label>
                                <Input id="raca" v-model="form.raca" placeholder="Ex: Golden, Poodle, SRD, Siamês..." required class="text-xs rounded-xl" />
                                <span v-if="form.errors.raca" class="text-[11px] text-destructive">{{ form.errors.raca }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="space-y-1.5">
                                <Label for="data_nascimento" class="text-xs font-semibold">Data de Nascimento *</Label>
                                <Input id="data_nascimento" type="date" v-model="form.data_nascimento" required class="text-xs rounded-xl" />
                                <span v-if="form.errors.data_nascimento" class="text-[11px] text-destructive">{{ form.errors.data_nascimento }}</span>
                            </div>

                            <div class="space-y-1.5">
                                <Label for="genero" class="text-xs font-semibold">Gênero *</Label>
                                <select id="genero" v-model="form.genero" class="w-full text-xs bg-background border border-border rounded-xl p-2.5 focus:outline-none focus:ring-1 focus:ring-[#36A69A]">
                                    <option value="M">Macho</option>
                                    <option value="F">Fêmea</option>
                                </select>
                                <span v-if="form.errors.genero" class="text-[11px] text-destructive">{{ form.errors.genero }}</span>
                            </div>

                            <div class="space-y-1.5">
                                <Label for="castrado" class="text-xs font-semibold">Castrado? *</Label>
                                <select id="castrado" v-model="form.castrado" class="w-full text-xs bg-background border border-border rounded-xl p-2.5 focus:outline-none focus:ring-1 focus:ring-[#36A69A]">
                                    <option :value="true">Sim</option>
                                    <option :value="false">Não</option>
                                </select>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-border/60 flex items-center justify-end gap-3">
                            <Link href="/pets">
                                <Button type="button" variant="outline" class="text-xs rounded-xl">Cancelar</Button>
                            </Link>
                            <Button type="submit" :disabled="form.processing" class="bg-[#176B87] hover:bg-[#125368] text-white text-xs font-bold rounded-xl px-5">
                                Cadastrar Paciente
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
