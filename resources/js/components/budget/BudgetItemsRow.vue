<script setup lang="ts">
import ActionButtonGroup, {
    type ActionButton,
} from '@/components/budget/ActionButtonGroup.vue';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import type { BudgetItem, BudgetItemImportance } from '@/types';
import { Check, Pencil, Trash2, X } from 'lucide-vue-next';
import { computed } from 'vue';
import type { DisplayConfig, EditingConfig } from './budget-items.config';

interface Props {
    item: BudgetItem;
    display: DisplayConfig;
    editing: EditingConfig | null;
    isDeleting?: boolean;
    isNewlyCreated?: boolean;
    isRecentlyEdited?: boolean;
}

const props = defineProps<Props>();

const emit = defineEmits<{
    edit: [item: BudgetItem];
    cancel: [];
    save: [];
    delete: [id: string];
    'update:draft': [draft: Partial<BudgetItem>];
}>();

const editActions = computed<ActionButton[]>(() => [
    {
        icon: Check,
        variant: 'ghost',
        size: 'icon',
        class: 'h-9 w-9 text-muted-foreground hover:bg-primary/10 hover:text-primary',
        title: 'Save',
        onClick: () => emit('save'),
    },
    {
        icon: X,
        variant: 'ghost',
        size: 'icon',
        class: 'h-9 w-9 text-muted-foreground hover:bg-destructive/10 hover:text-destructive',
        title: 'Cancel',
        onClick: () => emit('cancel'),
    },
]);

const viewActions = computed<ActionButton[]>(() => [
    {
        icon: Pencil,
        variant: 'ghost',
        size: 'icon',
        class: 'h-9 w-9 text-muted-foreground hover:bg-muted hover:text-foreground',
        title: 'Edit',
        onClick: () => emit('edit', props.item),
        disabled: props.isDeleting,
    },
    {
        icon: Trash2,
        variant: 'ghost',
        size: 'icon',
        class: 'h-9 w-9 text-muted-foreground hover:bg-destructive/10 hover:text-destructive',
        title: 'Delete',
        onClick: () => emit('delete', props.item.id),
        disabled: props.isDeleting,
    },
]);
</script>

<template>
    <tr
        class="hover:bg-muted/30 group transition-colors"
        :class="{
            'bg-muted/20': editing?.isEditing,
            'animate-in fade-in-0 slide-in-from-top-2 duration-300':
                isNewlyCreated,
            'animate-out fade-out-0 slide-out-to-right-4 opacity-50 duration-300':
                isDeleting,
            'admin-animate-flash-edit': isRecentlyEdited && !editing?.isEditing,
        }"
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
                    class="border-border/60 bg-card h-[40px] rounded-[10px] text-[15px] shadow-none"
                    @keydown.enter="emit('save')"
                    @keydown.esc="emit('cancel')"
                />
            </td>
            <td class="px-8 py-3">
                <Select
                    :modelValue="editing.draft.importance"
                    @update:modelValue="
                        emit('update:draft', {
                            ...editing.draft,
                            importance: $event as BudgetItemImportance,
                        })
                    "
                >
                    <SelectTrigger
                        class="border-border/60 bg-card h-[40px] rounded-[10px] text-[15px] shadow-none"
                    >
                        <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem
                            v-for="option in editing.importanceOptions"
                            :key="option.value"
                            :value="option.value"
                        >
                            {{ option.label }}
                        </SelectItem>
                    </SelectContent>
                </Select>
            </td>
            <td class="flex justify-end px-6 py-3">
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
                    class="border-border/60 bg-card h-[40px] max-w-[100px] rounded-[10px] text-right text-[15px] tabular-nums shadow-none"
                    @keydown.enter="emit('save')"
                    @keydown.esc="emit('cancel')"
                />
            </td>
            <td class="px-6 py-3">
                <ActionButtonGroup :actions="editActions" class="justify-end" />
            </td>
        </template>

        <template v-else>
            <td
                class="text-foreground w-[300px] px-6 py-[21px] text-[15px] font-medium"
            >
                {{ item.name }}
            </td>
            <td class="px-8 py-[21px]">
                <Badge
                    v-if="item.importance"
                    :class="display.importanceClass[item.importance]"
                    class="h-[22px] rounded-full px-[10px] py-0 text-[12px]"
                >
                    {{ display.importanceLabel[item.importance] }}
                </Badge>
                <span v-else class="text-muted-foreground text-xs">—</span>
            </td>
            <td
                class="text-foreground px-6 py-[21px] text-right text-[15px] tabular-nums tracking-tight"
            >
                {{ display.formatAmount(item.expected_amount) }}
            </td>
            <td class="px-6 py-[21px]">
                <ActionButtonGroup :actions="viewActions" class="justify-end" />
            </td>
        </template>
    </tr>
</template>
