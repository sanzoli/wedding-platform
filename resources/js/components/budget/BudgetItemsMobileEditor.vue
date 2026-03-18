<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import type { BudgetItem, BudgetItemImportance } from '@/types';
import { X } from 'lucide-vue-next';
import { editingConfig, parseExpectedAmount } from './budget-items.config';
import { useInputFocus } from '@/composables/useInputFocus';
import type { BudgetItemEmits, BudgetItemProps } from './budget-items.types';

defineProps<BudgetItemProps>();

const emit = defineEmits<BudgetItemEmits>();

const { inputRef: nameInputRef } = useInputFocus();
</script>

<template>
    <Teleport to="body">
        <div class="fixed inset-0 z-50 flex flex-col bg-background sm:hidden">
            <!-- Header -->
            <header
                class="flex items-center justify-between border-b border-border px-5 py-4"
            >
                <h2 class="text-[17px] font-semibold text-foreground">
                    Add item
                </h2>
                <Button
                    @click="emit('cancel')"
                    variant="ghost"
                    size="icon"
                    class="h-8 w-8 text-muted-foreground hover:bg-muted hover:text-foreground"
                    aria-label="Close"
                >
                    <X :size="18" :stroke-width="2" />
                </Button>
            </header>

            <!-- Form body -->
            <main class="flex flex-1 flex-col gap-5 overflow-y-auto px-5 py-6">
                <div class="flex flex-col gap-1.5">
                    <Label
                        class="text-[12px] font-medium tracking-[0.05em] text-muted-foreground uppercase"
                    >
                        Item name
                    </Label>
                    <Input
                        ref="nameInputRef"
                        :modelValue="draft.name"
                        @update:modelValue="
                            emit('update:draft', {
                                ...draft,
                                name: String($event),
                            })
                        "
                        placeholder="e.g., Venue rental"
                        class="h-[44px] rounded-[12px] border-border/60 bg-card text-[15px] shadow-none"
                        @keydown.esc="emit('cancel')"
                    />
                </div>
                <div class="flex flex-col gap-1.5">
                    <Label
                        class="text-[12px] font-medium tracking-[0.05em] text-muted-foreground uppercase"
                    >
                        Importance
                    </Label>
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
                            class="h-[44px] rounded-[12px] border-border/60 bg-card text-[15px] shadow-none"
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
                </div>
                <div class="flex flex-col gap-1.5">
                    <Label
                        class="text-[12px] font-medium tracking-[0.05em] text-muted-foreground uppercase"
                    >
                        Expected amount
                    </Label>
                    <Input
                        :modelValue="draft.expected_amount ?? undefined"
                        @update:modelValue="
                            emit('update:draft', {
                                ...draft,
                                expected_amount: parseExpectedAmount($event),
                            })
                        "
                        type="number"
                        min="0"
                        placeholder="0"
                        class="h-[44px] rounded-[12px] border-border/60 bg-card text-[15px] tabular-nums shadow-none"
                        @keydown.esc="emit('cancel')"
                    />
                </div>
            </main>

            <!-- Bottom actions -->
            <footer
                class="border-t border-border px-5 py-4 pb-[calc(1rem+env(safe-area-inset-bottom))]"
            >
                <div class="flex gap-3">
                    <Button
                        @click="emit('cancel')"
                        variant="secondary"
                        class="flex h-[46px] flex-1"
                    >
                        Cancel
                    </Button>
                    <Button @click="emit('save')" class="flex h-[46px] flex-1">
                        Save
                    </Button>
                </div>
            </footer>
        </div>
    </Teleport>
</template>
