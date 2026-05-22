<script setup lang="ts">
import type { Guest, GuestLanguage } from '@/types/guests';
import CompanionDisplay from './CompanionDisplay.vue';
import CompanionEditor from './CompanionEditor.vue';

const props = defineProps<{
    companion: Guest;
    editing: boolean;
    search: string;
}>();

const emit = defineEmits<{
    delete: [];
    'start-edit': [];
    'cancel-edit': [];
    'update-companion': [
        payload: {
            id: string;
            name: string;
            surname: string;
            language: GuestLanguage;
        },
    ];
}>();

const onSaveEdit = (input: {
    name: string;
    surname: string;
    language: GuestLanguage;
}) => {
    emit('update-companion', { id: props.companion.id, ...input });
    emit('cancel-edit');
};

const onDelete = () => emit('delete');

// TODO(backend): swap the two handlers above for the Inertia versions
// below; then delete handleUpdateCompanion + handleDeleteCompanion in Guests.vue.
//   import { router } from '@inertiajs/vue3';
//   import { destroy, update } from '@/actions/App/Http/Controllers/GuestsController';
//   const onSaveEdit = (input: { name: string; surname: string; language: GuestLanguage }) =>
//       router.put(update(props.companion.id), input, {
//           only: ['guests'], onSuccess: () => emit('cancel-edit'),
//       });
//   const onDelete = () => router.delete(destroy(props.companion.id), { only: ['guests'] });
</script>

<template>
    <CompanionEditor
        v-if="editing"
        :companion
        @save="onSaveEdit"
        @cancel="emit('cancel-edit')"
    />
    <CompanionDisplay
        v-else
        :companion
        :search
        @edit="emit('start-edit')"
        @delete="onDelete"
    />
</template>
