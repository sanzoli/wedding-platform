<script setup lang="ts">
import type { Budget } from '@/types';
import { onMounted, ref } from 'vue';

const props = defineProps<{
    budgetId: string;
}>();

const budget = ref<Budget | null>(null);
const error = ref<string | null>(null);
const isLoading = ref(true);

const requestJson = async <T,>(
    url: string,
    init: RequestInit = {},
): Promise<T> => {
    const response = await fetch(url, {
        credentials: 'same-origin',
        ...init,
        headers: {
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            ...(init.headers ?? {}),
        },
    });

    if (!response.ok) {
        throw new Error(`Request failed with status ${response.status}`);
    }

    return response.json() as Promise<T>;
};

onMounted(async () => {
    try {
        const budgets = await requestJson<Budget[]>('/web/budgets');
        budget.value = budgets.find((b) => b.id === props.budgetId) ?? null;

        if (!budget.value) {
            error.value = 'Budget not found';
        }
    } catch {
        error.value = 'Failed to load budget';
    } finally {
        isLoading.value = false;
    }
});
</script>

<template>
    <div class="mx-auto max-w-6xl px-6 py-10">
        <template v-if="isLoading">
            <p class="text-sm text-muted-foreground">Loading...</p>
        </template>

        <template v-else-if="error">
            <p class="text-sm text-destructive">{{ error }}</p>
        </template>

        <template v-else-if="budget">
            <header class="mb-10">
                <h1 class="text-2xl font-semibold text-foreground">
                    {{ budget.name }}
                </h1>
                <p class="mt-1 text-sm text-muted-foreground">
                    Manage and track your estimated costs
                </p>
            </header>

            <section>
                <h2 class="mb-4 text-sm font-medium text-foreground">
                    Budget Items
                </h2>

                <p
                    v-if="!budget.items || budget.items.length === 0"
                    class="text-sm text-muted-foreground"
                >
                    No budget items yet
                </p>

                <BudgetItemsTable v-else :items="budget.items" />
            </section>
        </template>
    </div>
</template>
