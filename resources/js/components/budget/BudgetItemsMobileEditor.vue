<script setup lang="ts">
import { Input } from '@/components/ui/input';
import type { BudgetItem, BudgetItemImportance } from '@/types';
import { editingConfig, parseExpectedAmount } from './budget-items.config';
import { X } from 'lucide-vue-next';

interface Props {
    draft: Partial<BudgetItem>;
}

defineProps<Props>();

const emit = defineEmits<{
    'update:draft': [draft: Partial<BudgetItem>];
    save: [];
    cancel: [];
}>();
</script>

<template>
    <Teleport to="body">
        <div class="fixed inset-0 z-50 flex flex-col bg-background sm:hidden">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-border px-5 py-4">
                <h2 class="text-[17px] font-semibold text-foreground">Add item</h2>
                <button
                    @click="emit('cancel')"
                    class="inline-flex h-8 w-8 items-center justify-center rounded-md text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                    aria-label="Close"
                >
                    <X :size="18" :stroke-width="2" />
                </button>
            </div>

            <!-- Form body -->
            <div class="flex flex-1 flex-col gap-5 overflow-y-auto px-5 py-6">
                <div class="flex flex-col gap-1.5">
                    <label class="text-[12px] font-medium tracking-[0.05em] text-muted-foreground uppercase">
                        Item name
                    </label>
                    <Input
                        :modelValue="draft.name"
                        @update:modelValue="emit('update:draft', { ...draft, name: String($event) })"
                        placeholder="e.g., Venue rental"
                        class="h-[44px] rounded-[12px] border-border/60 bg-card text-[15px] shadow-none"
                        @keydown.esc="emit('cancel')"
                        autofocus
                    />
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-[12px] font-medium tracking-[0.05em] text-muted-foreground uppercase">
                        Importance
                    </label>
                    <select
                        :value="draft.importance"
                        @change="emit('update:draft', { ...draft, importance: ($event.target as HTMLSelectElement).value as BudgetItemImportance })"
                        class="h-[44px] w-full rounded-[12px] border border-border/60 bg-card px-3 text-[15px] text-foreground shadow-none transition-colors outline-none focus:border-ring focus:ring-2 focus:ring-ring/20"
                    >
                        <option
                            v-for="option in editingConfig.importanceOptions"
                            :key="option.value"
                            :value="option.value"
                        >
                            {{ option.label }}
                        </option>
                    </select>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-[12px] font-medium tracking-[0.05em] text-muted-foreground uppercase">
                        Expected amount
                    </label>
                    <Input
                        :modelValue="draft.expected_amount ?? undefined"
                        @update:modelValue="emit('update:draft', { ...draft, expected_amount: parseExpectedAmount($event) })"
                        type="number"
                        min="0"
                        placeholder="0"
                        class="h-[44px] rounded-[12px] border-border/60 bg-card text-[15px] tabular-nums shadow-none"
                        @keydown.esc="emit('cancel')"
                    />
                </div>
            </div>

            <!-- Bottom actions -->
            <div class="border-t border-border px-5 py-4 pb-[calc(1rem+env(safe-area-inset-bottom))]">
                <div class="flex gap-3">
                    <button
                        @click="emit('cancel')"
                        class="flex h-[46px] flex-1 items-center justify-center rounded-[12px] bg-muted text-[15px] font-medium text-muted-foreground transition-colors hover:bg-muted/70"
                    >
                        Cancel
                    </button>
                    <button
                        @click="emit('save')"
                        class="flex h-[46px] flex-1 items-center justify-center rounded-[12px] bg-primary text-[15px] font-medium text-primary-foreground transition-colors hover:bg-primary/90"
                    >
                        Save
                    </button>
                </div>
            </div>
        </div>
    </Teleport>
</template>
