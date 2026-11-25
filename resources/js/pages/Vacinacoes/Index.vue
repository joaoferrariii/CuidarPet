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
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, PaginatedData, Vacinacao } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { AlertCircle, Plus, Syringe, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';

defineProps<{
    vacinacoes: PaginatedData<Vacinacao>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Vacinações', href: '/vacinacoes' },
];

const isDeleteOpen = ref(false);
const isDeleting = ref(false);
const deleteTarget = ref<{ id: number; nomeVacina: string; nomePet: string } | null>(null);

function requestDeleteVacinacao(id: number, nomeVacina: string, nomePet: string) {
    deleteTarget.value = { id, nomeVacina, nomePet };
    isDeleteOpen.value = true;
}

function confirmDeleteVacinacao() {
    if (!deleteTarget.value) return;
    isDeleting.value = true;
    router.delete(`/vacinacoes/${deleteTarget.value.id}`, {
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

function goToPage(page: number) {
    router.get('/vacinacoes', { page }, { preserveState: true, replace: true });
}

function isValida(validadeDate: string | null): boolean {
    if (!validadeDate) return true;

    let targetDate: Date;
    if (validadeDate.includes('/')) {
        const parts = validadeDate.split('/');
        if (parts.length === 3) {
            const day = parseInt(parts[0], 10);
            const month = parseInt(parts[1], 10) - 1;
            const year = parseInt(parts[2], 10);
            targetDate = new Date(year, month, day, 23, 59, 59, 999);
        } else {
            targetDate = new Date(validadeDate);
        }
    } else if (validadeDate.includes('-')) {
        const parts = validadeDate.split('-');
        if (parts.length === 3) {
            const year = parseInt(parts[0], 10);
            const month = parseInt(parts[1], 10) - 1;
            const day = parseInt(parts[2], 10);
            targetDate = new Date(year, month, day, 23, 59, 59, 999);
        } else {
            targetDate = new Date(validadeDate);
        }
    } else {
        targetDate = new Date(validadeDate);
    }

    if (isNaN(targetDate.getTime())) return false;
    return targetDate >= new Date();
}
</script>

<template>
    <Head title="Registro de Vacinações" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-5 p-4 md:p-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl font-bold tracking-tight text-[#176B87] dark:text-[#A8DCCF] flex items-center gap-2">
                        <Syringe class="h-6 w-6 text-[#36A69A]" />
                        Vacinações Aplicadas
                    </h1>
                    <p class="text-xs text-muted-foreground mt-0.5">
                        Controle geral de imunização e prazos de validade para todos os pacientes.
                    </p>
                </div>

                <Link href="/vacinacoes/create">
                    <Button class="bg-[#36A69A] hover:bg-[#2c8d82] text-white text-xs h-9 rounded-xl font-semibold gap-1.5 shrink-0">
                        <Plus class="h-4 w-4" />
                        <span>Nova Aplicação</span>
                    </Button>
                </Link>
            </div>

            <Card class="rounded-2xl border border-border/80 shadow-sm overflow-hidden">
                <CardHeader class="px-5 py-4 border-b border-border/60">
                    <CardTitle class="text-sm font-semibold text-[#176B87] dark:text-[#A8DCCF]">
                        Histórico de Imunização ({{ vacinacoes.total }})
                    </CardTitle>
                    <CardDescription class="text-xs">
                        Acompanhamento de doses e alertas de reforço.
                    </CardDescription>
                </CardHeader>
                <CardContent class="p-0">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-muted/40 text-[10px] font-bold text-muted-foreground uppercase tracking-wider border-b border-border/60">
                                <tr>
                                    <th class="py-3 px-5">Paciente (Pet)</th>
                                    <th class="py-3 px-5">Tutor</th>
                                    <th class="py-3 px-5">Vacina</th>
                                    <th class="py-3 px-5">Data de Aplicação</th>
                                    <th class="py-3 px-5">Validade</th>
                                    <th class="py-3 px-5 text-center">Status</th>
                                    <th class="py-3 px-5 text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border/60">
                                <tr v-for="vac in vacinacoes.data" :key="vac.id" class="hover:bg-muted/30 transition">
                                    <td class="py-3.5 px-5 font-semibold text-[#176B87] dark:text-[#A8DCCF]">
                                        <Link v-if="vac.pet" :href="`/pets/${vac.pet.id}`" class="hover:underline flex items-center gap-2">
                                            <span>🐕</span>
                                            <span>{{ vac.pet.nome }}</span>
                                        </Link>
                                    </td>
                                    <td class="py-3.5 px-5 text-muted-foreground">
                                        {{ vac.pet?.cliente?.nome || 'Não informado' }}
                                    </td>
                                    <td class="py-3.5 px-5 font-medium text-foreground">
                                        {{ vac.vacina?.descricao || 'Vacina' }}
                                    </td>
                                    <td class="py-3.5 px-5 text-muted-foreground">{{ vac.data }}</td>
                                    <td class="py-3.5 px-5 text-muted-foreground">{{ vac.validade || 'Sem validade' }}</td>
                                    <td class="py-3.5 px-5 text-center">
                                        <span :class="isValida(vac.validade) ? 'bg-emerald-500/15 text-emerald-500' : 'bg-destructive/15 text-destructive'" class="text-[10px] font-bold px-2 py-0.5 rounded-full">
                                            {{ isValida(vac.validade) ? 'Ativa' : 'Vencida' }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-5 text-right">
                                        <Button @click="requestDeleteVacinacao(vac.id, vac.vacina?.descricao || 'Vacina', vac.pet?.nome || 'Pet')" variant="ghost" size="sm" class="text-xs h-7 text-destructive hover:bg-destructive/10 rounded-lg p-1.5">
                                            <Trash2 class="h-3.5 w-3.5" />
                                        </Button>
                                    </td>
                                </tr>
                                <tr v-if="vacinacoes.data.length === 0">
                                    <td colspan="7" class="py-8 text-center text-muted-foreground text-xs">
                                        Nenhuma vacinação registrada.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div v-if="vacinacoes.last_page > 1" class="flex items-center justify-between px-5 py-3 border-t border-border/60 bg-muted/10">
                        <span class="text-[11px] text-muted-foreground">
                            Página {{ vacinacoes.current_page }} de {{ vacinacoes.last_page }} ({{ vacinacoes.total }} registros)
                        </span>
                        <div class="flex items-center gap-1.5">
                            <Button
                                variant="outline"
                                size="sm"
                                class="text-xs h-8 rounded-lg"
                                :disabled="vacinacoes.current_page <= 1"
                                @click="goToPage(vacinacoes.current_page - 1)"
                            >
                                Anterior
                            </Button>
                            <Button
                                variant="outline"
                                size="sm"
                                class="text-xs h-8 rounded-lg"
                                :disabled="vacinacoes.current_page >= vacinacoes.last_page"
                                @click="goToPage(vacinacoes.current_page + 1)"
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
                        Confirmar Exclusão de Vacina
                    </DialogTitle>
                    <DialogDescription class="text-xs">
                        Deseja realmente remover o registro da vacina <strong>"{{ deleteTarget?.nomeVacina }}"</strong> do paciente <strong>"{{ deleteTarget?.nomePet }}"</strong>?
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter class="pt-3">
                    <Button type="button" variant="outline" @click="isDeleteOpen = false" class="text-xs rounded-xl">Cancelar</Button>
                    <Button @click="confirmDeleteVacinacao" :disabled="isDeleting" class="bg-destructive hover:bg-destructive/90 text-white text-xs rounded-xl font-bold">
                        {{ isDeleting ? 'Removendo...' : 'Sim, Excluir' }}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
