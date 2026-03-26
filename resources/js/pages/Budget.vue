<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BudgetItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import ItemsTable from '@/components/budget/ItemsTable.vue';

defineProps<{
    name: string;
    items: BudgetItem[];
    filters: object;
}>();

const page = usePage();
</script>

<template>
    <Head title="Budget" />

    <AppLayout>
        <div class="max-w-6xl px-6 py-4">
            <div
                class="sticky top-0 z-40 -mx-6 mb-1 flex items-center justify-between bg-background/95 px-6 py-4 backdrop-blur-sm transition-shadow duration-200 sm:mb-2"
            >
                <div>
                    <h1 class="type-display text-foreground">
                        {{ name }}
                    </h1>
                    <p class="mt-2 text-[15px] text-muted-foreground">
                        {{ page.props.trans.budget.description }}
                    </p>
                </div>
            </div>

            <ItemsTable :items :filters></ItemsTable>
        </div>
    </AppLayout>
</template>
