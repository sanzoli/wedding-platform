<script setup lang="ts">
import ConfirmDialog from '@/components/admin/ConfirmDialog.vue';
import Table from '@/components/admin/Table.vue';
import TableHeader from '@/components/admin/TableHeader.vue';
import {
    Tooltip,
    TooltipContent,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import type { SortOptions } from '@/types';
import type { Guest, GuestGroupView } from '@/types/guest-list';
import { usePage } from '@inertiajs/vue3';
import { ChevronsDown, ChevronsRight } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import GuestListCompanionRow from './GuestListCompanionRow.vue';
import GuestListGroupRow from './GuestListGroupRow.vue';

const props = defineProps<{
    groups: GuestGroupView[];
}>();

const emit = defineEmits<{
    'delete-group': [groupId: string];
    'delete-companion': [companionId: string];
}>();

const trans = usePage().props.trans.guest_list as Record<string, string>;
trans.column_name ??= 'Guest / Group';
trans.column_members ??= 'Members';
trans.column_language ??= 'Language';
trans.column_mobile ??= 'Mobile';
trans.column_actions ??= 'Actions';
trans.expand_all ??= 'Expand all';
trans.collapse_all ??= 'Collapse all';
trans.delete_group_title ??= 'Delete this group?';
trans.delete_companion_title ??= 'Delete this companion?';
trans.delete_description ??= 'This action cannot be undone.';
trans.delete_confirm ??= 'Delete';
trans.delete_cancel ??= 'Cancel';

// TODO(backend): visual-only sort; the API will sort server-side.
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

// Delete confirmation. The dialog lives at the table level; the parent owns
// the mutation, so we emit on confirm and let it decide what to drop.
type PendingDelete =
    | { type: 'group'; groupId: string }
    | { type: 'companion'; companionId: string };

const pendingDelete = ref<PendingDelete | null>(null);

const requestDeleteGroup = (group: GuestGroupView) => {
    pendingDelete.value = { type: 'group', groupId: group.id };
};

const requestDeleteCompanion = (companion: Guest) => {
    pendingDelete.value = { type: 'companion', companionId: companion.id };
};

const cancelDelete = () => {
    pendingDelete.value = null;
};

const confirmDelete = () => {
    if (!pendingDelete.value) return;
    if (pendingDelete.value.type === 'group') {
        emit('delete-group', pendingDelete.value.groupId);
    } else {
        emit('delete-companion', pendingDelete.value.companionId);
    }
    pendingDelete.value = null;
};

const dialogTitle = computed(() => {
    if (!pendingDelete.value) return '';
    return pendingDelete.value.type === 'group'
        ? trans.delete_group_title
        : trans.delete_companion_title;
});
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
                    @delete="requestDeleteGroup(group)"
                />
                <template v-if="isExpanded(group.id)">
                    <GuestListCompanionRow
                        v-for="companion in group.companions"
                        :key="companion.id"
                        :companion="companion"
                        @delete="requestDeleteCompanion(companion)"
                    />
                </template>
            </template>
        </template>
    </Table>

    <ConfirmDialog
        :open="pendingDelete !== null"
        :title="dialogTitle"
        :description="trans.delete_description"
        :confirm-label="trans.delete_confirm"
        :cancel-label="trans.delete_cancel"
        confirm-variant="destructive"
        @update:open="(v) => !v && cancelDelete()"
        @confirm="confirmDelete"
    />
</template>
