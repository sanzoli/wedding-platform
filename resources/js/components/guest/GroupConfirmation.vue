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
    <section class="space-y-5 px-6 pb-6">
        <svg class="absolute size-0" aria-hidden="true" focusable="false">
            <filter
                id="guest-deckle"
                x="-10%"
                y="-10%"
                width="120%"
                height="120%"
            >
                <feTurbulence
                    type="fractalNoise"
                    baseFrequency="0.022 0.022"
                    numOctaves="3"
                    seed="11"
                    result="grain"
                />
                <feDisplacementMap
                    in="SourceGraphic"
                    in2="grain"
                    scale="6"
                    xChannelSelector="R"
                    yChannelSelector="G"
                />
            </filter>
        </svg>

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
