<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import {
    Activity,
    ClipboardList,
    FileText,
    PawPrint,
    Plus,
    ShieldCheck,
    Syringe,
    Users,
} from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Painel Geral',
        href: '/dashboard',
    },
];
</script>

<template>
    <Head title="Painel Geral" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-6 p-4 md:p-6">
            <!-- Welcome Banner -->
            <div class="rounded-2xl bg-gradient-to-r from-[#176B87] to-[#36A69A] p-6 text-white shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl font-bold">Painel de Atendimento CuidarPet</h1>
                    <p class="mt-1 max-w-xl text-xs text-white/80">
                        Gestão clínica veterinária completa: controle de pacientes, prontuário individual, registros de serviços e acompanhamento de vacinas.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <Link href="/pets/create">
                        <Button class="bg-white/20 hover:bg-white/30 text-white text-xs font-semibold rounded-xl gap-1.5">
                            <Plus class="h-3.5 w-3.5" />
                            <span>Novo Pet</span>
                        </Button>
                    </Link>
                    <Link href="/registros-servicos/create">
                        <Button class="bg-white text-[#176B87] hover:bg-white/90 text-xs font-bold rounded-xl gap-1.5 shadow-sm">
                            <Plus class="h-3.5 w-3.5 text-[#36A69A]" />
                            <span>Lançar Serviço</span>
                        </Button>
                    </Link>
                </div>
            </div>

            <!-- Quick Navigation Action Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <Link href="/pets" class="group">
                    <Card class="rounded-2xl border border-border/80 hover:border-[#36A69A] transition shadow-sm h-full flex flex-col justify-between">
                        <CardHeader class="p-4 pb-2">
                            <div class="flex items-center justify-between">
                                <div class="w-10 h-10 rounded-xl bg-[#176B87]/15 text-[#176B87] dark:text-[#A8DCCF] flex items-center justify-center">
                                    <PawPrint class="h-5 w-5" />
                                </div>
                                <span class="text-xs text-[#36A69A] group-hover:underline font-semibold">Acessar &rarr;</span>
                            </div>
                            <CardTitle class="text-sm font-bold mt-2 text-[#176B87] dark:text-[#A8DCCF]">
                                Pacientes (Pets)
                            </CardTitle>
                            <CardDescription class="text-xs">
                                Fichas e prontuários clínicos individuais de todos os animais.
                            </CardDescription>
                        </CardHeader>
                    </Card>
                </Link>

                <Link href="/clientes" class="group">
                    <Card class="rounded-2xl border border-border/80 hover:border-[#36A69A] transition shadow-sm h-full flex flex-col justify-between">
                        <CardHeader class="p-4 pb-2">
                            <div class="flex items-center justify-between">
                                <div class="w-10 h-10 rounded-xl bg-[#36A69A]/15 text-[#36A69A] flex items-center justify-center">
                                    <Users class="h-5 w-5" />
                                </div>
                                <span class="text-xs text-[#36A69A] group-hover:underline font-semibold">Acessar &rarr;</span>
                            </div>
                            <CardTitle class="text-sm font-bold mt-2 text-[#176B87] dark:text-[#A8DCCF]">
                                Tutores & Clientes
                            </CardTitle>
                            <CardDescription class="text-xs">
                                Cadastro completo de responsáveis e dados de contato.
                            </CardDescription>
                        </CardHeader>
                    </Card>
                </Link>

                <Link href="/vacinacoes" class="group">
                    <Card class="rounded-2xl border border-border/80 hover:border-[#36A69A] transition shadow-sm h-full flex flex-col justify-between">
                        <CardHeader class="p-4 pb-2">
                            <div class="flex items-center justify-between">
                                <div class="w-10 h-10 rounded-xl bg-amber-500/15 text-amber-500 flex items-center justify-center">
                                    <Syringe class="h-5 w-5" />
                                </div>
                                <span class="text-xs text-[#36A69A] group-hover:underline font-semibold">Acessar &rarr;</span>
                            </div>
                            <CardTitle class="text-sm font-bold mt-2 text-[#176B87] dark:text-[#A8DCCF]">
                                Carteira de Vacinas
                            </CardTitle>
                            <CardDescription class="text-xs">
                                Histórico de imunizações, lotes e prazos de validade.
                            </CardDescription>
                        </CardHeader>
                    </Card>
                </Link>

                <Link href="/registros-servicos" class="group">
                    <Card class="rounded-2xl border border-border/80 hover:border-[#36A69A] transition shadow-sm h-full flex flex-col justify-between">
                        <CardHeader class="p-4 pb-2">
                            <div class="flex items-center justify-between">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/15 text-emerald-500 flex items-center justify-center">
                                    <ClipboardList class="h-5 w-5" />
                                </div>
                                <span class="text-xs text-[#36A69A] group-hover:underline font-semibold">Acessar &rarr;</span>
                            </div>
                            <CardTitle class="text-sm font-bold mt-2 text-[#176B87] dark:text-[#A8DCCF]">
                                Atendimentos & Serviços
                            </CardTitle>
                            <CardDescription class="text-xs">
                                Consultas, cirurgias, banhos e lançamentos financeiros.
                            </CardDescription>
                        </CardHeader>
                    </Card>
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
