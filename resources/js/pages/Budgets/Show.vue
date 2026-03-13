<script setup lang="ts">
import BudgetItemsTable from '@/components/budget/BudgetItemsTable.vue';
import ThemeToggle from '@/components/ThemeToggle.vue';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { store as storeItem } from '@/routes/budgets/items';
import { destroy as destroyItem, update as updateItem } from '@/routes/items';
import type { Budget, BudgetItem } from '@/types';
import { router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    budget: Budget;
}>();

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

const handleCreateItem = (item: Partial<BudgetItem>) => {
    createForm.name = item.name || '';
    createForm.importance = item.importance ?? 'Normal';
    createForm.expected_amount = item.expected_amount ?? null;

    createForm.post(storeItem.url(props.budget.id), {
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

const handleUpdateItem = (id: string, item: Partial<BudgetItem>) => {
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

const handleDeleteItem = (id: string) => {
    // Prevents double click
    if (deletingItems.value.has(id)) {
        return;
    }

    deletingItems.value.add(id);

    router.delete(destroyItem.url(id), {
        preserveScroll: true,
        onError: (errors) => {
            error.value = 'Failed to delete budget item';
            deletingItems.value.delete(id);
        },
        onSuccess: () => {
            error.value = null;
            deletingItems.value.delete(id);
        },
    });
};
</script>

<template>
    <div class="mx-auto max-w-6xl px-6 py-4">
        <Alert v-if="error" variant="destructive" class="mb-4 text-center">
            <AlertDescription>
                {{ error }}
            </AlertDescription>
        </Alert>

        <div
            class="sticky top-0 z-40 -mx-6 mb-2 flex items-center justify-between bg-background/95 px-6 py-6 backdrop-blur-sm transition-shadow duration-200"
        >
            <div>
                <h1 class="type-display text-foreground">
                    {{ budget.name }}
                </h1>
                <p class="mt-2 text-[15px] text-muted-foreground">
                    Manage and track your estimated costs
                </p>
            </div>
            <ThemeToggle />
        </div>

        <section>
            <BudgetItemsTable
                :items="budget.items || []"
                :deleting-items="deletingItems"
                @create="handleCreateItem"
                @update="handleUpdateItem"
                @delete="handleDeleteItem"
            />
        </section>
    </div>
</template>
