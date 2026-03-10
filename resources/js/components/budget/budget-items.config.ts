import type { BudgetItem, BudgetItemImportance } from '@/types';

export function createEmptyItemDraft(): Partial<BudgetItem> {
    return {
        name: '',
        importance: 'normal',
        expected_amount: null,
    };
}

export function parseExpectedAmount(value: unknown): number | null {
    const num = value !== '' ? Number(value) : null;
    return num !== null && num < 0 ? 0 : num;
}

export function formatAmount(amount: number | null | undefined): string {
    if (amount === null || amount === undefined) return '$ —';
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
}

export const importanceOptions: {
    value: BudgetItemImportance;
    label: string;
}[] = [
    { value: 'innegociable', label: 'Innegociable' },
    { value: 'high', label: 'High' },
    { value: 'normal', label: 'Normal' },
    { value: 'low', label: 'Low' },
];

export const importanceVariant: Record<
    BudgetItemImportance,
    'default' | 'secondary' | 'destructive' | 'outline'
> = {
    innegociable: 'default',
    high: 'destructive',
    normal: 'secondary',
    low: 'outline',
};

export const importanceLabel: Record<BudgetItemImportance, string> = {
    innegociable: 'Innegociable',
    high: 'High',
    normal: 'Normal',
    low: 'Low',
};

export type DisplayConfig = {
    importanceVariant: typeof importanceVariant;
    importanceLabel: typeof importanceLabel;
    formatAmount: typeof formatAmount;
};

export type EditingConfig = {
    isEditing: boolean;
    draft: Partial<BudgetItem>;
    importanceOptions: typeof importanceOptions;
    parseExpectedAmount: typeof parseExpectedAmount;
};

export const displayConfig: DisplayConfig = {
    importanceVariant,
    importanceLabel,
    formatAmount,
};

export const editingConfig = {
    importanceOptions,
    parseExpectedAmount,
};
