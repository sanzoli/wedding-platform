import { store as storeItem } from '@/routes/budgets/items';
import { destroy as destroyItem, update as updateItem } from '@/routes/items';
import type { BudgetItem } from '@/types';
import { router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

export const useBudgetItemForm = (budgetId: string) => {
    const error = ref<string | null>(null);
    const deletingItems = ref<Set<string>>(new Set());

    const createForm = useForm<{
        name: string;
        importance: string | null;
        expected_amount: number | null;
    }>({
        name: '',
        importance: 'Normal',
        expected_amount: null,
    });

    const handleCreate = (item: Partial<BudgetItem>) => {
        createForm.name = item.name || '';
        createForm.importance = item.importance ?? 'Normal';
        createForm.expected_amount = item.expected_amount ?? null;

        createForm.post(storeItem.url(budgetId), {
            preserveScroll: true,
            onError: () => {
                error.value = 'Failed to create budget item';
            },
            onSuccess: () => {
                createForm.reset();
                error.value = null;
            },
        });
    };

    const handleUpdate = (id: string, item: Partial<BudgetItem>) => {
        router.patch(
            updateItem.url(id),
            {
                name: item.name,
                importance: item.importance,
                expected_amount: item.expected_amount,
            },
            {
                preserveScroll: true,
                onError: () => {
                    error.value = 'Failed to update budget item';
                },
                onSuccess: () => {
                    error.value = null;
                },
            },
        );
    };

    const handleDelete = (id: string) => {
        // Prevents double click
        if (deletingItems.value.has(id)) {
            return;
        }

        deletingItems.value.add(id);

        router.delete(destroyItem.url(id), {
            preserveScroll: true,
            onError: () => {
                error.value = 'Failed to delete budget item';
                deletingItems.value.delete(id);
            },
            onSuccess: () => {
                error.value = null;
                deletingItems.value.delete(id);
            },
        });
    };

    return {
        error,
        deletingItems,
        createForm,
        handleCreate,
        handleUpdate,
        handleDelete,
    };
};
