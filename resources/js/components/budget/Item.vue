<script setup lang="ts">
import {
    destroy,
    update,
} from '@/actions/App/Http/Controllers/BudgetItemsController';
import ItemDisplay from '@/components/budget/ItemDisplay.vue';
import ItemEditor from '@/components/budget/ItemEditor.vue';
import { confirmDelete, toastError } from '@/composables/useAlert';
import type { BudgetItem } from '@/types';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    item: BudgetItem;
}>();

const editing = ref(false);

const toggle = () => {
    editing.value = !editing.value;
};

const updateItem = (input: object) => {
    router.put(update(props.item.id), input, {
        only: ['items'],
        preserveScroll: true,
        onSuccess: () => (editing.value = false),
        onError: (errors: object) => toastError(Object.values(errors)[0]),
    });
};

const deleteItem = () =>
    confirmDelete(() =>
        router.delete(destroy(props.item.id), { only: ['items'] }),
    );
</script>

<template>
    <ItemEditor
        v-if="editing"
        :item
        @save="updateItem"
        @close="toggle"
    ></ItemEditor>

    <ItemDisplay v-else :item @edit="toggle" @delete="deleteItem"></ItemDisplay>
</template>
