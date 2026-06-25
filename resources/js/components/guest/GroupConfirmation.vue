<script setup lang="ts">
import ResponseCard from '@/components/guest/ResponseCard.vue';
import type { GuestGroupMember, ResponseOption } from '@/types/save-the-date';

defineProps<{
    title: string;
    hint: string;
    members: GuestGroupMember[];
    options: Record<ResponseOption, string>;
    selected: Record<number, ResponseOption | null>;
    currentGuestId: number;
}>();

defineEmits<{
    select: [id: number, response: ResponseOption];
}>();
</script>

<template>
    <section class="px-6">
        <div class="mx-auto max-w-xl">
            <div class="mb-2 text-center">
                <h2 class="font-display text-2xl text-foreground md:text-3xl">
                    {{ title }}
                </h2>
                <p
                    class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-muted-foreground"
                >
                    {{ hint }}
                </p>
            </div>

            <div class="divide-y divide-border/60">
                <ResponseCard
                    v-for="member in members"
                    :key="member.id"
                    :member="member"
                    :options="options"
                    :selected="selected[member.id] ?? null"
                    :is-you="member.id === currentGuestId"
                    @select="(response) => $emit('select', member.id, response)"
                />
            </div>
        </div>
    </section>
</template>
