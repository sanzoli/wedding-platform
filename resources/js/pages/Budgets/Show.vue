<script setup lang="ts">
import BudgetItemsTable from '@/components/budget/BudgetItemsTable.vue';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { useBudgetItemForm } from '@/composables/useBudgetItemForm';
import AppLayout from '@/layouts/AppLayout.vue';
import type { Budget } from '@/types';
import { Head } from '@inertiajs/vue3';

const props = defineProps<{
    budget: Budget;
}>();

const { error, deletingItems, handleCreate, handleUpdate, handleDelete } =
    useBudgetItemForm(props.budget.id);

</script>

<template>
    <Head :title="`Budget - ${budget.name}`" />

    <AppLayout>
    <div class="max-w-6xl px-6 py-4">
        <Alert v-if="error" variant="destructive" class="mb-4 text-center">
            <AlertDescription>
                {{ error }}
            </AlertDescription>
        </Alert>

        <div
            class="sticky top-0 z-40 -mx-6 mb-1 flex items-center justify-between bg-background/95 px-6 py-4 backdrop-blur-sm transition-shadow duration-200 sm:mb-2"
        >
            <div>
                <h1 class="type-display text-foreground">
                    {{ budget.name }}
                </h1>
                <p class="mt-2 text-[15px] text-muted-foreground">
                    Manage and track your estimated costs
                </p>
            </div>
        </div>

        <section>
            <BudgetItemsTable
                :items="budget.items || []"
                :deleting-items="deletingItems"
                @create="handleCreate"
                @update="handleUpdate"
                @delete="handleDelete"
            />
        </section>
    </div>
    </AppLayout>
</template>
