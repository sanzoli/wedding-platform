<script setup lang="ts">
import ActionButtonGroup, {
    type ActionButton,
} from '@/components/budget/ActionButtonGroup.vue';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import type { BudgetItem, BudgetItemImportance } from '@/types';
import { Check, X } from 'lucide-vue-next';
import { computed } from 'vue';
import { editingConfig } from './budget-items.config';
import { useInputFocus } from '@/composables/useInputFocus';
import type { BudgetItemEmits, BudgetItemProps } from './budget-items.types';

defineProps<BudgetItemProps>();

const { inputRef: nameInputRef } = useInputFocus();

const emit = defineEmits<BudgetItemEmits>();

const actions = computed<ActionButton[]>(() => [
    {
        icon: X,
        variant: 'ghost',
        size: 'icon',
        class: 'h-9 w-9 text-muted-foreground hover:bg-destructive/10 hover:text-destructive',
        title: 'Cancel',
        onClick: () => emit('cancel'),
    },
    {
        icon: Check,
        variant: 'ghost',
        size: 'icon',
        class: 'h-9 w-9 text-muted-foreground hover:bg-primary/10 hover:text-primary',
        title: 'Save',
        onClick: () => emit('save'),
    },
]);
</script>

<template>
    <tr
        class="group animate-in bg-muted/20 transition-colors duration-300 fade-in-0 slide-in-from-top-2"
    >
        <td class="w-[300px] px-6 py-3">
            <Input
                ref="nameInputRef"
                :modelValue="draft.name"
                @update:modelValue="
                    emit('update:draft', { ...draft, name: String($event) })
                "
                placeholder="e.g., Venue rental"
                class="h-[40px] rounded-[10px] border-border/60 bg-card text-[15px] shadow-none"
                @keydown.enter="emit('save')"
                @keydown.esc="emit('cancel')"
            />
        </td>
        <td class="px-8 py-3">
            <Select
                :modelValue="draft.importance"
                @update:modelValue="
                    emit('update:draft', {
                        ...draft,
                        importance: $event as BudgetItemImportance,
                    })
                "
            >
                <SelectTrigger
                    class="h-[40px] rounded-[10px] border-border/60 bg-card text-[15px] shadow-none"
                >
                    <SelectValue />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem
                        v-for="option in editingConfig.importanceOptions"
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
                :modelValue="draft.expected_amount ?? undefined"
                @update:modelValue="
                    emit('update:draft', {
                        ...draft,
                        expected_amount:
                            editingConfig.parseExpectedAmount($event),
                    })
                "
                type="number"
                min="0"
                placeholder="0"
                class="h-[40px] max-w-[120px] rounded-[10px] border-border/60 bg-card text-right text-[15px] tabular-nums shadow-none"
                @keydown.enter="emit('save')"
                @keydown.esc="emit('cancel')"
            />
        </td>
        <td class="px-6 py-3">
            <ActionButtonGroup :actions="actions" class="justify-end" />
        </td>
    </tr>
</template>
