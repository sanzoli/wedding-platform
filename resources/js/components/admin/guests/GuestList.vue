<script setup lang="ts">
import AddButton from '@/components/admin/AddButton.vue';
import ConfirmDialog from '@/components/admin/ConfirmDialog.vue';
import SearchBar from '@/components/admin/SearchBar.vue';
import Table from '@/components/admin/Table.vue';
import TableHeader from '@/components/admin/TableHeader.vue';
import {
    Tooltip,
    TooltipContent,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import type { SortOptions } from '@/types';
import type {
    Guest,
    GuestGroupView,
    GuestLanguage,
} from '@/types/guests';
import { usePage } from '@inertiajs/vue3';
import { ChevronsDown, ChevronsRight } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import GuestListCompanionRow from './GuestListCompanionRow.vue';
import GuestListCreateCompanionRow from './GuestListCreateCompanionRow.vue';
import GuestListCreatePrimaryRow from './GuestListCreatePrimaryRow.vue';
import GuestListGroupRow from './GuestListGroupRow.vue';

const props = defineProps<{
    groups: GuestGroupView[];
}>();

const emit = defineEmits<{
    'delete-group': [groupId: string];
    'delete-companion': [companionId: string];
    'create-group': [
        payload: {
            name: string;
            surname: string;
            language: GuestLanguage;
            mobile: string;
        },
    ];
    'create-companion': [
        payload: {
            groupId: string;
            name: string;
            surname: string;
            language: GuestLanguage;
        },
    ];
}>();

const trans = usePage().props.trans.guests as Record<string, string>;
trans.column_name ??= 'Guest / Group';
trans.column_members ??= 'Members';
trans.column_language ??= 'Language';
trans.column_mobile ??= 'Mobile';
trans.column_attention ??= 'Attention';
trans.column_actions ??= 'Actions';
trans.expand_all ??= 'Expand all';
trans.collapse_all ??= 'Collapse all';
trans.delete_group_title ??= 'Delete this group?';
trans.delete_companion_title ??= 'Delete this companion?';
trans.delete_description ??= 'This action cannot be undone.';
trans.delete_confirm ??= 'Delete';
trans.delete_cancel ??= 'Cancel';
trans.add_group ??= 'Add guest group';
trans.search_placeholder ??= 'Search guests...';

// TODO(backend): visual-only sort; the API will sort server-side.
const sortOptions = ref<SortOptions>({ type: 'name', direction: 'asc' });

const setSort = (type: string, direction: 'asc' | 'desc' | null) => {
    sortOptions.value = {
        type: direction === null ? null : type,
        direction,
    };
};

// Per-group expansion state. Groups default to collapsed.
const expanded = ref<Record<string, boolean>>({});
const isExpanded = (id: string) => expanded.value[id] ?? false;
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

// Inline create primary group. The row is mounted at the top of the body
// when adding is true; tick saves (with name|surname validation inside the
// row), X cancels.
const adding = ref(false);

const onCreateRequest = () => {
    if (adding.value) return;
    adding.value = true;
};

const onSavePrimary = (payload: {
    name: string;
    surname: string;
    language: GuestLanguage;
    mobile: string;
}) => {
    emit('create-group', payload);
    adding.value = false;
};

const onCancelCreate = () => {
    adding.value = false;
};

// Inline create companion. Tracks which group has the editor open; opening
// it force-expands that group so the editor is visible.
const addingCompanionIn = ref<string | null>(null);

const requestAddCompanion = (groupId: string) => {
    if (addingCompanionIn.value) return;
    addingCompanionIn.value = groupId;
    expanded.value = { ...expanded.value, [groupId]: true };
};

const onSaveCompanion = (
    groupId: string,
    payload: { name: string; surname: string; language: GuestLanguage },
) => {
    emit('create-companion', { groupId, ...payload });
    addingCompanionIn.value = null;
};

const onCancelCompanion = () => {
    addingCompanionIn.value = null;
};

// TODO(backend): wire `searchValue` to a debounced server query. For now the
// input is visual only — typing does not filter the local mock.
const searchValue = ref('');
</script>

<template>
    <Table>
        <template #toolbar>
            <div class="flex items-center gap-3">
                <SearchBar
                    v-model:search-value="searchValue"
                    :placeholder="trans.search_placeholder"
                />
                <div class="ml-auto">
                    <AddButton @add="onCreateRequest">{{
                        trans.add_group
                    }}</AddButton>
                </div>
            </div>
        </template>

        <template #header>
            <TableHeader
                sort-by="name"
                :sort-options="sortOptions"
                @update:sortOptions="setSort"
            >
                <template #prefix>
                    <Tooltip v-if="hasCollapsibleGroups">
                        <TooltipTrigger as-child>
                            <button
                                type="button"
                                class="inline-flex size-5 cursor-pointer items-center justify-center rounded text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
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
            <TableHeader class="text-center">{{
                trans.column_actions
            }}</TableHeader>
            <TableHeader class="w-px pr-2.5 pl-2">
                <span class="sr-only">{{ trans.column_attention }}</span>
            </TableHeader>
        </template>

        <template #body>
            <GuestListCreatePrimaryRow
                v-if="adding"
                @save="onSavePrimary"
                @cancel="onCancelCreate"
            />
            <template v-for="group in groups" :key="group.id">
                <GuestListGroupRow
                    :group="group"
                    :expanded="isExpanded(group.id)"
                    @toggle="toggle(group.id)"
                    @delete="requestDeleteGroup(group)"
                    @add-companion="requestAddCompanion(group.id)"
                />
                <template v-if="isExpanded(group.id)">
                    <GuestListCompanionRow
                        v-for="companion in group.companions"
                        :key="companion.id"
                        :companion="companion"
                        @delete="requestDeleteCompanion(companion)"
                    />
                    <GuestListCreateCompanionRow
                        v-if="addingCompanionIn === group.id"
                        @save="
                            (payload) => onSaveCompanion(group.id, payload)
                        "
                        @cancel="onCancelCompanion"
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
