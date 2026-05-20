<script setup lang="ts">
import Table from '@/components/admin/Table.vue';
import TableHeader from '@/components/admin/TableHeader.vue';
import type { SortOptions } from '@/types';
import type { GuestGroupView } from '@/types/guest-list';
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import GuestListCompanionRow from './GuestListCompanionRow.vue';
import GuestListGroupRow from './GuestListGroupRow.vue';

defineProps<{
    groups: GuestGroupView[];
}>();

const trans = usePage().props.trans.guest_list as Record<string, string>;
trans.column_name ??= 'Guest / Group';
trans.column_members ??= 'Members';
trans.column_language ??= 'Language';
trans.column_mobile ??= 'Mobile';
trans.column_actions ??= 'Actions';

// Visual-only sort state. Data sorting moves server-side with the backend.
const sortOptions = ref<SortOptions>({ type: 'name', direction: 'asc' });

const setSort = (type: string, direction: 'asc' | 'desc' | null) => {
    sortOptions.value = {
        type: direction === null ? null : type,
        direction,
    };
};
</script>

<template>
    <Table>
        <template #toolbar><!-- Filled by commits 6 and 8. --></template>

        <template #header>
            <TableHeader
                sort-by="name"
                :sort-options="sortOptions"
                @update:sortOptions="setSort"
                >{{ trans.column_name }}</TableHeader
            >
            <TableHeader
                sort-by="members"
                :sort-options="sortOptions"
                @update:sortOptions="setSort"
                >{{ trans.column_members }}</TableHeader
            >
            <TableHeader
                sort-by="language"
                :sort-options="sortOptions"
                @update:sortOptions="setSort"
                >{{ trans.column_language }}</TableHeader
            >
            <TableHeader>{{ trans.column_mobile }}</TableHeader>
            <TableHeader>{{ trans.column_actions }}</TableHeader>
        </template>

        <template #body>
            <template v-for="group in groups" :key="group.id">
                <GuestListGroupRow :group="group" />
                <GuestListCompanionRow
                    v-for="companion in group.companions"
                    :key="companion.id"
                    :companion="companion"
                />
            </template>
        </template>
    </Table>
</template>
