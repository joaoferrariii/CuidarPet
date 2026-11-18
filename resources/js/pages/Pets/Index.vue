<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, PaginatedData, Pet } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { FileText, PawPrint, Plus, Search, Trash2, User } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    pets: PaginatedData<Pet>;
    filters: { search?: string };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Pacientes (Pets)', href: '/pets' },
];

const search = ref(props.filters.search || '');

function handleSearch() {
    router.get('/pets', { search: search.value }, { preserveState: true, replace: true });
}

function deletePet(id: number, nome: string) {
    if (confirm(`Deseja realmente remover o pet "${nome}" do sistema?`)) {
        router.delete(`/pets/${id}`);
    }
}
</script>

<template>
    <Head title="Pacientes (Pets)" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-5 p-4 md:p-6">
            <!-- Header bar with search and create button -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl font-bold tracking-tight text-[#176B87] dark:text-[#A8DCCF] flex items-center gap-2">
                        <PawPrint class="h-6 w-6 text-[#36A69A]" />
                        Pacientes (Pets)
                    </h1>
                    <p class="text-xs text-muted-foreground mt-0.5">
                        Lista geral de animais cadastrados na clínica e acesso ao prontuário clínico.
                    </p>
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <div class="relative w-full sm:w-64">
                        <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                        <Input
                            v-model="search"
                            @keyup.enter="handleSearch"
                            placeholder="Buscar pet, raça ou tutor..."
                            class="pl-9 text-xs h-9 rounded-xl"
                        />
                    </div>

                    <Link href="/pets/create">
                        <Button class="bg-[#36A69A] hover:bg-[#2c8d82] text-white text-xs h-9 rounded-xl font-semibold gap-1.5 shrink-0">
                            <Plus class="h-4 w-4" />
                            <span>Novo Pet</span>
                        </Button>
                    </Link>
                </div>
            </div>

            <!-- Pets List Grid / Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <Card
                    v-for="pet in pets.data"
                    :key="pet.id"
                    class="rounded-2xl border border-border/80 hover:border-[#36A69A] transition shadow-sm overflow-hidden flex flex-col justify-between"
                >
                    <CardHeader class="p-4 pb-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-11 h-11 rounded-2xl bg-[#176B87]/15 text-[#176B87] dark:text-[#A8DCCF] flex items-center justify-center text-xl shrink-0">
                                    🐕
                                </div>
                                <div>
                                    <h3 class="font-bold text-sm text-[#176B87] dark:text-[#A8DCCF]">
                                        {{ pet.nome }}
                                    </h3>
                                    <p class="text-[11px] text-muted-foreground">
                                        {{ pet.raca }} • {{ pet.idade || 'Idade n/d' }}
                                    </p>
                                </div>
                            </div>
                            <span
                                :class="pet.genero === 'M' ? 'bg-blue-500/15 text-blue-500' : 'bg-pink-500/15 text-pink-500'"
                                class="text-[10px] font-bold px-2 py-0.5 rounded-full"
                            >
                                {{ pet.genero === 'M' ? 'Macho' : 'Fêmea' }}
                            </span>
                        </div>
                    </CardHeader>

                    <CardContent class="p-4 pt-2 text-xs space-y-3">
                        <div class="p-2.5 rounded-xl bg-muted/40 border border-border/60">
                            <div class="flex items-center gap-1.5 text-muted-foreground text-[11px]">
                                <User class="h-3.5 w-3.5 text-[#36A69A]" />
                                <span>Tutor Responsável:</span>
                            </div>
                            <strong class="text-xs text-[#176B87] dark:text-[#A8DCCF] block mt-0.5">
                                {{ pet.cliente?.nome || 'Não informado' }}
                            </strong>
                        </div>

                        <div class="flex items-center justify-between text-[11px] text-muted-foreground">
                            <span>Castrado: <strong>{{ pet.castrado ? 'Sim' : 'Não' }}</strong></span>
                            <span>Nasc: <strong>{{ pet.data_nascimento }}</strong></span>
                        </div>

                        <div class="pt-2 border-t border-border/60 flex items-center justify-between">
                            <Button
                                @click="deletePet(pet.id, pet.nome)"
                                variant="ghost"
                                size="sm"
                                class="text-xs h-7 text-destructive hover:bg-destructive/10 rounded-lg p-1.5"
                            >
                                <Trash2 class="h-3.5 w-3.5" />
                            </Button>

                            <Link :href="`/pets/${pet.id}`">
                                <Button size="sm" class="bg-[#176B87] hover:bg-[#125368] text-white text-xs h-7 rounded-xl font-semibold gap-1.5">
                                    <FileText class="h-3 w-3" />
                                    <span>Ver Prontuário</span>
                                </Button>
                            </Link>
                        </div>
                    </CardContent>
                </Card>

                <div v-if="pets.data.length === 0" class="col-span-full p-12 text-center border-2 border-dashed border-border rounded-2xl text-muted-foreground text-xs">
                    Nenhum paciente encontrado para a busca.
                </div>
            </div>
        </div>
    </AppLayout>
</template>
