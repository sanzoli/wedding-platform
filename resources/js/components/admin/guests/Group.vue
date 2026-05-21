<script setup lang="ts">
// Wrapper for the entity/display split. Hosts the edit toggle between
// GroupDisplay (read-only) and GroupEditor (inline edit).
import type { GuestGroupView, GuestLanguage } from '@/types/guests';
import { ref } from 'vue';
import GroupDisplay from './GroupDisplay.vue';
import GroupEditor from './GroupEditor.vue';

const props = defineProps<{
    group: GuestGroupView;
    expanded: boolean;
}>();

const emit = defineEmits<{
    toggle: [];
    delete: [];
    'add-companion': [];
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

const editing = ref(false);

const onSaveEdit = (input: {
    name: string;
    surname: string;
    language: GuestLanguage;
    mobile: string;
}) => {
    emit('update-group', { id: props.group.id, ...input });
    editing.value = false;
};

const onDelete = () => emit('delete');

// TODO(backend): swap the two handlers above for the Inertia versions
// below; then delete handleUpdateGroup + handleDeleteGroup in Guests.vue.
//   import { router } from '@inertiajs/vue3';
//   import { destroy, update } from '@/actions/App/Http/Controllers/GuestGroupsController';
//   const onSaveEdit = (input: { name: string; surname: string; language: GuestLanguage; mobile: string }) =>
//       router.put(update(props.group.id), input, {
//           only: ['guests'], onSuccess: () => (editing.value = false),
//       });
//   const onDelete = () => router.delete(destroy(props.group.id), { only: ['guests'] });
</script>

<template>
    <GroupEditor
        v-if="editing"
        :group
        @save="onSaveEdit"
        @cancel="editing = false"
    />
    <GroupDisplay
        v-else
        :group
        :expanded
        @toggle="emit('toggle')"
        @edit="editing = true"
        @delete="onDelete"
        @add-companion="emit('add-companion')"
    />
</template>
