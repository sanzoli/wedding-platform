<script setup lang="ts">
// Wrapper for the entity/display split. Will host the edit toggle and
// CompanionEditor when inline edit lands; pass-through for now.
import type { Guest } from '@/types/guests';
import CompanionDisplay from './CompanionDisplay.vue';

defineProps<{
    companion: Guest;
}>();

const emit = defineEmits<{
    delete: [];
}>();
</script>

<template>
    <CompanionDisplay :companion @delete="emit('delete')" />
</template>
