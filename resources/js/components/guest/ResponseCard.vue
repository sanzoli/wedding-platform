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

// Fixed display order — `options` is a map, so we can't rely on key order.
const order: ResponseOption[] = ['yes', 'probably_yes', 'probably_no', 'no'];
</script>

<template>
    <article class="rounded-2xl border border-border bg-card p-5 md:p-6">
        <div class="mb-4 flex items-center gap-3">
            <span
                class="flex size-10 shrink-0 items-center justify-center rounded-full bg-secondary text-sm font-medium text-secondary-foreground"
                aria-hidden="true"
            >
                {{ member.initials }}
            </span>
            <div class="min-w-0">
                <p class="truncate font-display text-lg text-foreground">
                    {{ member.full_name }}
                </p>
                <p
                    v-if="isYou"
                    class="guest-eyebrow text-[0.625rem] text-accent"
                >
                    You
                </p>
            </div>
        </div>

        <fieldset>
            <legend class="sr-only">Response for {{ member.full_name }}</legend>
            <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                <label
                    v-for="option in order"
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
                        class="flex h-full items-center justify-center rounded-xl border border-border bg-background px-3 py-2.5 text-center text-sm leading-tight text-foreground transition-colors peer-checked:border-primary peer-checked:bg-primary peer-checked:text-primary-foreground peer-focus-visible:ring-2 peer-focus-visible:ring-ring peer-focus-visible:ring-offset-2 hover:border-primary/40"
                    >
                        {{ options[option] }}
                    </span>
                </label>
            </div>
        </fieldset>
    </article>
</template>
