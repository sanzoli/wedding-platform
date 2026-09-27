<script setup lang="ts">
import CategoryFilter from '@/components/admin/guests/CategoryFilter.vue';
import SearchBar from '@/components/admin/SearchBar.vue';
import { Category, QueryOptions } from '@/types';
import { computed } from 'vue';

const emit = defineEmits(['update:query-options']);

const props = defineProps<{
    queryOptions: QueryOptions;
    categories: Category[];
}>();

const updateQueryOptions = (value: Partial<QueryOptions>) =>
    emit('update:query-options', { ...props.queryOptions, ...value });

const searchValue = computed({
    get: () => props.queryOptions.search,
    set: (value) => updateQueryOptions({ search: value }),
});

const selectedCategoryIds = computed({
    get: () => props.queryOptions.categories || [],
    set: (value) => updateQueryOptions({ categories: value }),
});
</script>

<template>
    <SearchBar v-model:search-value="searchValue" />
    <CategoryFilter v-model="selectedCategoryIds" :categories="categories" />
</template>
