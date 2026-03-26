<script setup lang="ts">
import type { BudgetItem } from '@/types';
import { reactive } from 'vue';
import { Input } from '@/components/ui/input';
import { router, usePage } from '@inertiajs/vue3';
import {
    store,
    update,
} from '@/actions/App/Http/Controllers/BudgetItemsController';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Select, SelectItem } from '@/components/ui/select';

const emit = defineEmits(['close']);
const props = defineProps<{
    item?: BudgetItem;
}>();

const page = usePage();
const importanceOptions = page.props.importanceOptions;

const input = reactive({
    name: props.item?.name,
    importance: props.item?.importance,
    expected_amount: props.item?.expected_amount ?? undefined,
});

const submitItem = function () {
    if (props.item?.id) {
        router.put(update(props.item.id), input, {
            only: ['items'],
            preserveScroll: true,
        });
        emit('close');

        return;
    }

    router.post(store(), input, {
        only: ['items'],
        preserveScroll: true,
    });
    emit('close');
};
</script>

<template>
    <div class="flex flex-col gap-3.5 px-4 py-4">
        <div class="flex flex-col gap-1.5">
            <Label
                class="text-[11px] font-medium tracking-[0.05em] text-muted-foreground uppercase"
            >
                {{ page.props.trans.budget.label.items }}
            </Label>
            <Input
                v-model="input.name"
                :placeholder="page.props.trans.budget.placeholder.item"
                class="h-[40px] rounded-[10px] border-border/60 bg-card text-[15px] shadow-none"
                @keydown.enter="submitItem"
                @keydown.esc="$emit('close')"
            />
        </div>
        <div class="flex flex-col gap-1.5">
            <Label
                class="text-[11px] font-medium tracking-[0.05em] text-muted-foreground uppercase"
            >
                {{ page.props.trans.budget.label.importance }}
            </Label>
            <Select v-model="input.importance">
                <SelectItem
                    v-for="(label, value) in importanceOptions"
                    :key="value"
                    :value
                >
                    {{ label }}
                </SelectItem>
            </Select>
        </div>
        <div class="flex flex-col gap-1.5">
            <Label
                class="text-[11px] font-medium tracking-[0.05em] text-muted-foreground uppercase"
            >
                {{ page.props.trans.budget.label.expected_amount }}
            </Label>
            <Input
                v-model="input.expected_amount"
                type="number"
                min="0"
                placeholder="0"
                class="h-[40px] rounded-[10px] border-border/60 bg-card text-[15px] tabular-nums shadow-none"
                @keydown.enter="submitItem"
                @keydown.esc="$emit('close')"
            />
        </div>
        <div class="flex gap-2.5 pt-1">
            <Button
                @click="emit('close')"
                variant="secondary"
                class="flex h-[38px] flex-1"
            >
                {{ page.props.trans.button.cancel }}
            </Button>
            <Button @click="submitItem" class="flex h-[38px] flex-1">
                {{ page.props.trans.button.save }}
            </Button>
        </div>
    </div>
</template>
