<script setup lang="ts">
import BudgetItemsTable from '@/components/budget/BudgetItemsTable.vue';
import ThemeToggle from '@/components/ThemeToggle.vue';
import type { Budget, BudgetItem, BudgetItemImportance } from '@/types';
import { onMounted, ref } from 'vue';

const props = defineProps<{
    budgetId: string;
}>();

const budget = ref<Budget | null>(null);
const error = ref<string | null>(null);
const isLoading = ref(true);

// Temporary local storage for budget items
let tempItemIdCounter = 1;
const tempItems = ref<BudgetItem[]>([]);

// Mock budget data for demo
const createMockBudget = (): Budget => {
    return {
        id: props.budgetId,
        name: 'Wedding Budget 2026',
        draft: false,
        items: tempItems.value,
    };
};

const generateTempId = (): string => {
    return `temp-${tempItemIdCounter++}`;
};

const loadBudget = async () => {
    try {
        // Simulate loading delay
        await new Promise((resolve) => setTimeout(resolve, 500));

        // Create mock budget with temporary items
        budget.value = createMockBudget();

        // Add some demo items for testing
        tempItems.value = [
            {
                id: generateTempId(),
                budget_id: props.budgetId,
                name: 'Venue Rental',
                importance: 'innegociable',
                expected_amount: 5000,
            },
            {
                id: generateTempId(),
                budget_id: props.budgetId,
                name: 'Photography',
                importance: 'high',
                expected_amount: 2500,
            },
            {
                id: generateTempId(),
                budget_id: props.budgetId,
                name: 'Flowers',
                importance: 'normal',
                expected_amount: 1200,
            },
        ];

        budget.value.items = tempItems.value;
    } catch {
        error.value = 'Failed to load budget';
    }
};

const handleCreateItem = (item: Partial<BudgetItem>) => {
    if (!budget.value) return;

    try {
        const newItem: BudgetItem = {
            id: generateTempId(),
            budget_id: props.budgetId,
            name: item.name || '',
            importance: (item.importance as BudgetItemImportance) || 'normal',
            expected_amount: item.expected_amount || null,
        };

        tempItems.value.push(newItem);
        budget.value.items = tempItems.value;

        console.log('Item created locally:', newItem);
    } catch (err) {
        console.error('Failed to create item:', err);
        error.value = 'Failed to create budget item';
    }
};

const handleUpdateItem = (id: string, item: Partial<BudgetItem>) => {
    if (!budget.value?.items) return;

    try {
        const index = tempItems.value.findIndex((i) => i.id === id);
        if (index !== -1) {
            tempItems.value[index] = {
                ...tempItems.value[index],
                name: item.name || tempItems.value[index].name,
                importance:
                    (item.importance as BudgetItemImportance) ||
                    tempItems.value[index].importance,
                expected_amount:
                    item.expected_amount !== undefined
                        ? item.expected_amount
                        : tempItems.value[index].expected_amount,
            };
            budget.value.items = tempItems.value;

            console.log('Item updated locally:', tempItems.value[index]);
        }
    } catch (err) {
        console.error('Failed to update item:', err);
        error.value = 'Failed to update budget item';
    }
};

const handleDeleteItem = (id: string) => {
    if (!budget.value?.items) return;

    try {
        tempItems.value = tempItems.value.filter((i) => i.id !== id);
        budget.value.items = tempItems.value;

        console.log('Item deleted locally:', id);
    } catch (err) {
        console.error('Failed to delete item:', err);
        error.value = 'Failed to delete budget item';
    }
};

onMounted(async () => {
    isLoading.value = true;
    await loadBudget();
    isLoading.value = false;
});
</script>

<template>
    <div class="mx-auto max-w-6xl px-6 py-10">
        <!-- Theme Toggle - Top Right Corner -->
        <div class="fixed top-6 right-6 z-50">
            <ThemeToggle />
        </div>
        <template v-if="isLoading">
            <div class="flex items-center justify-center py-20">
                <div class="text-center">
                    <div
                        class="mb-3 inline-block h-8 w-8 animate-spin rounded-full border-2 border-border border-t-primary"
                    ></div>
                    <p class="text-sm text-muted-foreground">
                        Loading budget...
                    </p>
                </div>
            </div>
        </template>

        <template v-else-if="error">
            <div
                class="rounded-lg border border-destructive/20 bg-destructive/5 p-6 text-center"
            >
                <p class="text-sm font-medium text-destructive">{{ error }}</p>
            </div>
        </template>

        <template v-else-if="budget">
            <header
                class="mb-10 flex items-end justify-between border-b border-border pb-6"
            >
                <div>
                    <h1 class="type-display text-foreground">
                        {{ budget.name }}
                    </h1>
                    <p class="mt-2 text-[15px] text-muted-foreground">
                        Manage and track your estimated costs
                    </p>
                </div>
            </header>

            <section>
                <BudgetItemsTable
                    :items="budget.items || []"
                    :budget-id="budget.id"
                    @create="handleCreateItem"
                    @update="handleUpdateItem"
                    @delete="handleDeleteItem"
                />
            </section>
        </template>
    </div>
</template>
