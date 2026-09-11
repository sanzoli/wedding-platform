<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref } from 'vue';
import EnvelopeArt from './EnvelopeArt.vue';
import sealImage from './img/sello.png';

defineProps<{
    hint: string;
    openLabel: string;
}>();

/** How long a guest is left alone with the seal before the hint appears. */
const HINT_DELAY = 3200;

/** Flap swing plus the sleeve dissolve — see the guest-* motion tokens. */
const OPENING_DURATION = 1750;

const opened = ref(false);
const dismissed = ref(false);
const hintVisible = ref(false);

let hintTimer: number | undefined;
let dismissTimer: number | undefined;

const prefersReducedMotion = () =>
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const open = () => {
    if (opened.value) {
        return;
    }

    opened.value = true;
    window.clearTimeout(hintTimer);

    if (prefersReducedMotion()) {
        dismissed.value = true;

        return;
    }

    dismissTimer = window.setTimeout(
        () => (dismissed.value = true),
        OPENING_DURATION,
    );
};

onMounted(() => {
    if (prefersReducedMotion()) {
        hintVisible.value = true;

        return;
    }

    hintTimer = window.setTimeout(() => (hintVisible.value = true), HINT_DELAY);
});

onBeforeUnmount(() => {
    window.clearTimeout(hintTimer);
    window.clearTimeout(dismissTimer);
});
</script>

<template>
    <div
        v-if="!dismissed"
        class="guest-envelope-stage"
        :class="{ 'guest-envelope-stage--opened': opened }"
        :inert="opened"
    >
        <div class="guest-envelope">
            <EnvelopeArt />

            <button
                type="button"
                class="guest-seal"
                :aria-label="openLabel"
                :style="{ '--guest-seal-image': `url(${sealImage})` }"
                @click="open"
            >
                <span class="guest-seal-half guest-seal-half--left" />
                <span class="guest-seal-half guest-seal-half--right" />
            </button>

            <p
                class="guest-envelope-hint"
                :class="{ 'guest-envelope-hint--visible': hintVisible }"
                aria-hidden="true"
            >
                {{ hint }}
            </p>
        </div>
    </div>
</template>
