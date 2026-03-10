<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import type { BudgetItem, BudgetItemImportance } from '@/types';
import { Check, Pencil, Plus, Trash2, X } from 'lucide-vue-next';
import { computed, ref } from 'vue';

function createEmptyItemDraft(): Partial<BudgetItem> {
    return {
        name: '',
        importance: 'normal',
        expected_amount: null,
    };
}

function parseExpectedAmount(value: unknown): number | null {
    const num = value !== '' ? Number(value) : null;
    return num !== null && num < 0 ? 0 : num;
}

// ─── Mobile FAB / full-screen editor state ───────────────────────────────────
const isMobileEditorOpen = ref(false);
const mobileEditorItem = ref<Partial<BudgetItem>>(createEmptyItemDraft());

const openMobileEditor = () => {
    mobileEditorItem.value = createEmptyItemDraft();
    isMobileEditorOpen.value = true;
};

const closeMobileEditor = () => {
    isMobileEditorOpen.value = false;
};

const saveMobileEditor = () => {
    if (mobileEditorItem.value?.name?.trim()) {
        emit('create', mobileEditorItem.value);
    }
    closeMobileEditor();
};

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

const editingItem = ref<Partial<BudgetItem> | null>(null);
const newItem = ref<Partial<BudgetItem> | null>(null);
const isAddingItem = ref(false);

function resetNewItemState() {
    isAddingItem.value = false;
    newItem.value = null;
}

const importanceOptions: { value: BudgetItemImportance; label: string }[] = [
    { value: 'innegociable', label: 'Innegociable' },
    { value: 'high', label: 'High' },
    { value: 'normal', label: 'Normal' },
    { value: 'low', label: 'Low' },
];

const importanceVariant: Record<
    BudgetItemImportance,
    'default' | 'secondary' | 'destructive' | 'outline'
> = {
    innegociable: 'default',
    high: 'secondary',
    normal: 'secondary',
    low: 'outline',
};

const importanceLabel: Record<BudgetItemImportance, string> = {
    innegociable: 'Innegociable',
    high: 'High',
    normal: 'Normal',
    low: 'Low',
};

const hasItems = computed(() => props.items.length > 0);

const formatAmount = (amount: number | null | undefined): string => {
    if (amount === null || amount === undefined) return '—';
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
};

const startEdit = (item: BudgetItem) => {
    editingItem.value = { ...item };
};

const cancelEdit = () => {
    editingItem.value = null;
};

const saveEdit = () => {
    if (editingItem.value && editingItem.value.id) {
        emit('update', editingItem.value.id, editingItem.value);
        editingItem.value = null;
    }
};

const deleteItem = (id: string) => {
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
        emit('create', newItem.value);
        resetNewItemState();
    } else {
        cancelAddItem();
    }
};

const isEditing = (itemId: string) => {
    return editingItem.value?.id === itemId;
};
</script>

<template>
    <div class="premium-table-container">
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
                        <template v-if="hasItems">
                            <tr
                                v-for="item in items"
                                :key="item.id"
                                class="group transition-colors hover:bg-muted/30"
                                :class="{
                                    'bg-muted/20': isEditing(item.id),
                                }"
                            >
                                <template
                                    v-if="isEditing(item.id) && editingItem"
                                >
                                    <td class="w-[300px] px-6 py-3">
                                        <Input
                                            v-model="editingItem.name"
                                            placeholder="e.g., Venue rental"
                                            class="h-[40px] rounded-[10px] border-border/60 bg-card text-[15px] shadow-none"
                                            @keydown.enter="saveEdit"
                                            @keydown.esc="cancelEdit"
                                        />
                                    </td>
                                    <td class="px-8 py-3">
                                        <select
                                            v-model="editingItem.importance"
                                            class="h-[40px] w-full rounded-[10px] border border-border/60 bg-card px-3 text-[15px] text-foreground shadow-none transition-colors outline-none focus:border-ring focus:ring-2 focus:ring-ring/20"
                                        >
                                            <option
                                                v-for="option in importanceOptions"
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
                                                editingItem.expected_amount ??
                                                undefined
                                            "
                                            @update:modelValue="
                                                editingItem.expected_amount =
                                                    parseExpectedAmount($event)
                                            "
                                            type="number"
                                            min="0"
                                            class="h-[40px] rounded-[10px] border-border/60 bg-card text-right text-[15px] tabular-nums shadow-none"
                                            @keydown.enter="saveEdit"
                                            @keydown.esc="cancelEdit"
                                        />
                                    </td>
                                    <td class="px-6 py-3">
                                        <div
                                            class="flex items-center justify-end gap-1.5"
                                        >
                                            <button
                                                @click="cancelEdit"
                                                class="inline-flex h-7 w-7 items-center justify-center rounded-md text-muted-foreground transition-colors hover:bg-destructive/10 hover:text-destructive"
                                                title="Cancel"
                                            >
                                                <X
                                                    :size="13"
                                                    :stroke-width="2.5"
                                                />
                                            </button>
                                            <button
                                                @click="saveEdit"
                                                class="inline-flex h-7 w-7 items-center justify-center rounded-md text-muted-foreground transition-colors hover:bg-primary/10 hover:text-primary"
                                                title="Save"
                                            >
                                                <Check
                                                    :size="13"
                                                    :stroke-width="2.5"
                                                />
                                            </button>
                                        </div>
                                    </td>
                                </template>

                                <template v-else>
                                    <td
                                        class="w-[300px] px-6 py-[21px] text-[15px] font-medium text-foreground"
                                    >
                                        {{ item.name }}
                                    </td>
                                    <td class="px-8 py-[21px]">
                                        <Badge
                                            v-if="item.importance"
                                            :variant="
                                                importanceVariant[
                                                    item.importance
                                                ]
                                            "
                                            class="h-[22px] rounded-full px-[10px] py-0 text-[12px]"
                                        >
                                            {{
                                                importanceLabel[item.importance]
                                            }}
                                        </Badge>
                                        <span
                                            v-else
                                            class="text-xs text-muted-foreground"
                                            >—</span
                                        >
                                    </td>
                                    <td
                                        class="px-6 py-[21px] text-right text-[15px] tracking-tight text-foreground tabular-nums"
                                    >
                                        {{ formatAmount(item.expected_amount) }}
                                    </td>
                                    <td class="px-6 py-[21px]">
                                        <div
                                            class="flex items-center justify-end gap-1 opacity-0 transition-opacity group-hover:opacity-100"
                                        >
                                            <button
                                                @click="startEdit(item)"
                                                class="inline-flex h-7 w-7 items-center justify-center rounded-md text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                                                title="Edit"
                                            >
                                                <Pencil
                                                    :size="13"
                                                    :stroke-width="2"
                                                />
                                            </button>
                                            <button
                                                @click="deleteItem(item.id)"
                                                class="inline-flex h-7 w-7 items-center justify-center rounded-md text-muted-foreground transition-colors hover:bg-destructive/10 hover:text-destructive"
                                                title="Delete"
                                            >
                                                <Trash2
                                                    :size="13"
                                                    :stroke-width="2"
                                                />
                                            </button>
                                        </div>
                                    </td>
                                </template>
                            </tr>
                        </template>

                        <tr v-else-if="!isAddingItem">
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <button
                                        @click="startAddItem"
                                        class="inline-flex items-center gap-2 rounded-[10px] bg-primary px-5 py-2.5 text-[14px] font-medium text-primary-foreground transition-colors hover:bg-primary/90"
                                    >
                                        <Plus :size="16" :stroke-width="2" />
                                        Add your first item
                                    </button>
                                    <div class="space-y-2">
                                        <p
                                            class="text-sm font-medium text-foreground/80"
                                        >
                                            No budget items yet
                                        </p>
                                        <p
                                            class="text-xs text-muted-foreground"
                                        >
                                            Start building your dream wedding
                                        </p>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <!-- New item editable row -->
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
                                        v-for="option in importanceOptions"
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
                                    class="h-[40px] rounded-[10px] border-border/60 bg-card text-right text-[15px] tabular-nums shadow-none"
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

                        <!-- Ghost add-item row - solo cuando hay items -->
                        <tr
                            v-if="!isAddingItem && hasItems"
                            @click="startAddItem"
                            class="group cursor-pointer transition-colors hover:bg-muted/30"
                        >
                            <td
                                colspan="4"
                                class="border-t border-border/50 px-6 py-[18px]"
                            >
                                <div
                                    class="flex items-center gap-2.5 text-muted-foreground transition-colors group-hover:text-foreground"
                                >
                                    <Plus
                                        :size="14"
                                        :stroke-width="2"
                                        class="opacity-70 transition-opacity group-hover:opacity-100"
                                    />
                                    <span class="text-[14px] font-medium"
                                        >Add item</span
                                    >
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ─── Mobile card list (below sm) ─── -->
        <div class="flex flex-col gap-2.5 sm:hidden">
            <template v-if="hasItems">
                <div
                    v-for="item in items"
                    :key="item.id"
                    class="rounded-xl border border-border bg-card shadow-sm"
                >
                    <!-- Edit mode card -->
                    <template v-if="isEditing(item.id) && editingItem">
                        <div class="flex flex-col gap-3.5 px-4 py-4">
                            <div class="flex flex-col gap-1.5">
                                <label
                                    class="text-[11px] font-medium tracking-[0.05em] text-muted-foreground uppercase"
                                    >Item</label
                                >
                                <Input
                                    v-model="editingItem.name"
                                    placeholder="e.g., Venue rental"
                                    class="h-[40px] rounded-[10px] border-border/60 bg-card text-[15px] shadow-none"
                                    @keydown.enter="saveEdit"
                                    @keydown.esc="cancelEdit"
                                    autofocus
                                />
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label
                                    class="text-[11px] font-medium tracking-[0.05em] text-muted-foreground uppercase"
                                    >Importance</label
                                >
                                <select
                                    v-model="editingItem.importance"
                                    class="h-[40px] w-full rounded-[10px] border border-border/60 bg-card px-3 text-[15px] text-foreground shadow-none transition-colors outline-none focus:border-ring focus:ring-2 focus:ring-ring/20"
                                >
                                    <option
                                        v-for="option in importanceOptions"
                                        :key="option.value"
                                        :value="option.value"
                                    >
                                        {{ option.label }}
                                    </option>
                                </select>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label
                                    class="text-[11px] font-medium tracking-[0.05em] text-muted-foreground uppercase"
                                    >Expected Amount</label
                                >
                                <Input
                                    :modelValue="
                                        editingItem.expected_amount ?? undefined
                                    "
                                    @update:modelValue="
                                        editingItem.expected_amount =
                                            parseExpectedAmount($event)
                                    "
                                    type="number"
                                    min="0"
                                    placeholder="0"
                                    class="h-[40px] rounded-[10px] border-border/60 bg-card text-[15px] tabular-nums shadow-none"
                                    @keydown.enter="saveEdit"
                                    @keydown.esc="cancelEdit"
                                />
                            </div>
                            <div class="flex gap-2.5 pt-1">
                                <button
                                    @click="cancelEdit"
                                    class="flex h-[38px] flex-1 items-center justify-center rounded-[10px] bg-muted text-[14px] font-medium text-muted-foreground transition-colors hover:bg-muted/70"
                                >
                                    Cancel
                                </button>
                                <button
                                    @click="saveEdit"
                                    class="flex h-[38px] flex-1 items-center justify-center rounded-[10px] bg-primary text-[14px] font-medium text-primary-foreground transition-colors hover:bg-primary/90"
                                >
                                    Save
                                </button>
                            </div>
                        </div>
                    </template>

                    <!-- View mode card — compact 2-row layout -->
                    <template v-else>
                        <div class="px-4 py-3">
                            <!-- Row 1: name + actions -->
                            <div
                                class="flex items-center justify-between gap-2"
                            >
                                <p
                                    class="truncate text-[15px] font-medium text-foreground"
                                >
                                    {{ item.name }}
                                </p>
                                <div class="flex shrink-0 items-center gap-0.5">
                                    <button
                                        @click="startEdit(item)"
                                        class="inline-flex h-7 w-7 items-center justify-center rounded-md text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                                        title="Edit"
                                    >
                                        <Pencil :size="13" :stroke-width="2" />
                                    </button>
                                    <button
                                        @click="deleteItem(item.id)"
                                        class="inline-flex h-7 w-7 items-center justify-center rounded-md text-muted-foreground transition-colors hover:bg-destructive/10 hover:text-destructive"
                                        title="Delete"
                                    >
                                        <Trash2 :size="13" :stroke-width="2" />
                                    </button>
                                </div>
                            </div>
                            <!-- Row 2: badge + amount -->
                            <div
                                class="mt-2 flex items-center justify-between gap-2"
                            >
                                <Badge
                                    v-if="item.importance"
                                    :variant="
                                        importanceVariant[item.importance]
                                    "
                                    class="h-[20px] rounded-full px-[9px] py-0 text-[11px]"
                                >
                                    {{ importanceLabel[item.importance] }}
                                </Badge>
                                <span
                                    v-else
                                    class="text-xs text-muted-foreground"
                                    >—</span
                                >
                                <p
                                    class="text-[16px] font-semibold tracking-tight text-foreground tabular-nums"
                                >
                                    {{ formatAmount(item.expected_amount) }}
                                </p>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

            <!-- Empty state for mobile -->
            <div
                v-else
                class="rounded-xl border border-border bg-card px-4 py-10 text-center shadow-sm"
            >
                <div class="flex flex-col items-center gap-3">
                    <p class="text-sm font-medium text-foreground/80">
                        No budget items yet
                    </p>
                    <p class="text-xs text-muted-foreground">
                        Tap + to add your first item and start building your
                        dream wedding
                    </p>
                </div>
            </div>
        </div>

        <!-- ─── Mobile FAB (below sm) ─── -->
        <button
            @click="openMobileEditor"
            class="fixed right-5 bottom-6 z-40 flex h-13 w-13 items-center justify-center rounded-full bg-primary text-primary-foreground shadow-lg transition-transform active:scale-95 sm:hidden"
            aria-label="Add item"
        >
            <Plus :size="20" :stroke-width="2" />
        </button>

        <!-- ─── Mobile full-screen editor overlay ─── -->
        <Teleport to="body">
            <div
                v-if="isMobileEditorOpen"
                class="fixed inset-0 z-50 flex flex-col bg-background sm:hidden"
            >
                <!-- Header -->
                <div
                    class="flex items-center justify-between border-b border-border px-5 py-4"
                >
                    <h2 class="text-[17px] font-semibold text-foreground">
                        Add item
                    </h2>
                    <button
                        @click="closeMobileEditor"
                        class="inline-flex h-8 w-8 items-center justify-center rounded-md text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                        aria-label="Close"
                    >
                        <X :size="18" :stroke-width="2" />
                    </button>
                </div>

                <!-- Form body -->
                <div
                    class="flex flex-1 flex-col gap-5 overflow-y-auto px-5 py-6"
                >
                    <div class="flex flex-col gap-1.5">
                        <label
                            class="text-[12px] font-medium tracking-[0.05em] text-muted-foreground uppercase"
                        >
                            Item name
                        </label>
                        <Input
                            v-model="mobileEditorItem.name"
                            placeholder="e.g., Venue rental"
                            class="h-[44px] rounded-[12px] border-border/60 bg-card text-[15px] shadow-none"
                            @keydown.esc="closeMobileEditor"
                            autofocus
                        />
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label
                            class="text-[12px] font-medium tracking-[0.05em] text-muted-foreground uppercase"
                        >
                            Importance
                        </label>
                        <select
                            v-model="mobileEditorItem.importance"
                            class="h-[44px] w-full rounded-[12px] border border-border/60 bg-card px-3 text-[15px] text-foreground shadow-none transition-colors outline-none focus:border-ring focus:ring-2 focus:ring-ring/20"
                        >
                            <option
                                v-for="option in importanceOptions"
                                :key="option.value"
                                :value="option.value"
                            >
                                {{ option.label }}
                            </option>
                        </select>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label
                            class="text-[12px] font-medium tracking-[0.05em] text-muted-foreground uppercase"
                        >
                            Expected amount
                        </label>
                        <Input
                            :modelValue="
                                mobileEditorItem.expected_amount ?? undefined
                            "
                            @update:modelValue="
                                mobileEditorItem.expected_amount =
                                    parseExpectedAmount($event)
                            "
                            type="number"
                            min="0"
                            placeholder="0"
                            class="h-[44px] rounded-[12px] border-border/60 bg-card text-[15px] tabular-nums shadow-none"
                            @keydown.esc="closeMobileEditor"
                        />
                    </div>
                </div>

                <!-- Bottom actions -->
                <div
                    class="border-t border-border px-5 py-4 pb-[calc(1rem+env(safe-area-inset-bottom))]"
                >
                    <div class="flex gap-3">
                        <button
                            @click="closeMobileEditor"
                            class="flex h-[46px] flex-1 items-center justify-center rounded-[12px] bg-muted text-[15px] font-medium text-muted-foreground transition-colors hover:bg-muted/70"
                        >
                            Cancel
                        </button>
                        <button
                            @click="saveMobileEditor"
                            class="flex h-[46px] flex-1 items-center justify-center rounded-[12px] bg-primary text-[15px] font-medium text-primary-foreground transition-colors hover:bg-primary/90"
                        >
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </Teleport>
    </div>
</template>
