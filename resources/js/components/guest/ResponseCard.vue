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
    <article class="guest-card">
        <div class="mb-4">
            <p class="font-display text-xl text-primary">
                {{ member.full_name }}
                <span
                    v-if="isYou"
                    class="guest-accent-ink ml-1 align-middle text-xs"
                >
                    · {{ youLabel }}
                </span>
            </p>
        </div>

        <fieldset>
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
