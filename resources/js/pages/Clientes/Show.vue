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
import type { BreadcrumbItem, Cliente } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, FileText, PawPrint, Plus, User } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    cliente: Cliente;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Tutores', href: '/clientes' },
    { title: props.cliente.nome, href: `/clientes/${props.cliente.id}` },
];

const isAddPetOpen = ref(false);

const petForm = useForm({
    cliente_id: props.cliente.id,
    nome: '',
    raca: '',
    data_nascimento: '',
    genero: 'M',
    castrado: false,
});

function submitAddPet() {
    petForm.post('/pets', {
        onSuccess: () => {
            isAddPetOpen.value = false;
            petForm.reset();
            petForm.cliente_id = props.cliente.id;
        },
    });
}
</script>

<template>
    <Head :title="cliente.nome" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-6 p-4 md:p-6">
            <!-- Header bar with back link -->
            <div class="flex items-center justify-between">
                <Link href="/clientes" class="inline-flex items-center gap-1.5 text-xs text-muted-foreground hover:text-[#176B87] dark:hover:text-[#A8DCCF] transition">
                    <ArrowLeft class="h-4 w-4" />
                    <span>Voltar para Lista de Tutores</span>
                </Link>

                <!-- Modal Cadastrar Pet para este Cliente -->
                <Dialog v-model:open="isAddPetOpen">
                    <DialogTrigger as-child>
                        <Button class="bg-[#36A69A] hover:bg-[#2c8d82] text-white text-xs h-9 rounded-xl font-semibold gap-1.5">
                            <Plus class="h-4 w-4" />
                            <span>Vincular Novo Pet</span>
                        </Button>
                    </DialogTrigger>
                    <DialogContent class="sm:max-w-md rounded-2xl">
                        <DialogHeader>
                            <DialogTitle class="text-[#176B87] dark:text-[#A8DCCF]">Cadastrar Animal de Estimação</DialogTitle>
                            <DialogDescription class="text-xs">
                                Novo pet para o tutor: <strong>{{ cliente.nome }}</strong>.
                            </DialogDescription>
                        </DialogHeader>

                        <form @submit.prevent="submitAddPet" class="space-y-3.5 py-2">
                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1">
                                    <Label for="petNome" class="text-xs font-semibold">Nome do Pet *</Label>
                                    <Input id="petNome" v-model="petForm.nome" placeholder="Ex: Thor, Mel..." required class="text-xs rounded-xl" />
                                    <span v-if="petForm.errors.nome" class="text-[11px] text-destructive">{{ petForm.errors.nome }}</span>
                                </div>
                                <div class="space-y-1">
                                    <Label for="petRaca" class="text-xs font-semibold">Raça / Espécie *</Label>
                                    <Input id="petRaca" v-model="petForm.raca" placeholder="Ex: Golden, Siamês..." required class="text-xs rounded-xl" />
                                    <span v-if="petForm.errors.raca" class="text-[11px] text-destructive">{{ petForm.errors.raca }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-3">
                                <div class="space-y-1">
                                    <Label for="dataNasc" class="text-xs font-semibold">Nascimento *</Label>
                                    <Input id="dataNasc" type="date" v-model="petForm.data_nascimento" required class="text-xs rounded-xl" />
                                    <span v-if="petForm.errors.data_nascimento" class="text-[11px] text-destructive">{{ petForm.errors.data_nascimento }}</span>
                                </div>
                                <div class="space-y-1">
                                    <Label for="genero" class="text-xs font-semibold">Gênero *</Label>
                                    <select id="genero" v-model="petForm.genero" class="w-full text-xs bg-background border border-border rounded-xl p-2.5 focus:outline-none focus:ring-1 focus:ring-[#36A69A]">
                                        <option value="M">Macho</option>
                                        <option value="F">Fêmea</option>
                                    </select>
                                </div>
                                <div class="space-y-1">
                                    <Label for="castrado" class="text-xs font-semibold">Castrado? *</Label>
                                    <select id="castrado" v-model="petForm.castrado" class="w-full text-xs bg-background border border-border rounded-xl p-2.5 focus:outline-none focus:ring-1 focus:ring-[#36A69A]">
                                        <option :value="true">Sim</option>
                                        <option :value="false">Não</option>
                                    </select>
                                </div>
                            </div>

                            <DialogFooter class="pt-3">
                                <Button type="button" variant="outline" @click="isAddPetOpen = false" class="text-xs rounded-xl">Cancelar</Button>
                                <Button type="submit" :disabled="petForm.processing" class="bg-[#176B87] hover:bg-[#125368] text-white text-xs rounded-xl font-bold">
                                    Salvar Pet
                                </Button>
                            </DialogFooter>
                        </form>
                    </DialogContent>
                </Dialog>
            </div>

            <!-- Tutor Card Details -->
            <Card class="rounded-2xl shadow-sm border border-border/80">
                <CardHeader class="pb-3 border-b border-border/60">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-[#176B87]/15 text-[#176B87] dark:text-[#A8DCCF] flex items-center justify-center font-bold text-lg">
                            <User class="h-6 w-6" />
                        </div>
                        <div>
                            <CardTitle class="text-lg font-bold text-[#176B87] dark:text-[#A8DCCF]">{{ cliente.nome }}</CardTitle>
                            <CardDescription class="text-xs">Dados cadastrais e contato do tutor responsável</CardDescription>
                        </div>
                    </div>
                </CardHeader>
                <CardContent class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 text-xs">
                    <div>
                        <span class="text-muted-foreground block text-[11px]">CPF:</span>
                        <strong class="font-mono">{{ cliente.cpf }}</strong>
                    </div>
                    <div>
                        <span class="text-muted-foreground block text-[11px]">E-mail:</span>
                        <strong>{{ cliente.email }}</strong>
                    </div>
                    <div>
                        <span class="text-muted-foreground block text-[11px]">Endereço:</span>
                        <strong>{{ cliente.endereco }}</strong>
                    </div>
                </CardContent>
            </Card>

            <!-- Linked Pets Section -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-bold text-[#176B87] dark:text-[#A8DCCF] flex items-center gap-2">
                        <PawPrint class="h-4 w-4 text-[#36A69A]" />
                        <span>Animais Vinculados ({{ cliente.pets?.length || 0 }})</span>
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <Card v-for="pet in cliente.pets" :key="pet.id" class="rounded-2xl border border-border/80 hover:border-[#36A69A] transition overflow-hidden">
                        <CardHeader class="p-4 pb-2 flex flex-row items-center justify-between space-y-0">
                            <div class="flex items-center space-x-2.5">
                                <span class="text-2xl">🐕</span>
                                <div>
                                    <h3 class="font-bold text-sm text-[#176B87] dark:text-[#A8DCCF]">{{ pet.nome }}</h3>
                                    <p class="text-[11px] text-muted-foreground">{{ pet.raca }} • {{ pet.idade || 'Idade não informada' }}</p>
                                </div>
                            </div>
                            <span :class="pet.genero === 'M' ? 'bg-blue-500/15 text-blue-500' : 'bg-pink-500/15 text-pink-500'" class="text-[10px] font-bold px-2 py-0.5 rounded-full">
                                {{ pet.genero === 'M' ? 'Macho' : 'Fêmea' }}
                            </span>
                        </CardHeader>
                        <CardContent class="p-4 pt-2 text-xs space-y-2">
                            <div class="flex items-center justify-between text-[11px] text-muted-foreground">
                                <span>Castrado: <strong>{{ pet.castrado ? 'Sim' : 'Não' }}</strong></span>
                                <span>Vacinas registradas: <strong>{{ pet.vacinacoes?.length || 0 }}</strong></span>
                            </div>
                            <div class="pt-2 border-t border-border/60 flex items-center justify-end">
                                <Link :href="`/pets/${pet.id}`">
                                    <Button size="sm" class="bg-[#36A69A] hover:bg-[#2c8d82] text-white text-xs h-7 rounded-xl font-semibold gap-1">
                                        <FileText class="h-3 w-3" />
                                        <span>Abrir Prontuário</span>
                                    </Button>
                                </Link>
                            </div>
                        </CardContent>
                    </Card>

                    <div v-if="!cliente.pets || cliente.pets.length === 0" class="col-span-full p-8 text-center border-2 border-dashed border-border rounded-2xl text-muted-foreground text-xs">
                        Nenhum animal cadastrado para este tutor ainda.
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
