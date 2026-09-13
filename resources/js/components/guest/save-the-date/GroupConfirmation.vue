<script setup lang="ts">
import ResponseCard from '@/components/guest/save-the-date/ResponseCard.vue';
import type {
    GuestGroupMember,
    ResponseOption,
    SaveStatus,
} from '@/types/save-the-date';

const props = defineProps<{
    members: GuestGroupMember[];
    options: Record<ResponseOption, string>;
    selected: Record<number, ResponseOption | null>;
    statuses: Record<number, SaveStatus | undefined>;
    statusLabels: Record<SaveStatus, string>;
    retryLabel: string;
    currentGuestId: number;
    youLabel: string;
}>();

defineEmits<{
    select: [id: number, response: ResponseOption];
    retry: [id: number];
}>();

const statusLabel = (id: number) => {
    const status = props.statuses[id];

    return status ? props.statusLabels[status] : '';
};
</script>

<template>
    <section class="space-y-5 px-6 pb-6">
        <ResponseCard
            v-for="member in members"
            :key="member.id"
            :member="member"
            :options="options"
            :selected="selected[member.id] ?? null"
            :status="statuses[member.id]"
            :status-label="statusLabel(member.id)"
            :retry-label="retryLabel"
            :you-label="youLabel"
            :is-you="members.length > 1 && member.id === currentGuestId"
            @select="(response) => $emit('select', member.id, response)"
            @retry="$emit('retry', member.id)"
        />
    </section>
</template>
