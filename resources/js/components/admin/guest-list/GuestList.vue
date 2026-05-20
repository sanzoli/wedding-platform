<script setup lang="ts">
import Table from '@/components/admin/Table.vue';
import TableHeader from '@/components/admin/TableHeader.vue';
import {
    Tooltip,
    TooltipContent,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import type { SortOptions } from '@/types';
import type { GuestGroupView } from '@/types/guest-list';
import { usePage } from '@inertiajs/vue3';
import { ChevronsDown, ChevronsRight } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import GuestListCompanionRow from './GuestListCompanionRow.vue';
import GuestListGroupRow from './GuestListGroupRow.vue';

const props = defineProps<{
    groups: GuestGroupView[];
}>();

const trans = usePage().props.trans.guest_list as Record<string, string>;
trans.column_name ??= 'Guest / Group';
trans.column_members ??= 'Members';
trans.column_language ??= 'Language';
trans.column_mobile ??= 'Mobile';
trans.column_actions ??= 'Actions';
trans.expand_all ??= 'Expand all';
trans.collapse_all ??= 'Collapse all';

// Visual-only sort state. Data sorting moves server-side with the backend.
const sortOptions = ref<SortOptions>({ type: 'name', direction: 'asc' });

const setSort = (type: string, direction: 'asc' | 'desc' | null) => {
    sortOptions.value = {
        type: direction === null ? null : type,
        direction,
    };
};

// Per-group expansion state. Groups default to expanded.
const expanded = ref<Record<string, boolean>>({});
const isExpanded = (id: string) => expanded.value[id] ?? true;
const toggle = (id: string) => {
    expanded.value = { ...expanded.value, [id]: !isExpanded(id) };
};

// Master expand/collapse. The control only renders if at least one group has
// companions to fold — otherwise there is nothing to expand.
const hasCollapsibleGroups = computed(() =>
    props.groups.some((g) => g.companions.length > 0),
);

const allExpanded = computed(() =>
    props.groups.every(
        (g) => g.companions.length === 0 || isExpanded(g.id),
    ),
);

const toggleAll = () => {
    const next = !allExpanded.value;
    expanded.value = Object.fromEntries(
        props.groups.map((g) => [g.id, next]),
    );
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
            >
                <template #prefix>
                    <Tooltip v-if="hasCollapsibleGroups">
                        <TooltipTrigger as-child>
                            <!--
                                -ml-2 aligns the master chevron with the
                                per-row chevrons (px-4 cells vs px-6 header).
                            -->
                            <button
                                type="button"
                                class="-ml-2 inline-flex size-5 cursor-pointer items-center justify-center rounded text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                                :aria-expanded="allExpanded"
                                :aria-label="
                                    allExpanded
                                        ? trans.collapse_all
                                        : trans.expand_all
                                "
                                @click="toggleAll"
                            >
                                <ChevronsDown
                                    v-if="allExpanded"
                                    :size="16"
                                />
                                <ChevronsRight v-else :size="16" />
                            </button>
                        </TooltipTrigger>
                        <TooltipContent>{{
                            allExpanded
                                ? trans.collapse_all
                                : trans.expand_all
                        }}</TooltipContent>
                    </Tooltip>
                </template>
                {{ trans.column_name }}
            </TableHeader>
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
                <GuestListGroupRow
                    :group="group"
                    :expanded="isExpanded(group.id)"
                    @toggle="toggle(group.id)"
                />
                <template v-if="isExpanded(group.id)">
                    <GuestListCompanionRow
                        v-for="companion in group.companions"
                        :key="companion.id"
                        :companion="companion"
                    />
                </template>
            </template>
        </template>
    </Table>
</template>
