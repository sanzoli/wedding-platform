<script setup lang="ts">
import type { CompanionView, GuestLanguage } from '@/types/guests';
import CompanionDisplay from './CompanionDisplay.vue';
import CompanionEditor from './CompanionEditor.vue';

withDefaults(
    defineProps<{
        companion: CompanionView;
        editing?: boolean;
    }>(),
    { editing: false },
);

defineEmits<{
    edit: [];
    delete: [];
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
        @edit="$emit('edit')"
        @delete="$emit('delete')"
    />
</template>
