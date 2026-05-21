<script setup lang="ts">
// Wrapper for the entity/display split. Will host the edit toggle and
// GroupEditor when inline edit lands; pass-through for now.
import type { GuestGroupView } from '@/types/guests';
import GroupDisplay from './GroupDisplay.vue';

defineProps<{
    group: GuestGroupView;
    expanded: boolean;
}>();

const emit = defineEmits<{
    toggle: [];
    delete: [];
    'add-companion': [];
}>();
</script>

<template>
    <GroupDisplay
        :group
        :expanded
        @toggle="emit('toggle')"
        @delete="emit('delete')"
        @add-companion="emit('add-companion')"
    />
</template>
