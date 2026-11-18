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
import type { BreadcrumbItem, FormaPagamento, Pet, Servico, Vacina } from '@/types';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import {
    Activity,
    ArrowLeft,
    Calendar,
    Clock,
    CreditCard,
    FileText,
    PawPrint,
    Plus,
    Printer,
    ShieldCheck,
    Syringe,
    Trash2,
    User,
} from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    pet: Pet;
    vacinas?: Vacina[];
    servicos?: Servico[];
    formasPagamento?: FormaPagamento[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Pets', href: '/pets' },
    { title: props.pet.nome, href: `/pets/${props.pet.id}` },
];

const isAddServicoOpen = ref(false);
const isAddVacinaOpen = ref(false);

// Form for registering service
const servicoForm = useForm({
    pet_id: props.pet.id,
    servico_id: '',
    forma_pagamento_id: '',
    data: new Date().toISOString().split('T')[0],
});

function submitServico() {
    servicoForm.post('/registros-servicos', {
        onSuccess: () => {
            isAddServicoOpen.value = false;
            servicoForm.reset('servico_id', 'forma_pagamento_id');
        },
    });
}

// Form for registering vaccination
const vacinaForm = useForm({
    pet_id: props.pet.id,
    vacina_id: '',
    data: new Date().toISOString().split('T')[0],
    validade: '',
});

function submitVacina() {
    vacinaForm.post('/vacinacoes', {
        onSuccess: () => {
            isAddVacinaOpen.value = false;
            vacinaForm.reset('vacina_id', 'validade');
        },
    });
}

function deleteServico(id: number) {
    if (confirm('Deseja remover este registro de serviço?')) {
        router.delete(`/registros-servicos/${id}`);
    }
}

function deleteVacina(id: number) {
    if (confirm('Deseja remover esta vacinação?')) {
        router.delete(`/vacinacoes/${id}`);
    }
}

function isValida(validadeDate: string | null): boolean {
    if (!validadeDate) return true;
    return new Date(validadeDate) >= new Date();
}

function printProntuario() {
    window.print();
}

const dataEmissao = new Date().toLocaleDateString('pt-BR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
});
</script>

<template>
    <Head :title="`Prontuário - ${pet.nome}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-6 p-4 md:p-6 print:p-0">
            <!-- Cabeçalho Exclusivo para Impressão / PDF (Apenas Print) -->
            <div class="hidden print:block mb-4 border-b-2 border-[#176B87] pb-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-[#176B87] flex items-center justify-center text-white text-2xl shrink-0 font-bold">
                            🐾
                        </div>
                        <div>
                            <h1 class="text-xl font-extrabold text-[#176B87]">CuidarPet - Clínica Veterinária</h1>
                            <p class="text-xs text-gray-600 italic">"Cuidando de quem faz parte da família"</p>
                        </div>
                    </div>
                    <div class="text-right text-xs text-gray-600">
                        <p><strong>Data de Emissão:</strong> {{ dataEmissao }}</p>
                        <p><strong>Prontuário Nº:</strong> #PET-{{ String(pet.id).padStart(5, '0') }}</p>
                    </div>
                </div>
                <div class="mt-3 text-center">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-gray-800 border-t border-b border-gray-200 py-1 bg-gray-50">
                        Histórico Clínico e Carteira de Saúde do Paciente
                    </h2>
                </div>
            </div>

            <!-- Back and Actions Top Bar (Oculto na Impressão) -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 no-print print:hidden">
                <Link href="/pets" class="inline-flex items-center gap-1.5 text-xs text-muted-foreground hover:text-[#176B87] dark:hover:text-[#A8DCCF] transition">
                    <ArrowLeft class="h-4 w-4" />
                    <span>Voltar para Lista de Pets</span>
                </Link>

                <div class="flex flex-wrap gap-2">
                    <!-- Modal Registrar Atendimento -->
                    <Dialog v-model:open="isAddServicoOpen">
                        <DialogTrigger as-child>
                            <Button class="bg-[#36A69A] hover:bg-[#2c8d82] text-white text-xs h-9 rounded-xl font-semibold gap-1.5 shadow-sm">
                                <Plus class="h-4 w-4" />
                                <span>Lançar Atendimento</span>
                            </Button>
                        </DialogTrigger>
                        <DialogContent class="sm:max-w-md rounded-2xl">
                            <DialogHeader>
                                <DialogTitle class="text-[#176B87] dark:text-[#A8DCCF]">Registrar Atendimento</DialogTitle>
                                <DialogDescription class="text-xs">
                                    Lançar consulta, cirurgia, banho ou procedimento para <strong>{{ pet.nome }}</strong>.
                                </DialogDescription>
                            </DialogHeader>

                            <form @submit.prevent="submitServico" class="space-y-3.5 py-2">
                                <div class="space-y-1">
                                    <Label class="text-xs font-semibold">Serviço Realizado *</Label>
                                    <select v-model="servicoForm.servico_id" required class="w-full text-xs bg-background border border-border rounded-xl p-2.5 focus:outline-none focus:ring-1 focus:ring-[#36A69A]">
                                        <option disabled value="">Selecione o serviço</option>
                                        <option v-for="s in servicos" :key="s.id" :value="s.id">{{ s.descricao }}</option>
                                    </select>
                                    <span v-if="servicoForm.errors.servico_id" class="text-[11px] text-destructive">{{ servicoForm.errors.servico_id }}</span>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div class="space-y-1">
                                        <Label class="text-xs font-semibold">Data do Atendimento *</Label>
                                        <Input type="date" v-model="servicoForm.data" required class="text-xs rounded-xl" />
                                        <span v-if="servicoForm.errors.data" class="text-[11px] text-destructive">{{ servicoForm.errors.data }}</span>
                                    </div>
                                    <div class="space-y-1">
                                        <Label class="text-xs font-semibold">Forma de Pagamento *</Label>
                                        <select v-model="servicoForm.forma_pagamento_id" required class="w-full text-xs bg-background border border-border rounded-xl p-2.5 focus:outline-none focus:ring-1 focus:ring-[#36A69A]">
                                            <option disabled value="">Forma de pgto</option>
                                            <option v-for="fp in formasPagamento" :key="fp.id" :value="fp.id">{{ fp.descricao }}</option>
                                        </select>
                                        <span v-if="servicoForm.errors.forma_pagamento_id" class="text-[11px] text-destructive">{{ servicoForm.errors.forma_pagamento_id }}</span>
                                    </div>
                                </div>

                                <DialogFooter class="pt-3">
                                    <Button type="button" variant="outline" @click="isAddServicoOpen = false" class="text-xs rounded-xl">Cancelar</Button>
                                    <Button type="submit" :disabled="servicoForm.processing" class="bg-[#176B87] hover:bg-[#125368] text-white text-xs font-bold rounded-xl">
                                        Gravar no Prontuário
                                    </Button>
                                </DialogFooter>
                            </form>
                        </DialogContent>
                    </Dialog>

                    <!-- Modal Aplicar Vacina -->
                    <Dialog v-model:open="isAddVacinaOpen">
                        <DialogTrigger as-child>
                            <Button class="bg-[#176B87] hover:bg-[#125368] text-white text-xs h-9 rounded-xl font-semibold gap-1.5 shadow-sm">
                                <Syringe class="h-4 w-4" />
                                <span>Aplicar Vacina</span>
                            </Button>
                        </DialogTrigger>
                        <DialogContent class="sm:max-w-md rounded-2xl">
                            <DialogHeader>
                                <DialogTitle class="text-[#176B87] dark:text-[#A8DCCF]">Registrar Vacinação</DialogTitle>
                                <DialogDescription class="text-xs">
                                    Registrar aplicação de dose na carteira de <strong>{{ pet.nome }}</strong>.
                                </DialogDescription>
                            </DialogHeader>

                            <form @submit.prevent="submitVacina" class="space-y-3.5 py-2">
                                <div class="space-y-1">
                                    <Label class="text-xs font-semibold">Vacina Aplicada *</Label>
                                    <select v-model="vacinaForm.vacina_id" required class="w-full text-xs bg-background border border-border rounded-xl p-2.5 focus:outline-none focus:ring-1 focus:ring-[#36A69A]">
                                        <option disabled value="">Selecione a vacina</option>
                                        <option v-for="v in vacinas" :key="v.id" :value="v.id">{{ v.descricao }}</option>
                                    </select>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div class="space-y-1">
                                        <Label class="text-xs font-semibold">Data da Aplicação *</Label>
                                        <Input type="date" v-model="vacinaForm.data" required class="text-xs rounded-xl" />
                                    </div>
                                    <div class="space-y-1">
                                        <Label class="text-xs font-semibold">Validade / Próximo Reforço</Label>
                                        <Input type="date" v-model="vacinaForm.validade" class="text-xs rounded-xl" />
                                    </div>
                                </div>

                                <DialogFooter class="pt-3">
                                    <Button type="button" variant="outline" @click="isAddVacinaOpen = false" class="text-xs rounded-xl">Cancelar</Button>
                                    <Button type="submit" :disabled="vacinaForm.processing" class="bg-[#36A69A] hover:bg-[#2c8d82] text-white text-xs font-bold rounded-xl">
                                        Salvar Vacinação
                                    </Button>
                                </DialogFooter>
                            </form>
                        </DialogContent>
                    </Dialog>

                    <!-- Print Button -->
                    <Button variant="outline" size="sm" @click="printProntuario" class="text-xs h-9 rounded-xl gap-1.5 cursor-pointer hover:border-[#176B87]">
                        <Printer class="h-3.5 w-3.5 text-[#176B87]" />
                        <span>Imprimir</span>
                    </Button>
                </div>
            </div>

            <!-- Pet Profile Header Card -->
            <Card class="rounded-2xl shadow-sm border border-border/80 print:shadow-none print:border-gray-300 print:bg-white print:text-black">
                <CardContent class="p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 rounded-2xl bg-[#176B87]/15 text-[#176B87] dark:text-[#A8DCCF] flex items-center justify-center text-3xl shrink-0 shadow-sm print:border print:border-gray-200">
                            🐕
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <h1 class="text-xl font-bold text-[#176B87] dark:text-[#A8DCCF] print:text-black">{{ pet.nome }}</h1>
                                <span :class="pet.genero === 'M' ? 'bg-blue-500/15 text-blue-500 print:border print:border-blue-300' : 'bg-pink-500/15 text-pink-500 print:border print:border-pink-300'" class="text-[10px] font-bold px-2 py-0.5 rounded-full">
                                    {{ pet.genero === 'M' ? 'Macho' : 'Fêmea' }}
                                </span>
                                <span :class="pet.castrado ? 'bg-emerald-500/15 text-emerald-500 print:border print:border-emerald-300' : 'bg-muted text-muted-foreground'" class="text-[10px] font-bold px-2 py-0.5 rounded-full">
                                    {{ pet.castrado ? 'Castrado' : 'Não castrado' }}
                                </span>
                            </div>
                            <p class="text-xs text-muted-foreground print:text-gray-600 mt-0.5">{{ pet.raca }} • {{ pet.idade || 'Idade n/d' }} • Nascido em {{ pet.data_nascimento }}</p>
                            <div class="flex items-center gap-1.5 mt-2 text-xs">
                                <User class="h-3.5 w-3.5 text-[#36A69A]" />
                                <span class="text-muted-foreground print:text-gray-600">Tutor:</span>
                                <Link v-if="pet.cliente" :href="`/clientes/${pet.cliente.id}`" class="font-semibold text-[#176B87] dark:text-[#A8DCCF] print:text-black hover:underline">
                                    {{ pet.cliente.nome }} (CPF: {{ pet.cliente.cpf }})
                                </Link>
                                <span v-else class="text-muted-foreground italic">Não vinculado</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 text-xs bg-muted/30 print:bg-gray-50 p-3 rounded-xl border border-border/50 print:border-gray-300">
                        <div class="text-center px-2">
                            <span class="text-muted-foreground print:text-gray-600 block text-[10px] uppercase font-semibold">Atendimentos</span>
                            <strong class="text-base text-[#176B87] dark:text-[#A8DCCF] print:text-black">{{ pet.registros_servicos?.length || 0 }}</strong>
                        </div>
                        <div class="w-px h-8 bg-border print:bg-gray-300"></div>
                        <div class="text-center px-2">
                            <span class="text-muted-foreground print:text-gray-600 block text-[10px] uppercase font-semibold">Vacinas</span>
                            <strong class="text-base text-[#36A69A] print:text-black">{{ pet.vacinacoes?.length || 0 }}</strong>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Grid: Timeline of Services (Left) + Vaccination Records (Right) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 print:grid-cols-1 print:gap-5">
                <!-- Left: Clinical Timeline of Services -->
                <div class="lg:col-span-2 space-y-4">
                    <Card class="rounded-2xl shadow-sm border border-border/80 print:shadow-none print:border-gray-300 print:bg-white print:text-black">
                        <CardHeader class="pb-3 border-b border-border/60 print:border-gray-200 flex flex-row items-center justify-between">
                            <div>
                                <CardTitle class="text-sm font-bold text-[#176B87] dark:text-[#A8DCCF] print:text-black flex items-center gap-2">
                                    <Activity class="h-4 w-4 text-[#36A69A] print:hidden" />
                                    <span>Prontuário & Linha do Tempo de Atendimentos</span>
                                </CardTitle>
                                <CardDescription class="text-xs print:text-gray-600">
                                    Histórico cronológico de consultas, exames, procedimentos e cirurgias realizadas.
                                </CardDescription>
                            </div>
                            <Button size="sm" @click="isAddServicoOpen = true" class="bg-[#36A69A] hover:bg-[#2c8d82] text-white text-xs h-7 rounded-xl gap-1 no-print print:hidden">
                                <Plus class="h-3 w-3" />
                                <span>Adicionar</span>
                            </Button>
                        </CardHeader>

                        <CardContent class="p-5">
                            <div v-if="pet.registros_servicos && pet.registros_servicos.length > 0" class="relative border-l-2 border-[#A8DCCF]/60 ml-3.5 space-y-4 print:border-l-2 print:border-gray-400">
                                <div v-for="reg in pet.registros_servicos" :key="reg.id" class="relative pl-6">
                                    <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-[#176B87] border-2 border-background shadow-sm print:bg-gray-700"></div>
                                    <div class="p-3.5 rounded-xl bg-muted/40 print:bg-gray-50 border border-border/60 print:border-gray-200 transition">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-bold text-[#176B87] dark:text-[#A8DCCF] print:text-black flex items-center gap-1.5">
                                                <FileText class="h-3.5 w-3.5 text-[#36A69A] print:hidden" />
                                                {{ reg.servico?.descricao || 'Procedimento Clínico' }}
                                            </span>
                                            <div class="flex items-center gap-2">
                                                <span class="text-[11px] text-muted-foreground print:text-gray-700 flex items-center gap-1 font-medium">
                                                    <Clock class="h-3 w-3 print:hidden" />
                                                    Data: {{ reg.data }}
                                                </span>
                                                <Button @click="deleteServico(reg.id)" variant="ghost" size="sm" class="h-6 w-6 p-0 text-destructive hover:bg-destructive/10 rounded-md no-print print:hidden">
                                                    <Trash2 class="h-3 w-3" />
                                                </Button>
                                            </div>
                                        </div>
                                        <div class="mt-2.5 flex items-center justify-between text-[11px] text-muted-foreground print:text-gray-700 pt-2 border-t border-border/40 print:border-gray-200">
                                            <span class="flex items-center gap-1">
                                                <CreditCard class="h-3 w-3 text-[#36A69A] print:hidden" />
                                                Forma de Pagamento:
                                                <strong class="text-foreground print:text-black">{{ reg.forma_pagamento?.descricao || 'Não especificada' }}</strong>
                                            </span>
                                            <span class="bg-[#176B87]/10 text-[#176B87] dark:text-[#A8DCCF] print:bg-gray-200 print:text-black px-2 py-0.5 rounded-md font-semibold text-[10px]">
                                                Concluído
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-else class="p-6 text-center border-2 border-dashed border-border print:border-gray-300 rounded-xl text-muted-foreground print:text-gray-500 text-xs">
                                Nenhum atendimento clínico lançado para este paciente até o momento.
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Right: Carteira de Vacinas -->
                <div class="space-y-4">
                    <Card class="rounded-2xl shadow-sm border border-border/80 print:shadow-none print:border-gray-300 print:bg-white print:text-black">
                        <CardHeader class="pb-3 border-b border-border/60 print:border-gray-200 flex flex-row items-center justify-between">
                            <div>
                                <CardTitle class="text-sm font-bold text-[#176B87] dark:text-[#A8DCCF] print:text-black flex items-center gap-2">
                                    <ShieldCheck class="h-4 w-4 text-[#36A69A] print:hidden" />
                                    <span>Carteira de Vacinação</span>
                                </CardTitle>
                                <CardDescription class="text-xs print:text-gray-600">Doses aplicadas e prazos de validade</CardDescription>
                            </div>
                            <Button size="sm" @click="isAddVacinaOpen = true" class="bg-[#176B87] hover:bg-[#125368] text-white text-xs h-7 rounded-xl gap-1 no-print print:hidden">
                                <Plus class="h-3 w-3" />
                                <span>Aplicar</span>
                            </Button>
                        </CardHeader>

                        <CardContent class="p-4 space-y-3">
                            <div v-for="vac in pet.vacinacoes" :key="vac.id" class="p-3 rounded-xl border border-border/70 print:border-gray-200 bg-card print:bg-gray-50 transition">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-foreground print:text-black">{{ vac.vacina?.descricao || 'Vacina' }}</span>
                                    <div class="flex items-center gap-1.5">
                                        <span :class="isValida(vac.validade) ? 'bg-emerald-500/15 text-emerald-600 print:border print:border-emerald-300' : 'bg-destructive/15 text-destructive print:border print:border-red-300'" class="text-[9px] font-bold px-1.5 py-0.5 rounded">
                                            {{ isValida(vac.validade) ? 'Em dia' : 'Vencida' }}
                                        </span>
                                        <Button @click="deleteVacina(vac.id)" variant="ghost" size="sm" class="h-5 w-5 p-0 text-destructive hover:bg-destructive/10 rounded-md no-print print:hidden">
                                            <Trash2 class="h-2.5 w-2.5" />
                                        </Button>
                                    </div>
                                </div>
                                <div class="mt-1.5 text-[11px] text-muted-foreground print:text-gray-700 flex items-center justify-between">
                                    <span>Aplicada em: <strong>{{ vac.data }}</strong></span>
                                    <span v-if="vac.validade">Reforço até: <strong>{{ vac.validade }}</strong></span>
                                </div>
                            </div>

                            <div v-if="!pet.vacinacoes || pet.vacinacoes.length === 0" class="p-6 text-center border-2 border-dashed border-border print:border-gray-300 rounded-xl text-muted-foreground print:text-gray-500 text-xs">
                                Nenhuma vacina registrada para este animal.
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>

            <!-- Rodapé Exclusivo para Impressão / PDF com Assinatura do Veterinário -->
            <div class="hidden print:block mt-10 pt-4 border-t border-gray-300">
                <div class="flex justify-between items-end text-xs text-gray-600">
                    <div>
                        <p>Documento gerado pelo sistema de prontuário eletrônico <strong>CuidarPet</strong>.</p>
                        <p class="text-[10px] text-gray-500">Válido como histórico clínico e carteira de vacinação do animal.</p>
                    </div>
                    <div class="text-center w-64">
                        <div class="border-b border-gray-800 mb-1"></div>
                        <p class="font-bold text-gray-900">Médico(a) Veterinário(a)</p>
                        <p class="text-[10px] text-gray-500">CRMV / Assinatura e Carimbo</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style>
@media print {
    /* Oculta tudo que é navegação, sidebar e botões interativos */
    aside,
    header,
    nav,
    .no-print,
    [data-sidebar="sidebar"],
    [data-sidebar="header"],
    [data-sidebar="trigger"],
    button:not(.allow-print) {
        display: none !important;
    }

    body {
        background-color: #ffffff !important;
        color: #000000 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    /* Expande para ocupar toda a página sem cortes laterais */
    main, [data-sidebar="content"], .flex-1 {
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }

    /* Quebra de página suave caso haja muitos registros */
    .print-break-inside-avoid {
        break-inside: avoid;
    }
}
</style>
