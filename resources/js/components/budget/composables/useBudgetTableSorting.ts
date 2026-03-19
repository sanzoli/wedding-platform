import type { BudgetItem } from '@/types';
import { computed, ref, type Ref } from 'vue';

type SortColumn = 'item' | 'importance' | 'expectedAmount';
type SortDirection = 'asc' | 'desc';

const importanceOrder: Record<string, number> = {
    MustHave: 4,
    High: 3,
    Normal: 2,
    Low: 1,
};

export function useBudgetTableSorting(items: Ref<BudgetItem[]>) {
    const sortColumn = ref<SortColumn | null>(null);
    const sortDirection = ref<SortDirection | null>(null);

    function toggleSort(column: SortColumn) {
        if (sortColumn.value !== column) {
            sortColumn.value = column;
            sortDirection.value = 'asc';
            return;
        }

        if (sortDirection.value === 'asc') {
            sortDirection.value = 'desc';
            return;
        }

        sortColumn.value = null;
        sortDirection.value = null;
    }

    const sortedItems = computed(() => {
        if (!sortColumn.value || !sortDirection.value) {
            return items.value;
        }

        const itemsCopy = [...items.value];
        const direction = sortDirection.value === 'asc' ? 1 : -1;

        return itemsCopy.sort((a, b) => {
            let aVal: string | number;
            let bVal: string | number;

            if (sortColumn.value === 'item') {
                aVal = a.name.toLowerCase();
                bVal = b.name.toLowerCase();
                return aVal < bVal ? -direction : aVal > bVal ? direction : 0;
            }

            if (sortColumn.value === 'importance') {
                aVal = importanceOrder[a.importance] || 0;
                bVal = importanceOrder[b.importance] || 0;
                return (aVal - bVal) * direction;
            }

            if (sortColumn.value === 'expectedAmount') {
                aVal = a.expected_amount || 0;
                bVal = b.expected_amount || 0;
                return (aVal - bVal) * direction;
            }

            return 0;
        });
    });

    return {
        sortColumn,
        sortDirection,
        toggleSort,
        sortedItems,
    };
}
