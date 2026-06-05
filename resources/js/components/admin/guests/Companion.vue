<script setup lang="ts">
import type { CompanionView, GuestLanguage } from '@/types/guests';
import CompanionDisplay from './CompanionDisplay.vue';
import CompanionEditor from './CompanionEditor.vue';

withDefaults(
    defineProps<{
        companion: CompanionView;
        editing?: boolean;
        canChangeGroup?: boolean;
    }>(),
    { editing: false, canChangeGroup: false },
);

defineEmits<{
    edit: [];
    delete: [];
    'change-group': [];
    'leave-group': [];
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
    <CompanionEditor
        v-if="editing"
        :companion
        @save="$emit('save', $event)"
        @cancel="$emit('cancel-edit')"
    />
    <CompanionDisplay
        v-else
        :companion
        :can-change-group
        @edit="$emit('edit')"
        @delete="$emit('delete')"
        @change-group="$emit('change-group')"
        @leave-group="$emit('leave-group')"
    />
</template>
