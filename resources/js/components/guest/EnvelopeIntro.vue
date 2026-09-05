<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref } from 'vue';
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
            <svg
                class="guest-envelope-sheet"
                viewBox="0 0 1200 800"
                preserveAspectRatio="xMidYMid meet"
                aria-hidden="true"
            >
                <defs>
                    <linearGradient
                        id="envelope-face"
                        x1="0"
                        y1="0"
                        x2="0"
                        y2="1"
                    >
                        <stop offset="0" stop-color="#FAF6EE" />
                        <stop offset="1" stop-color="#EAE3D6" />
                    </linearGradient>
                    <linearGradient
                        id="envelope-flap-bottom"
                        x1="0"
                        y1="1"
                        x2="0"
                        y2="0"
                    >
                        <stop offset="0" stop-color="#F3EEE2" />
                        <stop offset="1" stop-color="#E3DCCC" />
                    </linearGradient>
                    <linearGradient
                        id="envelope-flap-left"
                        x1="0"
                        y1="0"
                        x2="1"
                        y2="0"
                    >
                        <stop offset="0" stop-color="#F0EADD" />
                        <stop offset="1" stop-color="#E1DAC9" />
                    </linearGradient>
                    <linearGradient
                        id="envelope-flap-right"
                        x1="1"
                        y1="0"
                        x2="0"
                        y2="0"
                    >
                        <stop offset="0" stop-color="#F0EADD" />
                        <stop offset="1" stop-color="#E1DAC9" />
                    </linearGradient>
                    <radialGradient
                        id="envelope-pocket"
                        cx="50%"
                        cy="12%"
                        r="90%"
                    >
                        <stop offset="0" stop-color="#D4CBB9" />
                        <stop offset="55%" stop-color="#DED5C3" />
                        <stop offset="100%" stop-color="#E5DECE" />
                    </radialGradient>

                    <filter
                        id="envelope-mottle"
                        x="0"
                        y="0"
                        width="100%"
                        height="100%"
                    >
                        <feTurbulence
                            type="fractalNoise"
                            baseFrequency="0.012 0.024"
                            numOctaves="4"
                            seed="6"
                            result="n"
                        />
                        <feColorMatrix
                            in="n"
                            type="matrix"
                            values="0 0 0 0 0.32  0 0 0 0 0.28  0 0 0 0 0.22  0 0 0 0.06 0"
                        />
                    </filter>
                    <filter
                        id="envelope-fibre"
                        x="0"
                        y="0"
                        width="100%"
                        height="100%"
                    >
                        <feTurbulence
                            type="fractalNoise"
                            baseFrequency="0.006 0.09"
                            numOctaves="2"
                            seed="21"
                            result="n"
                        />
                        <feColorMatrix
                            in="n"
                            type="matrix"
                            values="0 0 0 0 0.30  0 0 0 0 0.27  0 0 0 0 0.22  0 0 0 0.03 0"
                        />
                    </filter>
                    <radialGradient
                        id="envelope-age"
                        cx="50%"
                        cy="45%"
                        r="72%"
                    >
                        <stop
                            offset="58%"
                            stop-color="#3a2c12"
                            stop-opacity="0"
                        />
                        <stop
                            offset="100%"
                            stop-color="#3a2c12"
                            stop-opacity=".09"
                        />
                    </radialGradient>
                    <clipPath id="envelope-clip">
                        <rect
                            x="0"
                            y="0"
                            width="1200"
                            height="800"
                            rx="16"
                        />
                    </clipPath>
                </defs>

                <rect
                    x="0"
                    y="0"
                    width="1200"
                    height="800"
                    rx="16"
                    fill="url(#envelope-face)"
                />

                <path
                    d="M0 0 L1200 0 L600 430 Z"
                    fill="url(#envelope-pocket)"
                />
                <path
                    d="M0 0 L1200 0 L600 430 Z"
                    fill="none"
                    stroke="rgba(70,48,22,.10)"
                    stroke-width="26"
                    stroke-linejoin="round"
                    opacity=".5"
                />

                <path
                    d="M0 800 L1200 800 L600 430 Z"
                    fill="url(#envelope-flap-bottom)"
                />
                <path
                    d="M0 800 L600 430 L1200 800"
                    fill="none"
                    stroke="rgba(120,92,50,.18)"
                    stroke-width="1.5"
                />
                <path
                    d="M0 0 L0 800 L600 430 Z"
                    fill="url(#envelope-flap-left)"
                />
                <path
                    d="M0 0 L600 430 L0 800"
                    fill="none"
                    stroke="rgba(120,92,50,.14)"
                    stroke-width="1.5"
                />
                <path
                    d="M1200 0 L1200 800 L600 430 Z"
                    fill="url(#envelope-flap-right)"
                />
                <path
                    d="M1200 0 L600 430 L1200 800"
                    fill="none"
                    stroke="rgba(120,92,50,.14)"
                    stroke-width="1.5"
                />

                <g clip-path="url(#envelope-clip)">
                    <rect
                        x="0"
                        y="0"
                        width="1200"
                        height="800"
                        filter="url(#envelope-mottle)"
                        style="mix-blend-mode: multiply"
                    />
                    <rect
                        x="0"
                        y="0"
                        width="1200"
                        height="800"
                        filter="url(#envelope-fibre)"
                        style="mix-blend-mode: multiply"
                    />
                    <rect
                        x="0"
                        y="0"
                        width="1200"
                        height="800"
                        fill="url(#envelope-age)"
                    />
                </g>

                <rect
                    x="1"
                    y="1"
                    width="1198"
                    height="798"
                    rx="16"
                    fill="none"
                    stroke="var(--guest-parchment-edge)"
                    stroke-width="2"
                />
            </svg>

            <svg
                class="guest-envelope-sheet guest-envelope-flap"
                viewBox="0 0 1200 800"
                preserveAspectRatio="xMidYMid meet"
                aria-hidden="true"
            >
                <defs>
                    <linearGradient
                        id="envelope-flap-top"
                        x1="0"
                        y1="0"
                        x2="0"
                        y2="1"
                    >
                        <stop offset="0" stop-color="#FBF7EF" />
                        <stop offset="1" stop-color="#ECE5D7" />
                    </linearGradient>
                    <clipPath id="envelope-flap-clip">
                        <path d="M0 0 L1200 0 L600 430 Z" />
                    </clipPath>
                </defs>

                <path
                    d="M0 0 L1200 0 L600 430 Z"
                    fill="url(#envelope-flap-top)"
                />
                <g clip-path="url(#envelope-flap-clip)">
                    <rect
                        x="0"
                        y="0"
                        width="1200"
                        height="440"
                        filter="url(#envelope-mottle)"
                        style="mix-blend-mode: multiply"
                    />
                    <rect
                        x="0"
                        y="0"
                        width="1200"
                        height="440"
                        filter="url(#envelope-fibre)"
                        style="mix-blend-mode: multiply"
                    />
                </g>
                <path
                    d="M0 0 L600 430 L1200 0"
                    fill="none"
                    stroke="rgba(120,92,50,.16)"
                    stroke-width="1.5"
                />
                <path
                    d="M0 0 L600 430"
                    stroke="rgba(255,255,255,.45)"
                    stroke-width="1"
                />
                <path
                    d="M1200 0 L600 430"
                    stroke="rgba(255,255,255,.45)"
                    stroke-width="1"
                />
            </svg>

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
