<script setup lang="ts">
import { GuestGroup } from '@/types/guests';
import GuestRow from '@/components/guests/GuestRow.vue';

defineProps<{
    group: GuestGroup;
    query?: string;
}>();
</script>

<template>
    <GuestRow :guest="group.primary" :query :members="group.count"/>
</template>
