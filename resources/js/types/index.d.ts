import { InertiaLinkProps } from '@inertiajs/vue3';
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
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
}

export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;

export interface SortOptions {
    type?: string | null;
    direction?: 'asc' | 'desc' | null;
}

export interface QueryOptions extends Record<string, FormDataConvertible> {
    search?: string;
    sort?: 'asc' | 'desc';
    sortBy?: string;
}

export interface BudgetItem {
    id: string;
    name: string;
    expected_amount: number | undefined;
    importance: 'MustHave' | 'High' | 'Normal' | 'Low' | null;
}
