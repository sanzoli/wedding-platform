<script setup lang="ts">
import type { GuestGroupMember, ResponseOption } from '@/types/save-the-date';
import { Check, Minus, X } from 'lucide-vue-next';
import type { Component } from 'vue';

defineProps<{
    member: GuestGroupMember;
    options: Record<ResponseOption, string>;
    selected: ResponseOption | null;
    youLabel: string;
    isYou?: boolean;
}>();

defineEmits<{
    select: [response: ResponseOption];
}>();

const choices: { value: ResponseOption; icon: Component }[] = [
    { value: 'yes', icon: Check },
    { value: 'probably_yes', icon: Check },
    { value: 'probably_no', icon: Minus },
    { value: 'no', icon: X },
];
</script>

<template>
    <article class="guest-card lg:flex lg:items-center lg:gap-5">
        <div class="mb-4 flex items-center gap-3 lg:mb-0 lg:w-40 lg:shrink-0">
            <span
                class="flex size-10 shrink-0 items-center justify-center rounded-full border border-accent/30 bg-accent/10 text-xs font-medium tracking-wide text-foreground"
                aria-hidden="true"
            >
                {{ member.initials }}
            </span>
            <p class="font-display text-lg text-foreground">
                {{ member.full_name }}
                <span
                    v-if="isYou"
                    class="guest-accent-ink ml-1 align-middle text-[0.625rem] tracking-[0.2em] uppercase"
                >
                    · {{ youLabel }}
                </span>
            </p>
        </div>

        <fieldset class="lg:min-w-0 lg:flex-1">
            <legend class="sr-only">{{ member.full_name }}</legend>

            <div class="grid gap-2 sm:grid-cols-2">
                <label
                    v-for="choice in choices"
                    :key="choice.value"
                    class="guest-option"
                >
                    <input
                        type="radio"
                        class="sr-only"
                        :name="`member-${member.id}`"
                        :value="choice.value"
                        :checked="selected === choice.value"
                        @change="$emit('select', choice.value)"
                    />
                    <component
                        :is="choice.icon"
                        class="size-4 shrink-0"
                        aria-hidden="true"
                    />
                    <span>{{ options[choice.value] }}</span>
                </label>
            </div>
        </fieldset>
    </article>
</template>
