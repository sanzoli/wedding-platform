<script setup lang="ts">
import type { BudgetItem } from '@/types';
import { reactive } from 'vue';
import IconButton from '@/components/IconButton.vue';
import { Input } from '@/components/ui/input';
import { Check, X } from 'lucide-vue-next';
import { usePage } from '@inertiajs/vue3';
import { Select, SelectItem } from '@/components/ui/select';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';

const emit = defineEmits(['save', 'close']);
const props = defineProps<{
    item?: BudgetItem;
}>();

const page = usePage();
const importanceOptions = page.props.importanceOptions;

const input = reactive({
    id: props.item?.id,
    name: props.item?.name,
    importance: props.item?.importance,
    expected_amount: props.item?.expected_amount,
});
</script>

<template>
    <tr class="group hidden transition-colors hover:bg-muted/30 md:table-row">
        <td class="w-[300px] px-6 py-3">
            <Input
                v-model="input.name"
                :placeholder="page.props.trans.budget.placeholder.item"
                class="h-[40px] rounded-[10px] border-border/60 bg-card text-[15px] shadow-none"
                @keydown.enter="$emit('save', input)"
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
                @keydown.enter="$emit('save', input)"
                @keydown.esc="$emit('close')"
            />
        </td>
        <td class="px-6 py-3">
            <IconButton
                @click="$emit('save', input)"
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

    <!--mobile-->
    <div class="flex flex-col gap-3.5 px-4 py-4 sm:hidden">
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
                @keydown.enter="$emit('save', input)"
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
                @keydown.enter="$emit('save', input)"
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
            <Button @click="$emit('save', input)" class="flex h-[38px] flex-1">
                {{ page.props.trans.button.save }}
            </Button>
        </div>
    </div>
</template>
