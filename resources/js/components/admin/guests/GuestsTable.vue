<script setup lang="ts">
import AddButton from '@/components/admin/AddButton.vue';
import SearchBar from '@/components/admin/SearchBar.vue';
import Table from '@/components/admin/Table.vue';
import TableHeader from '@/components/admin/TableHeader.vue';
import {
    Tooltip,
    TooltipContent,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import type {
    CompanionView,
    GuestGroupView,
    SelectGroupDialogStatus,
    SelectGroupOption,
    SelectGroupSource,
} from '@/types/guests';
import { usePage } from '@inertiajs/vue3';
import { ChevronsDown, ChevronsRight } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import Companion from './Companion.vue';
import Group from './Group.vue';
import NewCompanion from './NewCompanion.vue';
import NewGroup from './NewGroup.vue';
import SelectGroupDialog from './SelectGroupDialog.vue';

const props = withDefaults(
    defineProps<{
        tableGroups: GuestGroupView[];
        allGroups: GuestGroupView[];
        dialogStatus?: SelectGroupDialogStatus;
    }>(),
    { dialogStatus: 'idle' },
);

const trans = usePage().props.trans.guests as Record<string, string>;

// --- active flow management (one at a time) -----------------------------------------------

type ActiveFlow =
    | { kind: 'idle' }
    | { kind: 'adding-group' }
    | { kind: 'adding-companion'; groupId: string }
    | { kind: 'editing-group'; groupId: string }
    | { kind: 'editing-companion'; companionId: string }
    | {
          kind: 'select-group';
          mode: 'change' | 'join';
          source: SelectGroupSource;
          excludeGroupId: string;
      };

const activeFlow = ref<ActiveFlow>({ kind: 'idle' });

const resetActiveFlow = () => {
    activeFlow.value = { kind: 'idle' };
};

const isAddingGroup = computed(() => activeFlow.value.kind === 'adding-group');
const isAddingCompanionIn = (groupId: string) =>
    activeFlow.value.kind === 'adding-companion' &&
    activeFlow.value.groupId === groupId;
const isEditingGroup = (groupId: string) =>
    activeFlow.value.kind === 'editing-group' &&
    activeFlow.value.groupId === groupId;
const isEditingCompanion = (companionId: string) =>
    activeFlow.value.kind === 'editing-companion' &&
    activeFlow.value.companionId === companionId;

const selectGroupDialogFlow = computed(() =>
    activeFlow.value.kind === 'select-group' ? activeFlow.value : null,
);

// --- expand/collapse management -----------------------------------------------

const expanded = ref<Record<string, boolean>>({});
const isExpanded = (id: string) => expanded.value[id] ?? false;
const setExpanded = (id: string, value: boolean) => {
    expanded.value = { ...expanded.value, [id]: value };
};

const toggle = (group: GuestGroupView) => {
    const wasExpanded = isExpanded(group.id);
    setExpanded(group.id, !wasExpanded);
    if (wasExpanded) cancelFlowsInGroup(group);
};

const hasCollapsibleGroups = computed(() =>
    props.tableGroups.some((g) => g.companions.length > 0),
);

const allExpanded = computed(() =>
    props.tableGroups.every(
        (g) => g.companions.length === 0 || isExpanded(g.id),
    ),
);

const toggleAll = () => {
    const next = !allExpanded.value;
    expanded.value = Object.fromEntries(
        props.tableGroups.map((g) => [g.id, next]),
    );
    if (!next) {
        const kind = activeFlow.value.kind;
        if (kind === 'adding-companion' || kind === 'editing-companion') {
            resetActiveFlow();
        }
    }
};

const cancelFlowsInGroup = (group: GuestGroupView) => {
    const flow = activeFlow.value;
    if (flow.kind === 'adding-companion' && flow.groupId === group.id) {
        resetActiveFlow();
        return;
    }
    if (
        flow.kind === 'editing-companion' &&
        group.companions.some((c) => c.id === flow.companionId)
    ) {
        resetActiveFlow();
    }
};

const hasBodyContent = computed(
    () =>
        props.tableGroups.length > 0 ||
        activeFlow.value.kind === 'adding-group'
);

const onCreateRequest = () => {
    if (isAddingGroup.value) return;
    window.scrollTo({ top: 0, behavior: 'smooth' });
    activeFlow.value = { kind: 'adding-group' };
};

const requestAddCompanion = (groupId: string) => {
    if (isAddingCompanionIn(groupId)) return;
    activeFlow.value = { kind: 'adding-companion', groupId };
    setExpanded(groupId, true);
};

const startEditGroup = (groupId: string) => {
    if (isEditingGroup(groupId)) return;
    activeFlow.value = { kind: 'editing-group', groupId };
};

const startEditCompanion = (companionId: string) => {
    if (isEditingCompanion(companionId)) return;
    activeFlow.value = { kind: 'editing-companion', companionId };
    const group = props.tableGroups.find((g) =>
        g.companions.some((c) => c.id === companionId),
    );
    if (group) setExpanded(group.id, true);
};

// --- Move / Join — select-group dialog management -----------------------------------

const hasMultipleGroups = computed(() => props.allGroups.length >= 2);

const selectGroupDialogOptions = computed<SelectGroupOption[]>(() => {
    const flow = selectGroupDialogFlow.value;
    if (!flow) return [];
    return props.allGroups
        .filter((g) => g.id !== flow.excludeGroupId)
        .map((g) => ({
            groupId: g.id,
            name: g.primary.name,
            surname: g.primary.surname,
        }))
        .sort((a, b) => {
            const aKey = a.name.trim() || a.surname.trim();
            const bKey = b.name.trim() || b.surname.trim();
            return aKey.localeCompare(bKey);
        });
});

const openSelectGroupDialog = (
    mode: 'change' | 'join',
    source: SelectGroupSource,
    excludeGroupId: string,
) => {
    activeFlow.value = { kind: 'select-group', mode, source, excludeGroupId };
};

const onChangeGroup = (companion: CompanionView, group: GuestGroupView) =>
    openSelectGroupDialog(
        'change',
        {
            guestId: companion.id,
            name: companion.name,
            surname: companion.surname,
        },
        group.id,
    );

const onJoin = (group: GuestGroupView) =>
    openSelectGroupDialog(
        'join',
        {
            guestId: group.primary.id,
            name: group.primary.name,
            surname: group.primary.surname,
        },
        group.id,
    );

const onSplit = () => resetActiveFlow();
const onLeaveGroup = () => resetActiveFlow();
const onDeleteGroup = () => resetActiveFlow();
const onDeleteCompanion = () => resetActiveFlow();

const onSelectGroupDialogConfirm = () => {};

const selectGroupDialogTitle = computed(() => {
    const flow = selectGroupDialogFlow.value;
    if (!flow) return '';
    const template =
        flow.mode === 'change'
            ? trans.change_group_dialog_title
            : trans.join_group_dialog_title;
    const sourceLabel = `${flow.source.name} ${flow.source.surname}`.trim();
    return template.replaceAll(':name', sourceLabel);
});

</script>

<template>
    <Table
        :columns="7"
        :empty-add-label="trans.add_group"
        @add-item="onCreateRequest"
    >
        <template #toolbar>
            <div class="flex items-center gap-3">
                <SearchBar :placeholder="trans.search_placeholder" />
                <div class="ml-auto">
                    <AddButton @add="onCreateRequest">{{
                        trans.add_group
                    }}</AddButton>
                </div>
            </div>
        </template>

        <template #header>
            <TableHeader class="w-px pr-2 pl-4">
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
                            <ChevronsDown v-if="allExpanded" :size="16" />
                            <ChevronsRight v-else :size="16" />
                        </button>
                    </TooltipTrigger>
                    <TooltipContent>{{
                        allExpanded ? trans.collapse_all : trans.expand_all
                    }}</TooltipContent>
                </Tooltip>
            </TableHeader>
            <TableHeader>{{ trans.column_name }}</TableHeader>
            <TableHeader class="text-center!">{{
                trans.column_members
            }}</TableHeader>
            <TableHeader class="text-center!">{{
                trans.column_language
            }}</TableHeader>
            <TableHeader class="text-center!">{{
                trans.column_mobile
            }}</TableHeader>
            <TableHeader class="text-center!">{{
                trans.column_actions
            }}</TableHeader>
            <TableHeader class="w-px pr-2.5 pl-2">
                <span class="sr-only">{{ trans.column_attention }}</span>
            </TableHeader>
        </template>

        <template v-if="hasBodyContent" #body>
            <NewGroup v-if="isAddingGroup" @cancel="resetActiveFlow" />
            <template v-for="group in props.tableGroups" :key="group.id">
                <Group
                    :group="group"
                    :expanded="isExpanded(group.id)"
                    :editing="isEditingGroup(group.id)"
                    :can-join="hasMultipleGroups"
                    @toggle="toggle(group)"
                    @delete="onDeleteGroup"
                    @add-companion="requestAddCompanion(group.id)"
                    @edit="startEditGroup(group.id)"
                    @cancel-edit="resetActiveFlow"
                    @split="onSplit"
                    @join="onJoin(group)"
                />
                <template v-if="isExpanded(group.id)">
                    <NewCompanion
                        v-if="isAddingCompanionIn(group.id)"
                        @cancel="resetActiveFlow"
                    />
                    <Companion
                        v-for="companion in group.companions"
                        :key="companion.id"
                        :companion="companion"
                        :editing="isEditingCompanion(companion.id)"
                        :can-change-group="hasMultipleGroups"
                        @delete="onDeleteCompanion"
                        @edit="startEditCompanion(companion.id)"
                        @cancel-edit="resetActiveFlow"
                        @change-group="onChangeGroup(companion, group)"
                        @leave-group="onLeaveGroup"
                    />
                </template>
            </template>
        </template>
    </Table>

    <SelectGroupDialog
        :open="selectGroupDialogFlow !== null"
        :status="dialogStatus"
        :source="selectGroupDialogFlow?.source ?? null"
        :groups="selectGroupDialogOptions"
        @update:open="(v: boolean) => !v && resetActiveFlow()"
        @confirm="onSelectGroupDialogConfirm"
    >
        <template #title>{{ selectGroupDialogTitle }}</template>
    </SelectGroupDialog>
</template>
