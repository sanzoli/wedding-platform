<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import type { BudgetItem, BudgetItemImportance } from '@/types';
import { Check, Pencil, Trash2, X } from 'lucide-vue-next';
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
    <tr
        class="group transition-colors hover:bg-muted/30"
        :class="{ 'bg-muted/20': editing?.isEditing }"
    >
        <template v-if="editing?.isEditing && editing.draft">
            <td class="w-[300px] px-6 py-3">
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
                />
            </td>
            <td class="px-8 py-3">
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
            </td>
            <td class="px-6 py-3">
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
                    class="h-[40px] rounded-[10px] border-border/60 bg-card text-right text-[15px] tabular-nums shadow-none"
                    @keydown.enter="emit('save')"
                    @keydown.esc="emit('cancel')"
                />
            </td>
            <td class="px-6 py-3">
                <div class="flex items-center justify-end gap-1.5">
                    <button
                        @click="emit('cancel')"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-md text-muted-foreground transition-colors hover:bg-destructive/10 hover:text-destructive"
                        title="Cancel"
                    >
                        <X :size="15" :stroke-width="2.5" />
                    </button>
                    <button
                        @click="emit('save')"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-md text-muted-foreground transition-colors hover:bg-primary/10 hover:text-primary"
                        title="Save"
                    >
                        <Check :size="15" :stroke-width="2.5" />
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
                    :variant="display.importanceVariant[item.importance]"
                    :class="`badge-${item.importance}-importance`"
                    class="h-[22px] rounded-full px-[10px] py-0 text-[12px]"
                >
                    {{ display.importanceLabel[item.importance] }}
                </Badge>
                <span v-else class="text-xs text-muted-foreground">—</span>
            </td>
            <td
                class="px-6 py-[21px] text-right text-[15px] tracking-tight text-foreground tabular-nums"
            >
                {{ display.formatAmount(item.expected_amount) }}
            </td>
            <td class="px-6 py-[21px]">
                <div class="flex items-center justify-end gap-1">
                    <button
                        @click="emit('edit', item)"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-md text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                        title="Edit"
                    >
                        <Pencil :size="15" :stroke-width="2" />
                    </button>
                    <button
                        @click="emit('delete', item.id)"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-md text-muted-foreground transition-colors hover:bg-destructive/10 hover:text-destructive"
                        title="Delete"
                    >
                        <Trash2 :size="15" :stroke-width="2" />
                    </button>
                </div>
            </td>
        </template>
    </tr>
</template>
