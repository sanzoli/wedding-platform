<script setup lang="ts">
import ResponseCard from '@/components/guest/save-the-date/ResponseCard.vue';
import type {
    GuestGroupMember,
    ResponseOption,
    SaveStatus,
} from '@/types/save-the-date';

defineProps<{
    members: GuestGroupMember[];
    selected: Record<number, ResponseOption | null>;
    statuses: Record<number, SaveStatus | undefined>;
    currentGuestId: number;
}>();

defineEmits<{
    select: [id: number, response: ResponseOption];
    retry: [id: number];
}>();
</script>

<template>
    <section class="space-y-5 px-6 pb-6">
        <ResponseCard
            v-for="member in members"
            :key="member.id"
            :member="member"
            :selected="selected[member.id] ?? null"
            :status="statuses[member.id]"
            :is-you="members.length > 1 && member.id === currentGuestId"
            @select="(response) => $emit('select', member.id, response)"
            @retry="$emit('retry', member.id)"
        />
    </section>
</template>
