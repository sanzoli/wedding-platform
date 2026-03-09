<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import type { BudgetItem, BudgetItemImportance } from '@/types';

defineProps<{
    items: BudgetItem[];
}>();

const importanceVariant: Record<
    BudgetItemImportance,
    'default' | 'secondary' | 'destructive' | 'outline'
> = {
    innegociable: 'default',
    high: 'secondary',
    normal: 'outline',
    low: 'outline',
};

const importanceLabel: Record<BudgetItemImportance, string> = {
    innegociable: 'Innegociable',
    high: 'High',
    normal: 'Normal',
    low: 'Low',
};

const formatAmount = (amount: number | null): string => {
    if (amount === null) return '—';
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
};
</script>

<template>
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-border">
                <th
                    class="pb-3 text-left text-xs font-medium uppercase tracking-wide text-muted-foreground"
                >
                    Item
                </th>
                <th
                    class="pb-3 text-left text-xs font-medium uppercase tracking-wide text-muted-foreground"
                >
                    Importance
                </th>
                <th
                    class="pb-3 text-right text-xs font-medium uppercase tracking-wide text-muted-foreground"
                >
                    Expected Amount
                </th>
            </tr>
        </thead>
        <tbody class="divide-y divide-border">
            <tr
                v-for="item in items"
                :key="item.id"
                class="group transition-colors hover:bg-hover"
            >
                <td class="py-4 pr-6 font-medium text-foreground">
                    {{ item.name }}
                </td>
                <td class="py-4 pr-6">
                    <Badge :variant="importanceVariant[item.importance]">
                        {{ importanceLabel[item.importance] }}
                    </Badge>
                </td>
                <td class="py-4 text-right tabular-nums text-foreground">
                    {{ formatAmount(item.expected_amount) }}
                </td>
            </tr>
        </tbody>
    </table>
</template>
