<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import type { BudgetItem, BudgetItemImportance } from '@/types';
import { Check, Pencil, Plus, Trash2, X } from 'lucide-vue-next';
import { computed, nextTick, ref } from 'vue';

interface EditableItem extends BudgetItem {
    isEditing?: boolean;
    isNew?: boolean;
}

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

const editableItems = ref<EditableItem[]>([]);
const editingItem = ref<Partial<BudgetItem> | null>(null);
const newItem = ref<Partial<BudgetItem> | null>(null);
const isAddingItem = ref(false);

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

const hasItems = computed(() => props.items && props.items.length > 0);

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

const startAddItem = async () => {
    isAddingItem.value = true;
    newItem.value = {
        name: '',
        importance: 'normal',
        expected_amount: null,
    };
    await nextTick();
};

const cancelAddItem = () => {
    isAddingItem.value = false;
    newItem.value = null;
};

const saveNewItem = () => {
    if (newItem.value && newItem.value.name && newItem.value.name.trim()) {
        emit('create', newItem.value);
        isAddingItem.value = false;
        newItem.value = null;
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
        <div class="mb-8">
            <h2
                class="text-[13px] font-medium tracking-[0.05em] text-muted-foreground uppercase"
            >
                Budget Items
            </h2>
            <p class="mt-1.5 text-[14px] text-muted-foreground/70">
                {{ items.length }} item{{ items.length === 1 ? '' : 's' }}
            </p>
        </div>

        <!-- ─── Desktop table (sm and up) ─── -->
        <div
            class="hidden overflow-hidden rounded-xl border border-border bg-card shadow-sm sm:block"
        >
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-border">
                            <th
                                class="px-6 py-[22px] text-left text-[13px] font-medium tracking-[0.05em] text-muted-foreground uppercase"
                            >
                                Item
                            </th>
                            <th
                                class="px-6 py-[22px] text-left text-[13px] font-medium tracking-[0.05em] text-muted-foreground uppercase"
                            >
                                Importance
                            </th>
                            <th
                                class="px-6 py-[22px] text-right text-[13px] font-medium tracking-[0.05em] text-muted-foreground uppercase"
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
                                    <td class="px-6 py-3">
                                        <Input
                                            v-model="editingItem.name"
                                            class="h-[40px] rounded-[10px] border-border/60 bg-card text-[15px] shadow-none"
                                            @keydown.enter="saveEdit"
                                            @keydown.esc="cancelEdit"
                                        />
                                    </td>
                                    <td class="px-6 py-3">
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
                                            v-model.number="
                                                editingItem.expected_amount
                                            "
                                            type="number"
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
                                                @click="saveEdit"
                                                class="inline-flex h-7 w-7 items-center justify-center rounded-md text-muted-foreground transition-colors hover:bg-primary/10 hover:text-primary"
                                                title="Save"
                                            >
                                                <Check
                                                    :size="13"
                                                    :stroke-width="2.5"
                                                />
                                            </button>
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
                                        </div>
                                    </td>
                                </template>

                                <template v-else>
                                    <td
                                        class="px-6 py-[21px] text-[15px] font-medium text-foreground"
                                    >
                                        {{ item.name }}
                                    </td>
                                    <td class="px-6 py-[21px]">
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
                                <div class="flex flex-col items-center gap-3">
                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-full bg-muted/40"
                                    >
                                        <Plus
                                            :size="20"
                                            :stroke-width="1.5"
                                            class="text-muted-foreground/60"
                                        />
                                    </div>
                                    <div class="space-y-1">
                                        <p
                                            class="text-sm font-medium text-foreground/80"
                                        >
                                            No budget items yet
                                        </p>
                                        <p
                                            class="text-xs text-muted-foreground"
                                        >
                                            Click the row below to add your
                                            first item
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
                            <td class="px-6 py-3">
                                <Input
                                    v-model="newItem.name"
                                    placeholder="e.g., Venue rental"
                                    class="h-[40px] rounded-[10px] border-border/60 bg-card text-[15px] shadow-none"
                                    @keydown.enter="saveNewItem"
                                    @keydown.esc="cancelAddItem"
                                    autofocus
                                />
                            </td>
                            <td class="px-6 py-3">
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
                                    v-model.number="newItem.expected_amount"
                                    type="number"
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

                        <!-- Ghost add-item row -->
                        <tr
                            v-if="!isAddingItem"
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
        <div class="flex flex-col gap-4 sm:hidden">
            <template v-if="hasItems">
                <div
                    v-for="item in items"
                    :key="item.id"
                    class="rounded-xl border border-border bg-card shadow-sm"
                >
                    <!-- Edit mode card -->
                    <template v-if="isEditing(item.id) && editingItem">
                        <div
                            class="flex flex-col gap-4 px-[21px] pt-[21px] pb-[21px]"
                        >
                            <div class="flex flex-col gap-1.5">
                                <label
                                    class="text-[12px] font-medium tracking-[0.05em] text-muted-foreground uppercase"
                                    >Item</label
                                >
                                <Input
                                    v-model="editingItem.name"
                                    placeholder="e.g., Venue rental"
                                    class="h-[44px] rounded-[14px] border-border/60 bg-card text-[15px] shadow-none"
                                    @keydown.enter="saveEdit"
                                    @keydown.esc="cancelEdit"
                                    autofocus
                                />
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label
                                    class="text-[12px] font-medium tracking-[0.05em] text-muted-foreground uppercase"
                                    >Importance</label
                                >
                                <select
                                    v-model="editingItem.importance"
                                    class="h-[44px] w-full rounded-[14px] border border-border/60 bg-card px-3 text-[15px] text-foreground shadow-none transition-colors outline-none focus:border-ring focus:ring-2 focus:ring-ring/20"
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
                                    >Expected Amount</label
                                >
                                <Input
                                    v-model.number="editingItem.expected_amount"
                                    type="number"
                                    placeholder="0"
                                    class="h-[44px] rounded-[14px] border-border/60 bg-card text-[15px] tabular-nums shadow-none"
                                    @keydown.enter="saveEdit"
                                    @keydown.esc="cancelEdit"
                                />
                            </div>
                            <div class="flex gap-3 pt-2">
                                <button
                                    @click="saveEdit"
                                    class="flex h-[42px] flex-1 items-center justify-center rounded-[14px] bg-foreground text-[15px] font-medium text-background transition-colors hover:bg-foreground/90"
                                >
                                    Save
                                </button>
                                <button
                                    @click="cancelEdit"
                                    class="flex h-[42px] flex-1 items-center justify-center rounded-[14px] bg-muted text-[15px] font-medium text-muted-foreground transition-colors hover:bg-muted/70"
                                >
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </template>

                    <!-- View mode card -->
                    <template v-else>
                        <div
                            class="flex flex-col gap-4 px-[21px] pt-[21px] pb-[1px]"
                        >
                            <div class="flex items-start justify-between">
                                <div class="flex flex-col gap-2">
                                    <p
                                        class="text-[16px] font-medium text-foreground"
                                    >
                                        {{ item.name }}
                                    </p>
                                    <Badge
                                        v-if="item.importance"
                                        :variant="
                                            importanceVariant[item.importance]
                                        "
                                        class="h-[22px] w-fit rounded-full px-[10px] py-0 text-[12px]"
                                    >
                                        {{ importanceLabel[item.importance] }}
                                    </Badge>
                                </div>
                                <div class="flex shrink-0 items-center gap-1">
                                    <button
                                        @click="startEdit(item)"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-[14px] text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                                        title="Edit"
                                    >
                                        <Pencil :size="15" :stroke-width="2" />
                                    </button>
                                    <button
                                        @click="deleteItem(item.id)"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-[14px] text-muted-foreground transition-colors hover:bg-destructive/10 hover:text-destructive"
                                        title="Delete"
                                    >
                                        <Trash2 :size="15" :stroke-width="2" />
                                    </button>
                                </div>
                            </div>
                            <p
                                class="pb-[20px] text-[20px] font-medium tracking-tight text-foreground tabular-nums"
                            >
                                {{ formatAmount(item.expected_amount) }}
                            </p>
                        </div>
                    </template>
                </div>
            </template>

            <!-- New item card (add mode) -->
            <div
                v-if="isAddingItem && newItem"
                class="rounded-xl border border-border bg-card shadow-sm"
            >
                <div class="flex flex-col gap-4 px-[21px] pt-[21px] pb-[21px]">
                    <div class="flex flex-col gap-1.5">
                        <label
                            class="text-[12px] font-medium tracking-[0.05em] text-muted-foreground uppercase"
                            >Item</label
                        >
                        <Input
                            v-model="newItem.name"
                            placeholder="e.g., Venue rental"
                            class="h-[44px] rounded-[14px] border-border/60 bg-card text-[15px] shadow-none"
                            @keydown.enter="saveNewItem"
                            @keydown.esc="cancelAddItem"
                            autofocus
                        />
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label
                            class="text-[12px] font-medium tracking-[0.05em] text-muted-foreground uppercase"
                            >Importance</label
                        >
                        <select
                            v-model="newItem.importance"
                            class="h-[44px] w-full rounded-[14px] border border-border/60 bg-card px-3 text-[15px] text-foreground shadow-none transition-colors outline-none focus:border-ring focus:ring-2 focus:ring-ring/20"
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
                            >Expected Amount</label
                        >
                        <Input
                            v-model.number="newItem.expected_amount"
                            type="number"
                            placeholder="0"
                            class="h-[44px] rounded-[14px] border-border/60 bg-card text-[15px] tabular-nums shadow-none"
                            @keydown.enter="saveNewItem"
                            @keydown.esc="cancelAddItem"
                        />
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button
                            @click="saveNewItem"
                            class="flex h-[42px] flex-1 items-center justify-center rounded-[14px] bg-foreground text-[15px] font-medium text-background transition-colors hover:bg-foreground/90"
                        >
                            Save
                        </button>
                        <button
                            @click="cancelAddItem"
                            class="flex h-[42px] flex-1 items-center justify-center rounded-[14px] bg-muted text-[15px] font-medium text-muted-foreground transition-colors hover:bg-muted/70"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>

            <!-- Add item button -->
            <button
                v-if="!isAddingItem"
                @click="startAddItem"
                class="flex h-[54px] w-full items-center justify-center gap-2.5 rounded-xl border border-border bg-card text-[14px] font-medium text-muted-foreground transition-colors hover:bg-muted/30 hover:text-foreground"
            >
                <Plus :size="14" :stroke-width="2" class="opacity-70" />
                Add item
            </button>
        </div>
    </div>
</template>
