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
import { AlertCircle, CheckCircle2, PawPrint, Search, Trash2, UserPlus, Users } from 'lucide-vue-next';
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
const isConfirmStep = ref(false);

// Estado de exclusão
const isDeleteOpen = ref(false);
const isDeleting = ref(false);
const deleteTarget = ref<{ id: number; nome: string } | null>(null);

const form = useForm({
    nome: '',
    cpf: '',
    email: '',
    endereco: '',
});

function handleSearch() {
    router.get('/clientes', { search: search.value }, { preserveState: true, replace: true });
}

function goToPage(page: number) {
    router.get('/clientes', { search: search.value, page }, { preserveState: true, replace: true });
}

function openCreateModal() {
    isConfirmStep.value = false;
    form.clearErrors();
    isCreateOpen.value = true;
}

// 1. Fluxo de Criação com Pop-up de Confirmação Seguro
function requestCreate() {
    if (!form.nome || !form.cpf || !form.email || !form.endereco) {
        return;
    }
    isConfirmStep.value = true;
}

function cancelConfirm() {
    isConfirmStep.value = false;
}

function executeCreate() {
    form.post('/clientes', {
        preserveScroll: true,
        onSuccess: () => {
            isCreateOpen.value = false;
            isConfirmStep.value = false;
            form.reset();
        },
        onError: () => {
            isConfirmStep.value = false;
        },
    });
}

// 2. Fluxo de Exclusão com Modal Pop-up
function requestDelete(id: number, nome: string) {
    deleteTarget.value = { id, nome };
    isDeleteOpen.value = true;
}

function confirmDelete() {
    if (!deleteTarget.value) return;
    isDeleting.value = true;
    router.delete(`/clientes/${deleteTarget.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            isDeleteOpen.value = false;
            deleteTarget.value = null;
            isDeleting.value = false;
        },
        onError: () => {
            isDeleting.value = false;
        },
    });
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

                    <!-- Modal Cadastrar Cliente com Pop-up de Confirmação Integrado -->
                    <Dialog v-model:open="isCreateOpen">
                        <DialogTrigger as-child>
                            <Button @click="openCreateModal" class="bg-[#36A69A] hover:bg-[#2c8d82] text-white text-xs h-9 rounded-xl font-semibold gap-1.5 shrink-0">
                                <UserPlus class="h-4 w-4" />
                                <span>Novo Tutor</span>
                            </Button>
                        </DialogTrigger>
                        <DialogContent class="sm:max-w-md rounded-2xl">
                            <!-- ETAPA 1: Formulário de Preenchimento -->
                            <div v-if="!isConfirmStep">
                                <DialogHeader>
                                    <DialogTitle class="text-[#176B87] dark:text-[#A8DCCF]">Cadastrar Tutor</DialogTitle>
                                    <DialogDescription class="text-xs">
                                        Preencha os dados do responsável pelo pet.
                                    </DialogDescription>
                                </DialogHeader>

                                <form @submit.prevent="requestCreate" class="space-y-3.5 py-2">
                                    <div class="space-y-1">
                                        <Label for="nome" class="text-xs font-semibold">Nome Completo *</Label>
                                        <Input id="nome" v-model="form.nome" placeholder="Ex: Ana Clara Silveira" required class="text-xs rounded-xl" />
                                        <span v-if="form.errors.nome" class="text-[11px] text-destructive font-medium">{{ form.errors.nome }}</span>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="space-y-1">
                                            <Label for="cpf" class="text-xs font-semibold">CPF *</Label>
                                            <Input id="cpf" v-model="form.cpf" placeholder="000.000.000-00" required class="text-xs rounded-xl" />
                                            <span v-if="form.errors.cpf" class="text-[11px] text-destructive font-medium">{{ form.errors.cpf }}</span>
                                        </div>
                                        <div class="space-y-1">
                                            <Label for="email" class="text-xs font-semibold">E-mail *</Label>
                                            <Input id="email" type="email" v-model="form.email" placeholder="cliente@email.com" required class="text-xs rounded-xl" />
                                            <span v-if="form.errors.email" class="text-[11px] text-destructive font-medium">{{ form.errors.email }}</span>
                                        </div>
                                    </div>

                                    <div class="space-y-1">
                                        <Label for="endereco" class="text-xs font-semibold">Endereço Completo *</Label>
                                        <Input id="endereco" v-model="form.endereco" placeholder="Rua, número, bairro e cidade" required class="text-xs rounded-xl" />
                                        <span v-if="form.errors.endereco" class="text-[11px] text-destructive font-medium">{{ form.errors.endereco }}</span>
                                    </div>

                                    <DialogFooter class="pt-3">
                                        <Button type="button" variant="outline" @click="isCreateOpen = false" class="text-xs rounded-xl">
                                            Cancelar
                                        </Button>
                                        <Button type="submit" class="bg-[#176B87] hover:bg-[#125368] text-white text-xs rounded-xl font-bold">
                                            Salvar Tutor
                                        </Button>
                                    </DialogFooter>
                                </form>
                            </div>

                            <!-- ETAPA 2: Pop-up / Confirmação do Cadastro -->
                            <div v-else class="py-2 space-y-4">
                                <DialogHeader>
                                    <DialogTitle class="text-[#176B87] dark:text-[#A8DCCF] flex items-center gap-2">
                                        <CheckCircle2 class="h-5 w-5 text-[#36A69A]" />
                                        Confirmar Cadastro do Tutor
                                    </DialogTitle>
                                    <DialogDescription class="text-xs">
                                        Por favor, confirme as informações antes de gravar no banco de dados:
                                    </DialogDescription>
                                </DialogHeader>

                                <div class="bg-muted/40 p-3.5 rounded-xl border border-border/70 space-y-2 text-xs">
                                    <div>
                                        <span class="text-muted-foreground block text-[10px] uppercase font-semibold">Nome:</span>
                                        <strong class="text-foreground">{{ form.nome }}</strong>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <span class="text-muted-foreground block text-[10px] uppercase font-semibold">CPF:</span>
                                            <strong class="font-mono">{{ form.cpf }}</strong>
                                        </div>
                                        <div>
                                            <span class="text-muted-foreground block text-[10px] uppercase font-semibold">E-mail:</span>
                                            <strong>{{ form.email }}</strong>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-muted-foreground block text-[10px] uppercase font-semibold">Endereço:</span>
                                        <strong>{{ form.endereco }}</strong>
                                    </div>
                                </div>

                                <DialogFooter class="pt-2">
                                    <Button type="button" variant="outline" @click="cancelConfirm" :disabled="form.processing" class="text-xs rounded-xl">
                                        Voltar / Editar
                                    </Button>
                                    <Button @click="executeCreate" :disabled="form.processing" class="bg-[#176B87] hover:bg-[#125368] text-white text-xs rounded-xl font-bold">
                                        {{ form.processing ? 'Gravando no Banco...' : 'Sim, Confirmar e Salvar' }}
                                    </Button>
                                </DialogFooter>
                            </div>
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
                                            @click="requestDelete(cliente.id, cliente.nome)"
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

                    <!-- Paginação -->
                    <div v-if="clientes.last_page > 1" class="flex items-center justify-between px-5 py-3 border-t border-border/60 bg-muted/10">
                        <span class="text-[11px] text-muted-foreground">
                            Página {{ clientes.current_page }} de {{ clientes.last_page }} ({{ clientes.total }} tutores)
                        </span>
                        <div class="flex items-center gap-1.5">
                            <Button
                                variant="outline"
                                size="sm"
                                class="text-xs h-8 rounded-lg"
                                :disabled="clientes.current_page <= 1"
                                @click="goToPage(clientes.current_page - 1)"
                            >
                                Anterior
                            </Button>
                            <Button
                                variant="outline"
                                size="sm"
                                class="text-xs h-8 rounded-lg"
                                :disabled="clientes.current_page >= clientes.last_page"
                                @click="goToPage(clientes.current_page + 1)"
                            >
                                Próxima
                            </Button>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- Pop-up de Confirmação de Exclusão -->
        <Dialog v-model:open="isDeleteOpen">
            <DialogContent class="sm:max-w-sm rounded-2xl">
                <DialogHeader>
                    <DialogTitle class="text-destructive flex items-center gap-2">
                        <AlertCircle class="h-5 w-5" />
                        Confirmar Exclusão
                    </DialogTitle>
                    <DialogDescription class="text-xs">
                        Deseja realmente remover o tutor <strong>"{{ deleteTarget?.nome }}"</strong> e todos os animais vinculados a ele? Esta ação não pode ser desfeita.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter class="pt-3">
                    <Button type="button" variant="outline" @click="isDeleteOpen = false" class="text-xs rounded-xl">
                        Cancelar
                    </Button>
                    <Button @click="confirmDelete" :disabled="isDeleting" class="bg-destructive hover:bg-destructive/90 text-white text-xs rounded-xl font-bold">
                        {{ isDeleting ? 'Removendo...' : 'Sim, Excluir' }}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
