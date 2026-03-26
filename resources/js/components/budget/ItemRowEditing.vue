<script setup lang="ts">
import type { BudgetItem } from '@/types';
import { reactive } from 'vue';
import IconButton from '@/components/IconButton.vue';
import { Input } from '@/components/ui/input';
import { Check, X } from 'lucide-vue-next';
import { router, usePage } from '@inertiajs/vue3';
import {
    store,
    update,
} from '@/actions/App/Http/Controllers/BudgetItemsController';
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
        router.put(update(props.item.id), input, { only: ['items'] });
        emit('close');

        return;
    }

    router.post(store(), input, { only: ['items'] });
    emit('close');
};
</script>

<template>
    <tr class="group transition-colors hover:bg-muted/30">
        <td class="w-[300px] px-6 py-3">
            <Input
                v-model="input.name"
                :placeholder="page.props.trans.budget.placeholder.item"
                class="h-[40px] rounded-[10px] border-border/60 bg-card text-[15px] shadow-none"
                @keydown.enter="submitItem"
                @keydown.esc="$emit('close')"
            />
        </td>
        <td class="px-6 py-3">
            <Select v-model="input.importance">
                <SelectItem
                    v-for="(label, value) in importanceOptions"
                    :key="value"
                    :value
                >
                    {{ label }}
                </SelectItem>
            </Select>
        </td>
        <td class="flex justify-end px-6 py-3">
            <Input
                v-model="input.expected_amount"
                type="number"
                min="0"
                placeholder="0"
                class="h-[40px] max-w-[100px] min-w-32 rounded-[10px] border-border/60 bg-card text-right text-[15px] tabular-nums shadow-none"
                @keydown.enter="submitItem"
                @keydown.esc="$emit('close')"
            />
        </td>
        <td class="px-6 py-3">
            <IconButton
                @click="submitItem"
                class="hover:bg-muted hover:text-foreground"
            >
                <Check></Check>
            </IconButton>
            <IconButton
                @click="$emit('close')"
                class="hover:bg-destructive/10 hover:text-destructive"
            >
                <X></X>
            </IconButton>
        </td>
    </tr>
</template>
