<script setup lang="ts">
import { Guest } from '@/types/guests';
import { lang } from '@erag/lang-sync-inertia/vue';

const { trans, transChoice } = lang();
defineProps<{
    currentGuest: Guest;
}>();
</script>

<template>
    <div
        class="px-6 pt-[calc(var(--guest-header-height)+2rem)] pb-8 text-center lg:pt-14"
    >
        <p class="guest-greeting font-display text-foreground">
            {{
                trans('save_the_date.greeting', {
                    name: currentGuest.first_name,
                })
            }}
        </p>
        <p
            class="mx-auto mt-3 max-w-md text-lg leading-relaxed text-foreground/90"
        >
            {{ transChoice('save_the_date.intro', 1) }}
        </p>

        <span
            class="mx-auto mt-7 block h-px w-10 bg-border"
            aria-hidden="true"
        />

        <p
            class="mx-auto mt-5 max-w-md text-base leading-relaxed text-muted-foreground"
        >
            {{ transChoice('save_the_date.hint', 1) }}
        </p>
    </div>
</template>
