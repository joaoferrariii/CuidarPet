import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: {
        location: string;
        url: string;
        port: null | number;
        defaults: Record<string, unknown>;
        routes: Record<string, string>;
    };
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    role?: { id: number; nome: string };
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface PaginatedData<T> {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number | null;
    to: number | null;
    prev_page_url: string | null;
    next_page_url: string | null;
}

export interface Cliente {
    id: number;
    nome: string;
    cpf: string;
    email: string;
    endereco: string;
    pets_count?: number;
    pets?: Pet[];
    created_at?: string;
}

export interface Pet {
    id: number;
    cliente_id: number;
    nome: string;
    raca: string;
    data_nascimento: string;
    idade?: string;
    genero: 'M' | 'F';
    castrado: boolean;
    cliente?: Cliente;
    vacinacoes?: Vacinacao[];
    registros_servicos?: RegistroServico[];
    created_at?: string;
}

export interface Vacina {
    id: number;
    descricao: string;
}

export interface Vacinacao {
    id: number;
    pet_id: number;
    vacina_id: number;
    data: string;
    validade: string | null;
    pet?: Pet;
    vacina?: Vacina;
}

export interface Servico {
    id: number;
    descricao: string;
}

export interface FormaPagamento {
    id: number;
    descricao: string;
}

export interface RegistroServico {
    id: number;
    pet_id: number;
    servico_id: number;
    forma_pagamento_id: number;
    data: string;
    pet?: Pet;
    servico?: Servico;
    forma_pagamento?: FormaPagamento;
}

export type BreadcrumbItemType = BreadcrumbItem;
