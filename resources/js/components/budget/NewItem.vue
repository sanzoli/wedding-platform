<script setup lang="ts">
import IconButton from '@/components/IconButton.vue';
import { Input } from '@/components/ui/input';
import { Select, SelectItem } from '@/components/ui/select';
import { Check, X } from 'lucide-vue-next';
import { router, usePage } from '@inertiajs/vue3';
import { reactive } from 'vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { store } from '@/actions/App/Http/Controllers/BudgetItemsController';
import { toastError } from '@/composables/useAlert';

const emit = defineEmits(['close']);

const page = usePage();
const importanceOptions = page.props.importanceOptions;

const input = reactive({
    name: '',
    importance: null,
    expected_amount: undefined,
});

const storeItem = () => {
    router.post(store(), input, {
        only: ['items'],
        preserveScroll: true,
        onSuccess: () => emit('close'),
        onError: (errors: object) => toastError(Object.values(errors)[0]),
    });
};
</script>

<template>
    <tr class="group hidden transition-colors hover:bg-muted/30 md:table-row">
        <td class="w-[300px] px-6 py-3">
            <Input
                v-model="input.name"
                :placeholder="page.props.trans.budget.placeholder.item"
                class="h-[40px] rounded-[10px] border-border/60 bg-card text-[15px] shadow-none"
                @keydown.enter="storeItem"
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
                @keydown.enter="storeItem"
                @keydown.esc="$emit('close')"
            />
        </td>
        <td class="px-6 py-3">
            <IconButton
                @click="storeItem"
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

    <Teleport to="body">
        <div class="fixed inset-0 z-50 flex flex-col bg-background sm:hidden">
            <!-- Header -->
            <header
                class="flex items-center justify-between border-b border-border px-5 py-4"
            >
                <h2 class="text-[17px] font-semibold text-foreground">
                    {{ page.props.trans.budget.button.add }}
                </h2>
                <Button
                    @click="$emit('close')"
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
                        {{ page.props.trans.budget.label.item_name }}
                    </Label>
                    <Input
                        ref="nameInputRef"
                        v-model="input.name"
                        :placeholder="page.props.trans.budget.placeholder.item"
                        class="h-[44px] rounded-[12px] border-border/60 bg-card text-[15px] shadow-none"
                    />
                </div>
                <div class="flex flex-col gap-1.5">
                    <Label
                        class="text-[12px] font-medium tracking-[0.05em] text-muted-foreground uppercase"
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
                        class="text-[12px] font-medium tracking-[0.05em] text-muted-foreground uppercase"
                    >
                        {{ page.props.trans.budget.label.expected_amount }}
                    </Label>
                    <Input
                        v-model="input.expected_amount"
                        type="number"
                        min="0"
                        placeholder="0"
                        class="h-[44px] rounded-[12px] border-border/60 bg-card text-[15px] tabular-nums shadow-none"
                        @keydown.esc="$emit('close')"
                    />
                </div>
            </main>

            <!-- Bottom actions -->
            <footer
                class="border-t border-border px-5 py-4 pb-[calc(1rem+env(safe-area-inset-bottom))]"
            >
                <div class="flex gap-3">
                    <Button
                        @click="$emit('close')"
                        variant="secondary"
                        class="flex h-[46px] flex-1"
                    >
                        {{ page.props.trans.button.cancel }}
                    </Button>
                    <Button @click="storeItem" class="flex h-[46px] flex-1">
                        {{ page.props.trans.button.save }}
                    </Button>
                </div>
            </footer>
        </div>
    </Teleport>
</template>
