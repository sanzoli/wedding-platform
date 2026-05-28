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
import type { CompanionView, GuestGroupView } from '@/types/guests';
import { usePage } from '@inertiajs/vue3';
import { ChevronsDown, ChevronsRight } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import Companion from './Companion.vue';
import Group from './Group.vue';
import NewCompanion from './NewCompanion.vue';
import NewGroup from './NewGroup.vue';

const props = defineProps<{
    groups: GuestGroupView[];
}>();

const emit = defineEmits<{
    'delete-group': [groupId: string];
    'delete-companion': [companionId: string];
}>();

const trans = usePage().props.trans.guests as Record<string, string>;

const expanded = ref<Record<string, boolean>>({});
const isExpanded = (id: string) => expanded.value[id] ?? false;
const toggle = (id: string) => {
    const wasExpanded = isExpanded(id);
    expanded.value = { ...expanded.value, [id]: !wasExpanded };
    if (wasExpanded) cancelFlowsInGroup(id);
};

const hasCollapsibleGroups = computed(() =>
    props.groups.some((g) => g.companions.length > 0),
);

const allExpanded = computed(() =>
    props.groups.every((g) => g.companions.length === 0 || isExpanded(g.id)),
);

const toggleAll = () => {
    const next = !allExpanded.value;
    expanded.value = Object.fromEntries(props.groups.map((g) => [g.id, next]));
    if (!next) {
        addingCompanionIn.value = null;
        editingCompanionId.value = null;
    }
};

// In-flight flows — only one open at a time.
const adding = ref(false);
const addingCompanionIn = ref<string | null>(null);
const editingGroupId = ref<string | null>(null);
const editingCompanionId = ref<string | null>(null);

const hasBodyContent = computed(
    () =>
        props.groups.length > 0 ||
        adding.value ||
        addingCompanionIn.value !== null,
);

const closePendingFlows = () => {
    adding.value = false;
    addingCompanionIn.value = null;
    editingGroupId.value = null;
    editingCompanionId.value = null;
};

const cancelFlowsInGroup = (groupId: string) => {
    if (addingCompanionIn.value === groupId) {
        addingCompanionIn.value = null;
    }
    if (editingCompanionId.value !== null) {
        const group = props.groups.find((g) => g.id === groupId);
        const insideGroup = group?.companions.some(
            (c) => c.id === editingCompanionId.value,
        );
        if (insideGroup) editingCompanionId.value = null;
    }
};

const onCreateRequest = () => {
    if (adding.value) return;
    closePendingFlows();
    window.scrollTo({ top: 0, behavior: 'smooth' });
    adding.value = true;
};

const onCancelCreate = () => {
    adding.value = false;
};

const requestAddCompanion = (groupId: string) => {
    if (addingCompanionIn.value === groupId) return;
    closePendingFlows();
    addingCompanionIn.value = groupId;
    expanded.value = { ...expanded.value, [groupId]: true };
};

const onCancelCompanion = () => {
    addingCompanionIn.value = null;
};

const startEditGroup = (groupId: string) => {
    if (editingGroupId.value === groupId) return;
    closePendingFlows();
    editingGroupId.value = groupId;
};

const startEditCompanion = (companionId: string) => {
    if (editingCompanionId.value === companionId) return;
    closePendingFlows();
    editingCompanionId.value = companionId;
    const group = props.groups.find((g) =>
        g.companions.some((c) => c.id === companionId),
    );
    if (group) {
        expanded.value = { ...expanded.value, [group.id]: true };
    }
};

type PendingDelete =
    | { type: 'group'; groupId: string }
    | { type: 'companion'; companionId: string };

const pendingDelete = ref<PendingDelete | null>(null);

const requestDeleteGroup = (group: GuestGroupView) => {
    closePendingFlows();
    pendingDelete.value = { type: 'group', groupId: group.id };
};

const requestDeleteCompanion = (companion: CompanionView) => {
    closePendingFlows();
    pendingDelete.value = { type: 'companion', companionId: companion.id };
};

const cancelDelete = () => {
    pendingDelete.value = null;
};

const confirmDelete = () => {
    const pending = pendingDelete.value;
    if (!pending) return;
    pendingDelete.value = null;
    if (pending.type === 'group') {
        emit('delete-group', pending.groupId);
    } else {
        emit('delete-companion', pending.companionId);
    }
};

const findCompanion = (companionId: string): CompanionView | null => {
    for (const group of props.groups) {
        const found = group.companions.find((c) => c.id === companionId);
        if (found) return found;
    }
    return null;
};

const dialogTitle = computed(() => {
    const pending = pendingDelete.value;
    if (!pending) return '';

    if (pending.type === 'group') {
        const group = props.groups.find((g) => g.id === pending.groupId);
        if (!group) return trans.delete_group_title;
        const name = `${group.primary.name} ${group.primary.surname}`.trim();
        if (!name) return trans.delete_group_title;
        const template =
            group.companions.length > 0
                ? trans.delete_group_title_named
                : trans.delete_guest_named;
        return template.replace(':name', name);
    }

    const companion = findCompanion(pending.companionId);
    if (!companion) return trans.delete_companion_title_pending;
    if (companion.isPending) return trans.delete_companion_title_pending;
    const name = `${companion.name} ${companion.surname}`.trim();
    return name
        ? trans.delete_guest_named.replace(':name', name)
        : trans.delete_companion_title_pending;
});

const dialogDescription = computed(() => {
    const pending = pendingDelete.value;
    if (!pending) return trans.delete_description;
    if (pending.type !== 'group') return trans.delete_description;

    const group = props.groups.find((g) => g.id === pending.groupId);
    const count = group?.companions.length ?? 0;
    if (count === 0) return trans.delete_description;
    const template =
        count === 1
            ? trans.delete_group_desc_one
            : trans.delete_group_desc_other;
    return template.replace(':count', String(count));
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
            <NewGroup v-if="adding" @cancel="onCancelCreate" />
            <template v-for="group in props.groups" :key="group.id">
                <Group
                    :group="group"
                    :expanded="isExpanded(group.id)"
                    :editing="editingGroupId === group.id"
                    @toggle="toggle(group.id)"
                    @delete="requestDeleteGroup(group)"
                    @add-companion="requestAddCompanion(group.id)"
                    @edit="startEditGroup(group.id)"
                    @cancel-edit="editingGroupId = null"
                />
                <template v-if="isExpanded(group.id)">
                    <Companion
                        v-for="companion in group.companions"
                        :key="companion.id"
                        :companion="companion"
                        :editing="editingCompanionId === companion.id"
                        @delete="requestDeleteCompanion(companion)"
                        @edit="startEditCompanion(companion.id)"
                        @cancel-edit="editingCompanionId = null"
                    />
                    <NewCompanion
                        v-if="addingCompanionIn === group.id"
                        @cancel="onCancelCompanion"
                    />
                </template>
            </template>
        </template>
    </Table>

    <ConfirmDialog
        :open="pendingDelete !== null"
        :title="dialogTitle"
        :description="dialogDescription"
        :confirm-label="trans.delete_confirm"
        :cancel-label="trans.delete_cancel"
        confirm-variant="destructive"
        @update:open="(v) => !v && cancelDelete()"
        @confirm="confirmDelete"
    />
</template>
