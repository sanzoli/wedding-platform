<script setup lang="ts">
import BudgetItemsEmptyState from '@/components/budget/BudgetItemsEmptyState.vue';
import BudgetItemsMobileCard from '@/components/budget/BudgetItemsMobileCard.vue';
import BudgetItemsMobileEditor from '@/components/budget/BudgetItemsMobileEditor.vue';
import BudgetItemsNewRow from '@/components/budget/BudgetItemsNewRow.vue';
import BudgetItemsRow from '@/components/budget/BudgetItemsRow.vue';
import BudgetSortableHeader from '@/components/budget/BudgetSortableHeader.vue';
import { Card } from '@/components/ui/card';
import FloatingActionButton from '@/components/ui/floating-action-button/FloatingActionButton.vue';
import type { BudgetItem } from '@/types';
import { Plus } from 'lucide-vue-next';
import { computed, nextTick, onUnmounted, ref, watch } from 'vue';
import {
    createEmptyItemDraft,
    displayConfig,
    editingConfig,
} from './budget-items.config';
import BudgetItemsToolbar from './BudgetItemsToolbar.vue';
import { useBudgetTableSorting } from './composables/useBudgetTableSorting';

interface Props {
    items: BudgetItem[];
    deletingItems?: Set<string>;
}

const props = defineProps<Props>();

const emit = defineEmits<{
    create: [item: Partial<BudgetItem>];
    update: [id: string, item: Partial<BudgetItem>];
    delete: [id: string];
}>();

// ─── Animation states ─────────────────────────────────────────────────────────
const newlyCreatedItems = ref<Set<string>>(new Set());
const recentlyEditedItems = ref<Set<string>>(new Set());

// Track newly created items for entrance animation
watch(
    () => props.items,
    (newItems, oldItems) => {
        if (!oldItems) return;

        const oldIds = new Set(oldItems.map((item) => item.id));
        const newIds = newItems.filter((item) => !oldIds.has(item.id));

        newIds.forEach((item) => {
            newlyCreatedItems.value.add(item.id);
            setTimeout(() => {
                newlyCreatedItems.value.delete(item.id);
            }, 500);
        });
    },
    { deep: false },
);

// ─── Search ───────────────────────────────────────────────────────────────────
const searchQuery = ref('');

const filteredItems = computed(() => {
    const q = searchQuery.value.trim().toLowerCase();
    if (!q) return props.items;
    return props.items.filter((item) => item.name.toLowerCase().includes(q));
});

// ─── Sorting ──────────────────────────────────────────────────────────────────
const { sortColumn, sortDirection, toggleSort, sortedItems } =
    useBudgetTableSorting(filteredItems);

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
        const itemId = editingItem.value.id;
        // TODO(api): Replace local update flow with backend PATCH for this item
        emit('update', itemId, editingItem.value);
        editingItem.value = null;

        // Trigger flash animation
        recentlyEditedItems.value.add(itemId);
        setTimeout(() => {
            recentlyEditedItems.value.delete(itemId);
        }, 600);
    }
};

const deleteItem = (id: string) => {
    // TODO(api): Replace local delete flow with backend DELETE for this item
    emit('delete', id);
};

const startAddItem = () => {
    isAddingItem.value = true;
    newItem.value = createEmptyItemDraft();

    nextTick(() => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth',
        });
    });
};

const cancelAddItem = () => {
    resetNewItemState();
};

const saveNewItem = () => {
    if (newItem.value && newItem.value.name && newItem.value.name.trim()) {
        // TODO(api): Replace local create flow with backend POST for budget items
        emit('create', newItem.value);
        resetNewItemState();
    } else {
        cancelAddItem();
    }
};

const hasItems = computed(() => props.items.length > 0);
const hasFilteredItems = computed(() => filteredItems.value.length > 0);
const hasSearchQuery = computed(() => searchQuery.value.trim().length > 0);

const rowEditingFor = (item: BudgetItem) =>
    editingItem.value?.id === item.id
        ? { isEditing: true, draft: editingItem.value!, ...editingConfig }
        : null;

const isDeleting = (item: BudgetItem) => {
    return props.deletingItems?.has(item.id) || false;
};

const isNewlyCreated = (item: BudgetItem) => {
    return newlyCreatedItems.value.has(item.id);
};

const isRecentlyEdited = (item: BudgetItem) => {
    return recentlyEditedItems.value.has(item.id);
};

// ─── Mobile FAB / full-screen editor state ───────────────────────────────────
const isMobileEditorOpen = ref(false);
const mobileEditorDraft = ref<Partial<BudgetItem>>(createEmptyItemDraft());

// Detect screen size
const isMobile = ref(false);
const updateIsMobile = () => {
    const wasMobile = isMobile.value;
    isMobile.value = window.innerWidth < 640; // sm breakpoint

    // Desktop to Mobile: Transfer desktop add state to mobile
    if (!wasMobile && isMobile.value && isAddingItem.value && newItem.value) {
        mobileEditorDraft.value = { ...newItem.value };
        isMobileEditorOpen.value = true;
        resetNewItemState();
    }

    // Mobile to Desktop: Transfer mobile add state to desktop
    if (wasMobile && !isMobile.value && isMobileEditorOpen.value) {
        newItem.value = { ...mobileEditorDraft.value };
        isAddingItem.value = true;
        isMobileEditorOpen.value = false;
    }
};

// Initialize and add listener
updateIsMobile();
window.addEventListener('resize', updateIsMobile);

const openMobileEditor = () => {
    mobileEditorDraft.value = createEmptyItemDraft();
    isMobileEditorOpen.value = true;
};

const closeMobileEditor = () => {
    isMobileEditorOpen.value = false;
};

const saveMobileEditor = () => {
    if (mobileEditorDraft.value?.name?.trim()) {
        // TODO(api): Replace local create flow with backend POST for budget items
        emit('create', mobileEditorDraft.value);
    }
    closeMobileEditor();
};

// Cleanup on unmount
onUnmounted(() => {
    window.removeEventListener('resize', updateIsMobile);
});
</script>

<template>
    <section class="premium-table-container">
        <!-- ─── Toolbar ─── -->
        <BudgetItemsToolbar
            :search-query="searchQuery"
            :is-adding-item="isAddingItem"
            @update:search-query="searchQuery = $event"
            @start-add-item="startAddItem"
        />

        <!-- ─── Desktop table (sm and up) ─── -->
        <Card class="hidden gap-0 overflow-hidden py-0 sm:block">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-border">
                            <th
                                class="type-table-header w-[300px] px-6 py-[22px] text-left text-muted-foreground"
                            >
                                <BudgetSortableHeader
                                    :label="`Item (${items.length})`"
                                    :active="sortColumn === 'item'"
                                    :direction="
                                        sortColumn === 'item'
                                            ? sortDirection
                                            : null
                                    "
                                    @toggle="toggleSort('item')"
                                />
                            </th>
                            <th
                                class="type-table-header px-8 py-[22px] text-left text-muted-foreground"
                            >
                                <BudgetSortableHeader
                                    label="Importance"
                                    :active="sortColumn === 'importance'"
                                    :direction="
                                        sortColumn === 'importance'
                                            ? sortDirection
                                            : null
                                    "
                                    @toggle="toggleSort('importance')"
                                />
                            </th>
                            <th
                                class="type-table-header px-6 py-[22px] text-right text-muted-foreground"
                            >
                                <BudgetSortableHeader
                                    label="Expected Amount"
                                    :active="sortColumn === 'expectedAmount'"
                                    :direction="
                                        sortColumn === 'expectedAmount'
                                            ? sortDirection
                                            : null
                                    "
                                    @toggle="toggleSort('expectedAmount')"
                                />
                            </th>
                            <th class="w-[120px] px-6 py-[22px]"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border/50">
                        <BudgetItemsNewRow
                            v-if="isAddingItem && newItem"
                            :draft="newItem"
                            @update:draft="newItem = $event"
                            @save="saveNewItem"
                            @cancel="cancelAddItem"
                        />

                        <template v-if="hasItems && hasFilteredItems">
                            <BudgetItemsRow
                                v-for="item in sortedItems"
                                :key="item.id"
                                :item="item"
                                :display="displayConfig"
                                :editing="rowEditingFor(item)"
                                :is-deleting="isDeleting(item)"
                                :is-newly-created="isNewlyCreated(item)"
                                :is-recently-edited="isRecentlyEdited(item)"
                                @edit="startEdit"
                                @cancel="cancelEdit"
                                @save="saveEdit"
                                @delete="deleteItem"
                                @update:draft="editingItem = $event"
                            />
                        </template>

                        <!-- Search no results -->
                        <tr
                            v-else-if="
                                hasItems && !hasFilteredItems && hasSearchQuery
                            "
                        >
                            <td colspan="4" class="px-6 py-12 text-center">
                                <BudgetItemsEmptyState
                                    variant="search-desktop"
                                />
                            </td>
                        </tr>

                        <!-- Empty state normal -->
                        <tr v-else-if="!isAddingItem && !hasItems">
                            <td colspan="4" class="px-6 py-12 text-center">
                                <BudgetItemsEmptyState
                                    variant="desktop"
                                    @addItem="startAddItem"
                                />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </Card>

        <!-- ─── Mobile card list (below sm) ─── -->
        <div class="flex flex-col gap-2.5 sm:hidden">
            <template v-if="hasItems && hasFilteredItems">
                <BudgetItemsMobileCard
                    v-for="item in filteredItems"
                    :key="item.id"
                    :item="item"
                    :display="displayConfig"
                    :editing="rowEditingFor(item)"
                    :is-deleting="isDeleting(item)"
                    :is-newly-created="isNewlyCreated(item)"
                    :is-recently-edited="isRecentlyEdited(item)"
                    @edit="startEdit"
                    @cancel="cancelEdit"
                    @save="saveEdit"
                    @delete="deleteItem"
                    @update:draft="editingItem = $event"
                />
            </template>

            <template
                v-else-if="hasItems && !hasFilteredItems && hasSearchQuery"
            >
                <BudgetItemsEmptyState variant="search-mobile" />
            </template>

            <BudgetItemsEmptyState 
                v-else 
                variant="mobile" 
                @addItem="openMobileEditor" 
            />
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
    </section>
</template>
