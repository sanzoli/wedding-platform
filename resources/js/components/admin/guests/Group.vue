<script setup lang="ts">
import type { GuestGroupView, GuestLanguage } from '@/types/guests';
import GroupDisplay from './GroupDisplay.vue';
import GroupEditor from './GroupEditor.vue';

const props = defineProps<{
    group: GuestGroupView;
    expanded: boolean;
    editing: boolean;
    search: string;
}>();

const emit = defineEmits<{
    toggle: [];
    delete: [];
    'add-companion': [];
    'start-edit': [];
    'cancel-edit': [];
    'update-group': [
        payload: {
            id: string;
            name: string;
            surname: string;
            language: GuestLanguage;
            mobile: string;
        },
    ];
}>();

const onSaveEdit = (input: {
    name: string;
    surname: string;
    language: GuestLanguage;
    mobile: string;
}) => {
    emit('update-group', { id: props.group.id, ...input });
    emit('cancel-edit');
};

const onDelete = () => emit('delete');

// TODO(backend): swap the two handlers above for the Inertia versions
// below; then delete handleUpdateGroup + handleDeleteGroup in Guests.vue.
//   import { router } from '@inertiajs/vue3';
//   import { destroy, update } from '@/actions/App/Http/Controllers/GuestGroupsController';
//   const onSaveEdit = (input: { name: string; surname: string; language: GuestLanguage; mobile: string }) =>
//       router.put(update(props.group.id), input, {
//           only: ['guests'], onSuccess: () => emit('cancel-edit'),
//       });
//   const onDelete = () => router.delete(destroy(props.group.id), { only: ['guests'] });
</script>

<template>
    <GroupEditor
        v-if="editing"
        :group
        @save="onSaveEdit"
        @cancel="emit('cancel-edit')"
    />
    <GroupDisplay
        v-else
        :group
        :expanded
        :search
        @toggle="emit('toggle')"
        @edit="emit('start-edit')"
        @delete="onDelete"
        @add-companion="emit('add-companion')"
    />
</template>
