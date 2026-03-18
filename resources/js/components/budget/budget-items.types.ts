import type { BudgetItem } from '@/types';

// Reusable props interface for budget item components
export interface BudgetItemProps {
    draft: Partial<BudgetItem>;
}

// Reusable emit types for budget item components
export interface BudgetItemEmits {
    'update:draft': [draft: Partial<BudgetItem>];
    save: [];
    cancel: [];
}

// Type helper for defineEmits
export type BudgetItemComponentEmits = BudgetItemEmits;
