<script setup lang="ts">
import type { BudgetItem } from '@/types';
import ItemEditor from '@/components/budget/ItemEditor.vue';
import ItemDisplay from '@/components/budget/ItemDisplay.vue';
import { confirmDelete } from '@/composables/useModal';
import { router } from '@inertiajs/vue3';
import {
    destroy,
    update,
} from '@/actions/App/Http/Controllers/BudgetItemsController';
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
    });

    editing.value = false;
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
