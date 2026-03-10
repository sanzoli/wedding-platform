<script setup lang="ts">
import BudgetItemsEmptyState from '@/components/budget/BudgetItemsEmptyState.vue';
import BudgetItemsMobileCard from '@/components/budget/BudgetItemsMobileCard.vue';
import BudgetItemsMobileEditor from '@/components/budget/BudgetItemsMobileEditor.vue';
import BudgetItemsRow from '@/components/budget/BudgetItemsRow.vue';
import FloatingActionButton from '@/components/ui/floating-action-button/FloatingActionButton.vue';
import { Input } from '@/components/ui/input';
import type { BudgetItem } from '@/types';
import { Check, Plus, X } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import {
    createEmptyItemDraft,
    displayConfig,
    editingConfig,
    parseExpectedAmount,
} from './budget-items.config';
import BudgetItemsToolbar from './BudgetItemsToolbar.vue';

interface Props {
    items: BudgetItem[];
    budgetId?: string;
}

const props = defineProps<Props>();

const emit = defineEmits<{
    create: [item: Partial<BudgetItem>];
    update: [id: string, item: Partial<BudgetItem>];
    delete: [id: string];
}>();

// ─── Search ───────────────────────────────────────────────────────────────────
const searchQuery = ref('');

const filteredItems = computed(() => {
    const q = searchQuery.value.trim().toLowerCase();
    if (!q) return props.items;
    return props.items.filter((item) => item.name.toLowerCase().includes(q));
});

// ─── Inline row editing state ────────────────────────────────────────────────
const editingItem = ref<Partial<BudgetItem> | null>(null);
const newItem = ref<Partial<BudgetItem> | null>(null);
const isAddingItem = ref(false);

function resetNewItemState() {
    isAddingItem.value = false;
    newItem.value = null;
}

const startEdit = (item: BudgetItem) => {
    editingItem.value = { ...item };
};

const cancelEdit = () => {
    editingItem.value = null;
};

const saveEdit = () => {
    if (editingItem.value && editingItem.value.id) {
        // TODO(api): replace local update flow with backend PATCH for this item
        emit('update', editingItem.value.id, editingItem.value);
        editingItem.value = null;
    }
};

const deleteItem = (id: string) => {
    // TODO(api): replace local delete flow with backend DELETE for this item
    emit('delete', id);
};

const startAddItem = () => {
    isAddingItem.value = true;
    newItem.value = createEmptyItemDraft();
};

const cancelAddItem = () => {
    resetNewItemState();
};

const saveNewItem = () => {
    if (newItem.value && newItem.value.name && newItem.value.name.trim()) {
        // TODO(api): replace local create flow with backend POST for budget items
        emit('create', newItem.value);
        resetNewItemState();
    } else {
        cancelAddItem();
    }
};

const hasItems = computed(() => props.items.length > 0);

const rowEditingFor = (item: BudgetItem) =>
    editingItem.value?.id === item.id
        ? { isEditing: true, draft: editingItem.value!, ...editingConfig }
        : null;

// ─── Mobile FAB / full-screen editor state ───────────────────────────────────
const isMobileEditorOpen = ref(false);
const mobileEditorDraft = ref<Partial<BudgetItem>>(createEmptyItemDraft());

const openMobileEditor = () => {
    mobileEditorDraft.value = createEmptyItemDraft();
    isMobileEditorOpen.value = true;
};

const closeMobileEditor = () => {
    isMobileEditorOpen.value = false;
};

const saveMobileEditor = () => {
    if (mobileEditorDraft.value?.name?.trim()) {
        // TODO(api): replace local create flow with backend POST for budget items
        emit('create', mobileEditorDraft.value);
    }
    closeMobileEditor();
};
</script>

<template>
    <div class="premium-table-container">
        <!-- ─── Toolbar ─── -->
        <BudgetItemsToolbar
            :search-query="searchQuery"
            :is-adding-item="isAddingItem"
            @update:search-query="searchQuery = $event"
            @start-add-item="startAddItem"
            @cancel-add-item="cancelAddItem"
        />

        <!-- ─── Desktop table (sm and up) ─── -->
        <div
            class="hidden overflow-hidden rounded-xl border border-border bg-card shadow-sm sm:block"
        >
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-border">
                            <th
                                class="type-table-header w-[300px] px-6 py-[22px] text-left text-muted-foreground"
                            >
                                Item ({{ items.length }})
                            </th>
                            <th
                                class="type-table-header px-8 py-[22px] text-left text-muted-foreground"
                            >
                                Importance
                            </th>
                            <th
                                class="type-table-header px-6 py-[22px] text-right text-muted-foreground"
                            >
                                Expected Amount
                            </th>
                            <th class="w-[120px] px-6 py-[22px]"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border/50">
                        <!-- New item editable row — always at top -->
                        <tr
                            v-if="isAddingItem && newItem"
                            class="group bg-muted/20 transition-colors"
                        >
                            <td class="w-[300px] px-6 py-3">
                                <Input
                                    v-model="newItem.name"
                                    placeholder="e.g., Venue rental"
                                    class="h-[40px] rounded-[10px] border-border/60 bg-card text-[15px] shadow-none"
                                    @keydown.enter="saveNewItem"
                                    @keydown.esc="cancelAddItem"
                                    autofocus
                                />
                            </td>
                            <td class="px-8 py-3">
                                <select
                                    v-model="newItem.importance"
                                    class="h-[40px] w-full rounded-[10px] border border-border/60 bg-card px-3 text-[15px] text-foreground shadow-none transition-colors outline-none focus:border-ring focus:ring-2 focus:ring-ring/20"
                                >
                                    <option
                                        v-for="option in editingConfig.importanceOptions"
                                        :key="option.value"
                                        :value="option.value"
                                    >
                                        {{ option.label }}
                                    </option>
                                </select>
                            </td>
                            <td class="px-6 py-3">
                                <Input
                                    :modelValue="
                                        newItem.expected_amount ?? undefined
                                    "
                                    @update:modelValue="
                                        newItem.expected_amount =
                                            parseExpectedAmount($event)
                                    "
                                    type="number"
                                    min="0"
                                    placeholder="0"
                                    class="h-[40px] max-w-[70px] rounded-[10px] border-border/60 bg-card text-right text-[15px] tabular-nums shadow-none"
                                    @keydown.enter="saveNewItem"
                                    @keydown.esc="cancelAddItem"
                                />
                            </td>
                            <td class="px-6 py-3">
                                <div
                                    class="flex items-center justify-end gap-1.5"
                                >
                                    <button
                                        @click="saveNewItem"
                                        class="inline-flex h-7 w-7 items-center justify-center rounded-md text-muted-foreground transition-colors hover:bg-primary/10 hover:text-primary"
                                        title="Save"
                                    >
                                        <Check :size="13" :stroke-width="2.5" />
                                    </button>
                                    <button
                                        @click="cancelAddItem"
                                        class="inline-flex h-7 w-7 items-center justify-center rounded-md text-muted-foreground transition-colors hover:bg-destructive/10 hover:text-destructive"
                                        title="Cancel"
                                    >
                                        <X :size="13" :stroke-width="2.5" />
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <template v-if="hasItems">
                            <BudgetItemsRow
                                v-for="item in filteredItems"
                                :key="item.id"
                                :item="item"
                                :display="displayConfig"
                                :editing="rowEditingFor(item)"
                                @edit="startEdit"
                                @cancel="cancelEdit"
                                @save="saveEdit"
                                @delete="deleteItem"
                                @update:draft="editingItem = $event"
                            />
                        </template>

                        <tr v-else-if="!isAddingItem">
                            <td colspan="4" class="px-6 py-12 text-center">
                                <BudgetItemsEmptyState
                                    variant="desktop"
                                    @add-item="startAddItem"
                                />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ─── Mobile card list (below sm) ─── -->
        <div class="flex flex-col gap-2.5 sm:hidden">
            <template v-if="hasItems">
                <BudgetItemsMobileCard
                    v-for="item in filteredItems"
                    :key="item.id"
                    :item="item"
                    :display="displayConfig"
                    :editing="rowEditingFor(item)"
                    @edit="startEdit"
                    @cancel="cancelEdit"
                    @save="saveEdit"
                    @delete="deleteItem"
                    @update:draft="editingItem = $event"
                />
            </template>

            <BudgetItemsEmptyState v-else variant="mobile" />
        </div>

        <!-- ─── Mobile FAB (below sm) ─── -->
        <FloatingActionButton
            @click="openMobileEditor"
            aria-label="Add item"
            class="sm:hidden"
        >
            <Plus :size="20" :stroke-width="2" />
        </FloatingActionButton>

        <!-- ─── Mobile full-screen editor overlay ─── -->
        <BudgetItemsMobileEditor
            v-if="isMobileEditorOpen"
            :draft="mobileEditorDraft"
            @update:draft="mobileEditorDraft = $event"
            @save="saveMobileEditor"
            @cancel="closeMobileEditor"
        />
    </div>
</template>
