<script setup lang="ts">
import type { GuestGroupMember, ResponseOption } from '@/types/save-the-date';

defineProps<{
    member: GuestGroupMember;
    options: Record<ResponseOption, string>;
    selected: ResponseOption | null;
    isYou?: boolean;
}>();

defineEmits<{
    select: [response: ResponseOption];
}>();

const displayOrder: ResponseOption[] = [
    'yes',
    'probably_yes',
    'probably_no',
    'no',
];
</script>

<template>
    <article class="py-6">
        <div class="mb-4 flex items-center gap-3">
            <span
                class="flex size-9 shrink-0 items-center justify-center rounded-full border border-accent/30 bg-accent/10 text-xs font-medium tracking-wide text-foreground"
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
                    · you
                </span>
            </p>
        </div>

        <fieldset>
            <legend class="sr-only">Response for {{ member.full_name }}</legend>
            <div class="grid grid-cols-2 gap-2 sm:flex sm:flex-wrap">
                <label
                    v-for="option in displayOrder"
                    :key="option"
                    class="cursor-pointer"
                >
                    <input
                        type="radio"
                        class="peer sr-only"
                        :name="`member-${member.id}`"
                        :value="option"
                        :checked="selected === option"
                        @change="$emit('select', option)"
                    />
                    <span
                        class="inline-flex min-h-12 w-full items-center justify-center rounded-full border border-border px-5 text-base text-foreground/80 transition-colors peer-checked:border-accent peer-checked:bg-accent peer-checked:text-accent-foreground peer-focus-visible:ring-2 peer-focus-visible:ring-accent peer-focus-visible:ring-offset-2 peer-focus-visible:outline-none hover:border-accent/50 hover:text-foreground sm:w-auto"
                    >
                        {{ options[option] }}
                    </span>
                </label>
            </div>
        </fieldset>
    </article>
</template>
