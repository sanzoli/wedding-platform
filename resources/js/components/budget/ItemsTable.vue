<script setup lang="ts">
import type { BudgetItem, QueryOptions, SortOptions } from '@/types';
import SearchBar from '@/components/SearchBar.vue';
import TableHeader from '@/components/TableHeader.vue';
import Table from '@/components/Table.vue';
import { Button } from '@/components/ui/button';
import { Plus } from 'lucide-vue-next';
import ItemRowDisplay from '@/components/budget/ItemRowDisplay.vue';
import { router, usePage } from '@inertiajs/vue3';
import { budget } from '@/routes';
import debounce from 'lodash/debounce';
import { reactive, ref, watch } from 'vue';
import {
    ItemRowEditing,
    ItemMobileCardDisplay,
    ItemMobileCardEditing,
    ItemMobileCreate,
} from '@/components/budget';

const trans = usePage().props.trans;
const props = defineProps<{
    items: BudgetItem[];
    filters: QueryOptions;
}>();

const searchValue = ref(props.filters.search);
const adding = ref(false);
const items_editing = reactive<Record<string, boolean>>({});
const sortOptions = reactive(<SortOptions>{
    type: props.filters.sortBy,
    direction: props.filters.sort,
});

const openEditItem = function (item: BudgetItem) {
    items_editing[item.id] = true;
};

const closeEditItem = function (item: BudgetItem) {
    delete items_editing[item.id];
};

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
                <SearchBar v-model:search-value="searchValue" :placeholder="trans.search_bar"></SearchBar>

                <!-- Add item: desktop only -->
                <Button class="hidden sm:inline-flex" @click="adding = true">
                    <Plus :size="15" :stroke-width="2"></Plus>
                    {{ trans.budget.button.add }}
                </Button>
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
            <ItemRowEditing
                v-if="adding"
                @close="adding = false"
            ></ItemRowEditing>
            <component
                v-for="item in items"
                :key="item.id"
                :item
                :is="
                    items_editing.hasOwnProperty(item.id)
                        ? ItemRowEditing
                        : ItemRowDisplay
                "
                @edit="openEditItem(item)"
                @close="closeEditItem(item)"
            ></component>
        </template>

        <template #mobile>
            <component
                :is="
                    items_editing.hasOwnProperty(item.id)
                        ? ItemMobileCardEditing
                        : ItemMobileCardDisplay
                "
                v-for="item in items"
                :key="item.id"
                :item
                @edit="openEditItem(item)"
                @close="closeEditItem(item)"
            ></component>
        </template>

        <template #append>
            <button
                class="fixed right-5 bottom-6 z-40 flex h-13 w-13 items-center justify-center rounded-full bg-primary text-primary-foreground shadow-lg transition-transform active:scale-95 sm:hidden"
                aria-label="Add item"
                @click="adding = true"
            >
                <Plus :size="20" :stroke-width="2" />
            </button>

            <ItemMobileCreate
                v-if="adding"
                @close="adding = false"
            ></ItemMobileCreate>
        </template>
    </Table>
</template>
