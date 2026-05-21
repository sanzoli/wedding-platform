<script setup lang="ts">
// Wrapper for the entity/display split. Hosts the edit toggle between
// CompanionDisplay (read-only) and CompanionEditor (inline edit).
import type { Guest, GuestLanguage } from '@/types/guests';
import { ref } from 'vue';
import CompanionDisplay from './CompanionDisplay.vue';
import CompanionEditor from './CompanionEditor.vue';

const props = defineProps<{
    companion: Guest;
}>();

const emit = defineEmits<{
    delete: [];
    'update-companion': [
        payload: {
            id: string;
            name: string;
            surname: string;
            language: GuestLanguage;
        },
    ];
}>();

const editing = ref(false);

const onSaveEdit = (input: {
    name: string;
    surname: string;
    language: GuestLanguage;
}) => {
    emit('update-companion', { id: props.companion.id, ...input });
    editing.value = false;
};

const onDelete = () => emit('delete');

// TODO(backend): swap the two handlers above for the Inertia versions
// below; then delete handleUpdateCompanion + handleDeleteCompanion in Guests.vue.
//   import { router } from '@inertiajs/vue3';
//   import { destroy, update } from '@/actions/App/Http/Controllers/GuestsController';
//   const onSaveEdit = (input: { name: string; surname: string; language: GuestLanguage }) =>
//       router.put(update(props.companion.id), input, {
//           only: ['guests'], onSuccess: () => (editing.value = false),
//       });
//   const onDelete = () => router.delete(destroy(props.companion.id), { only: ['guests'] });
</script>

<template>
    <CompanionEditor
        v-if="editing"
        :companion
        @save="onSaveEdit"
        @cancel="editing = false"
    />
    <CompanionDisplay
        v-else
        :companion
        @edit="editing = true"
        @delete="onDelete"
    />
</template>
