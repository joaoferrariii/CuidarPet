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
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, Cliente, PaginatedData } from '@/types';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { PawPrint, Search, Trash2, UserPlus, Users } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    clientes: PaginatedData<Cliente>;
    filters: { search?: string };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Clientes & Tutores', href: '/clientes' },
];

const search = ref(props.filters.search || '');
const isCreateOpen = ref(false);

const form = useForm({
    nome: '',
    cpf: '',
    email: '',
    endereco: '',
});

function handleSearch() {
    router.get('/clientes', { search: search.value }, { preserveState: true, replace: true });
}

function submitCreate() {
    form.post('/clientes', {
        onSuccess: () => {
            isCreateOpen.value = false;
            form.reset();
        },
    });
}

function deleteCliente(id: number, nome: string) {
    if (confirm(`Deseja realmente remover o tutor "${nome}" e todos os seus pets?`)) {
        router.delete(`/clientes/${id}`);
    }
}
</script>

<template>
    <Head title="Clientes & Tutores" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-5 p-4 md:p-6">
            <!-- Header bar with title, search and button -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl font-bold tracking-tight text-[#176B87] dark:text-[#A8DCCF] flex items-center gap-2">
                        <Users class="h-6 w-6 text-[#36A69A]" />
                        Tutores & Clientes
                    </h1>
                    <p class="text-xs text-muted-foreground mt-0.5">
                        Gerencie os tutores cadastrados e seus animais de estimação vinculados.
                    </p>
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <!-- Search input -->
                    <div class="relative w-full sm:w-64">
                        <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                        <Input
                            v-model="search"
                            @keyup.enter="handleSearch"
                            placeholder="Buscar por nome, CPF..."
                            class="pl-9 text-xs h-9 rounded-xl"
                        />
                    </div>

                    <!-- Modal Cadastrar Cliente -->
                    <Dialog v-model:open="isCreateOpen">
                        <DialogTrigger as-child>
                            <Button class="bg-[#36A69A] hover:bg-[#2c8d82] text-white text-xs h-9 rounded-xl font-semibold gap-1.5 shrink-0">
                                <UserPlus class="h-4 w-4" />
                                <span>Novo Tutor</span>
                            </Button>
                        </DialogTrigger>
                        <DialogContent class="sm:max-w-md rounded-2xl">
                            <DialogHeader>
                                <DialogTitle class="text-[#176B87] dark:text-[#A8DCCF]">Cadastrar Tutor</DialogTitle>
                                <DialogDescription class="text-xs">
                                    Preencha os dados do responsável pelo pet.
                                </DialogDescription>
                            </DialogHeader>

                            <form @submit.prevent="submitCreate" class="space-y-3.5 py-2">
                                <div class="space-y-1">
                                    <Label for="nome" class="text-xs font-semibold">Nome Completo *</Label>
                                    <Input id="nome" v-model="form.nome" placeholder="Ex: Ana Clara Silveira" required class="text-xs rounded-xl" />
                                    <span v-if="form.errors.nome" class="text-[11px] text-destructive">{{ form.errors.nome }}</span>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div class="space-y-1">
                                        <Label for="cpf" class="text-xs font-semibold">CPF *</Label>
                                        <Input id="cpf" v-model="form.cpf" placeholder="000.000.000-00" required class="text-xs rounded-xl" />
                                        <span v-if="form.errors.cpf" class="text-[11px] text-destructive">{{ form.errors.cpf }}</span>
                                    </div>
                                    <div class="space-y-1">
                                        <Label for="email" class="text-xs font-semibold">E-mail *</Label>
                                        <Input id="email" type="email" v-model="form.email" placeholder="cliente@email.com" required class="text-xs rounded-xl" />
                                        <span v-if="form.errors.email" class="text-[11px] text-destructive">{{ form.errors.email }}</span>
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <Label for="endereco" class="text-xs font-semibold">Endereço Completo *</Label>
                                    <Input id="endereco" v-model="form.endereco" placeholder="Rua, número, bairro e cidade" required class="text-xs rounded-xl" />
                                    <span v-if="form.errors.endereco" class="text-[11px] text-destructive">{{ form.errors.endereco }}</span>
                                </div>

                                <DialogFooter class="pt-3">
                                    <Button type="button" variant="outline" @click="isCreateOpen = false" class="text-xs rounded-xl">
                                        Cancelar
                                    </Button>
                                    <Button type="submit" :disabled="form.processing" class="bg-[#176B87] hover:bg-[#125368] text-white text-xs rounded-xl font-bold">
                                        Salvar Tutor
                                    </Button>
                                </DialogFooter>
                            </form>
                        </DialogContent>
                    </Dialog>
                </div>
            </div>

            <!-- Clients List Card / Table -->
            <Card class="rounded-2xl shadow-sm border border-border/80 overflow-hidden">
                <CardHeader class="px-5 py-4 border-b border-border/60">
                    <CardTitle class="text-sm font-semibold text-[#176B87] dark:text-[#A8DCCF]">
                        Tutores Cadastrados ({{ clientes.total }})
                    </CardTitle>
                    <CardDescription class="text-xs">
                        Lista completa de clientes com contagem de animais sob cuidado.
                    </CardDescription>
                </CardHeader>
                <CardContent class="p-0">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-muted/40 text-[10px] font-bold text-muted-foreground uppercase tracking-wider border-b border-border/60">
                                <tr>
                                    <th class="py-3 px-5">Tutor</th>
                                    <th class="py-3 px-5">CPF</th>
                                    <th class="py-3 px-5">E-mail</th>
                                    <th class="py-3 px-5">Endereço</th>
                                    <th class="py-3 px-5 text-center">Pets</th>
                                    <th class="py-3 px-5 text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border/60">
                                <tr v-for="cliente in clientes.data" :key="cliente.id" class="hover:bg-muted/30 transition">
                                    <td class="py-3.5 px-5 font-semibold text-[#176B87] dark:text-[#A8DCCF]">
                                        <Link :href="`/clientes/${cliente.id}`" class="hover:underline flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full bg-[#176B87]/15 text-[#176B87] dark:text-[#A8DCCF] flex items-center justify-center font-bold text-[11px]">
                                                {{ cliente.nome.charAt(0) }}
                                            </div>
                                            <span>{{ cliente.nome }}</span>
                                        </Link>
                                    </td>
                                    <td class="py-3.5 px-5 font-mono text-muted-foreground">{{ cliente.cpf }}</td>
                                    <td class="py-3.5 px-5 text-muted-foreground">{{ cliente.email }}</td>
                                    <td class="py-3.5 px-5 text-muted-foreground truncate max-w-[200px]">{{ cliente.endereco }}</td>
                                    <td class="py-3.5 px-5 text-center">
                                        <span class="inline-flex items-center gap-1 bg-[#A8DCCF]/25 text-[#176B87] dark:text-[#A8DCCF] font-bold px-2 py-0.5 rounded-full text-[10px]">
                                            <PawPrint class="h-3 w-3" />
                                            {{ cliente.pets_count ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-5 text-right space-x-2">
                                        <Link :href="`/clientes/${cliente.id}`">
                                            <Button variant="ghost" size="sm" class="text-xs h-7 text-[#36A69A] hover:text-[#176B87] hover:bg-muted/50 rounded-lg">
                                                Ver Detalhes
                                            </Button>
                                        </Link>
                                        <Button
                                            @click="deleteCliente(cliente.id, cliente.nome)"
                                            variant="ghost"
                                            size="sm"
                                            class="text-xs h-7 text-destructive hover:bg-destructive/10 rounded-lg p-1.5"
                                        >
                                            <Trash2 class="h-3.5 w-3.5" />
                                        </Button>
                                    </td>
                                </tr>
                                <tr v-if="clientes.data.length === 0">
                                    <td colspan="6" class="py-8 text-center text-muted-foreground text-xs">
                                        Nenhum tutor encontrado com os critérios de busca.
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
