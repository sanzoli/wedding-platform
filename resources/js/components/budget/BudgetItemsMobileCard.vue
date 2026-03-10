<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import type { BudgetItem, BudgetItemImportance } from '@/types';
import { Pencil, Trash2 } from 'lucide-vue-next';
import type { DisplayConfig, EditingConfig } from './budget-items.config';

interface Props {
    item: BudgetItem;
    display: DisplayConfig;
    editing: EditingConfig | null;
}

const props = defineProps<Props>();

const emit = defineEmits<{
    edit: [item: BudgetItem];
    cancel: [];
    save: [];
    delete: [id: string];
    'update:draft': [draft: Partial<BudgetItem>];
}>();
</script>

<template>
    <div class="rounded-xl border border-border bg-card shadow-sm">
        <!-- Edit mode card -->
        <template v-if="editing?.isEditing && editing.draft">
            <div class="flex flex-col gap-3.5 px-4 py-4">
                <div class="flex flex-col gap-1.5">
                    <label
                        class="text-[11px] font-medium tracking-[0.05em] text-muted-foreground uppercase"
                        >Item</label
                    >
                    <Input
                        :modelValue="editing.draft.name"
                        @update:modelValue="
                            emit('update:draft', {
                                ...editing.draft,
                                name: String($event),
                            })
                        "
                        placeholder="e.g., Venue rental"
                        class="h-[40px] rounded-[10px] border-border/60 bg-card text-[15px] shadow-none"
                        @keydown.enter="emit('save')"
                        @keydown.esc="emit('cancel')"
                        autofocus
                    />
                </div>
                <div class="flex flex-col gap-1.5">
                    <label
                        class="text-[11px] font-medium tracking-[0.05em] text-muted-foreground uppercase"
                        >Importance</label
                    >
                    <select
                        :value="editing.draft.importance"
                        @change="
                            emit('update:draft', {
                                ...editing.draft,
                                importance: ($event.target as HTMLSelectElement)
                                    .value as BudgetItemImportance,
                            })
                        "
                        class="h-[40px] w-full rounded-[10px] border border-border/60 bg-card px-3 text-[15px] text-foreground shadow-none transition-colors outline-none focus:border-ring focus:ring-2 focus:ring-ring/20"
                    >
                        <option
                            v-for="option in editing.importanceOptions"
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
                        :modelValue="editing.draft.expected_amount ?? undefined"
                        @update:modelValue="
                            emit('update:draft', {
                                ...editing.draft,
                                expected_amount:
                                    editing.parseExpectedAmount($event),
                            })
                        "
                        type="number"
                        min="0"
                        placeholder="0"
                        class="h-[40px] rounded-[10px] border-border/60 bg-card text-[15px] tabular-nums shadow-none"
                        @keydown.enter="emit('save')"
                        @keydown.esc="emit('cancel')"
                    />
                </div>
                <div class="flex gap-2.5 pt-1">
                    <button
                        @click="emit('cancel')"
                        class="flex h-[38px] flex-1 items-center justify-center rounded-[10px] bg-muted text-[14px] font-medium text-muted-foreground transition-colors hover:bg-muted/70"
                    >
                        Cancel
                    </button>
                    <button
                        @click="emit('save')"
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
                <div class="flex items-center justify-between gap-2">
                    <p class="truncate text-[15px] font-medium text-foreground">
                        {{ item.name }}
                    </p>
                    <div class="flex shrink-0 items-center gap-0.5">
                        <button
                            @click="emit('edit', item)"
                            class="inline-flex h-7 w-7 items-center justify-center rounded-md text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                            title="Edit"
                        >
                            <Pencil :size="13" :stroke-width="2" />
                        </button>
                        <button
                            @click="emit('delete', item.id)"
                            class="inline-flex h-7 w-7 items-center justify-center rounded-md text-muted-foreground transition-colors hover:bg-destructive/10 hover:text-destructive"
                            title="Delete"
                        >
                            <Trash2 :size="13" :stroke-width="2" />
                        </button>
                    </div>
                </div>
                <!-- Row 2: badge + amount -->
                <div class="mt-2 flex items-center justify-between gap-2">
                    <Badge
                        v-if="item.importance"
                        :class="display.importanceClass[item.importance]"
                        class="h-[20px] rounded-full px-[9px] py-0 text-[11px]"
                    >
                        {{ display.importanceLabel[item.importance] }}
                    </Badge>
                    <span v-else class="text-xs text-muted-foreground">—</span>
                    <p
                        class="text-[16px] font-semibold tracking-tight text-foreground tabular-nums"
                    >
                        {{ display.formatAmount(item.expected_amount) }}
                    </p>
                </div>
            </div>
        </template>
    </div>
</template>
