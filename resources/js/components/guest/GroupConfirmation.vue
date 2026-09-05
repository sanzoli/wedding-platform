<script setup lang="ts">
import ResponseCard from '@/components/guest/ResponseCard.vue';
import type { GuestGroupMember, ResponseOption } from '@/types/save-the-date';

defineProps<{
    members: GuestGroupMember[];
    options: Record<ResponseOption, string>;
    selected: Record<number, ResponseOption | null>;
    currentGuestId: number;
    youLabel: string;
}>();

defineEmits<{
    select: [id: number, response: ResponseOption];
}>();
</script>

<template>
    <section class="space-y-4 px-6 pb-6">
        <ResponseCard
            v-for="member in members"
            :key="member.id"
            :member="member"
            :options="options"
            :selected="selected[member.id] ?? null"
            :you-label="youLabel"
            :is-you="member.id === currentGuestId"
            @select="(response) => $emit('select', member.id, response)"
        />
    </section>
</template>
