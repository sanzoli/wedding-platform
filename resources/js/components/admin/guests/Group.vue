<script setup lang="ts">
import type { GuestGroupView, GuestLanguage } from '@/types/guests';
import GroupDisplay from './GroupDisplay.vue';
import GroupEditor from './GroupEditor.vue';

withDefaults(
    defineProps<{
        group: GuestGroupView;
        expanded?: boolean;
        editing?: boolean;
        canJoin?: boolean;
    }>(),
    { expanded: false, editing: false, canJoin: false },
);

defineEmits<{
    toggle: [];
    edit: [];
    delete: [];
    'add-companion': [];
    split: [];
    join: [];
    'cancel-edit': [];
    save: [
        payload: {
            name: string;
            surname: string;
            language: GuestLanguage;
            mobile: string;
        },
    ];
}>();
</script>

<template>
    <GroupEditor
        v-if="editing"
        :group
        @save="$emit('save', $event)"
        @cancel="$emit('cancel-edit')"
    />
    <GroupDisplay
        v-else
        :group
        :expanded
        :can-join
        @toggle="$emit('toggle')"
        @edit="$emit('edit')"
        @delete="$emit('delete')"
        @add-companion="$emit('add-companion')"
        @split="$emit('split')"
        @join="$emit('join')"
    />
</template>
