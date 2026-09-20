<script setup lang="ts">
import { lang } from '@erag/lang-sync-inertia/vue';

const { trans } = lang();
defineProps<{
    saved: number;
    total: number;
}>();

</script>

<template>
    <div
        class="pointer-events-none sticky bottom-4 z-10 flex justify-center px-6"
    >
        <p
            class="guest-island guest-island--count pointer-events-auto px-5 py-2.5 text-sm"
            :class="$attrs.class"
            aria-live="polite"
        >
            {{
                trans('save_the_date.progress', {
                    saved: saved,
                    total: total,
                })
            }}
        </p>
    </div>
</template>
