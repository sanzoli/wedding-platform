<script setup lang="ts">
import AddButton from '@/components/AddButton.vue';
import Item from '@/components/budget/Item.vue';
import NewItem from '@/components/budget/NewItem.vue';
import SearchBar from '@/components/SearchBar.vue';
import Table from '@/components/Table.vue';
import TableHeader from '@/components/TableHeader.vue';
import { budget } from '@/routes';
import type { BudgetItem, QueryOptions, SortOptions } from '@/types';
import { router, usePage } from '@inertiajs/vue3';
import debounce from 'lodash/debounce';
import { reactive, ref, watch } from 'vue';

const trans = usePage().props.trans;
const props = defineProps<{
    items: BudgetItem[];
    filters: QueryOptions;
}>();

const searchValue = ref(props.filters.search);
const adding = ref(false);
const sortOptions = reactive(<SortOptions>{
    type: props.filters.sortBy,
    direction: props.filters.sort,
});

const refreshPage = function () {
    const data = <QueryOptions>{};
    if (searchValue.value) {
        data.search = searchValue.value;
    }

    if (sortOptions.type && sortOptions.direction) {
        data.sortBy = sortOptions.type;
        data.sort = sortOptions.direction;
    }

    router.get(budget().url, data, { preserveState: true, replace: true });
};

const sort = function (type: string, direction: 'asc' | 'desc' | null) {
    sortOptions.direction = direction;
    sortOptions.type = direction ? type : null;
    refreshPage();
};

watch(
    searchValue,
    debounce(() => {
        refreshPage();
    }, 300),
);
</script>

<template>
    <Table @add-item="adding = true" :search="searchValue">
        <template #toolbar>
            <div class="flex items-center justify-between gap-3">
                <SearchBar
                    v-model:search-value="searchValue"
                    :placeholder="trans.search_bar"
                ></SearchBar>

                <AddButton @add="adding = true">
                    {{ trans.budget.button.add }}
                </AddButton>
            </div>
        </template>

        <template #header>
            <TableHeader
                class="w-[300px]"
                sortBy="name"
                :sortOptions
                @update:sort-options="sort"
            >
                {{ trans.budget.label.items }} ({{ items.length }})
            </TableHeader>
            <TableHeader
                sortBy="importance"
                :sortOptions
                @update:sort-options="sort"
            >
                {{ trans.budget.label.importance }}
            </TableHeader>
            <TableHeader
                class="text-right"
                sortBy="expected_amount"
                :sortOptions
                @update:sort-options="sort"
            >
                {{ trans.budget.label.expected_amount }}
            </TableHeader>
            <TableHeader class="w-[120px]"></TableHeader>
        </template>

        <template #body>
            <NewItem v-if="adding" @close="adding = false"></NewItem>
            <Item v-for="item in items" :key="item.id" :item></Item>
        </template>
    </Table>
</template>
