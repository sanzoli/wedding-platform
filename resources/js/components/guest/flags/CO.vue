<script setup lang="ts">
import { useId } from 'vue';

const clipId = useId();
</script>

<template>
    <svg viewBox="0 0 24 24" aria-hidden="true">
        <defs>
            <clipPath :id="clipId"><circle cx="12" cy="12" r="11.5" /></clipPath>
        </defs>
        <g :clip-path="`url(#${clipId})`">
            <rect x="0" y="0" width="24" height="12" fill="#FCD116" />
            <rect x="0" y="12" width="24" height="6" fill="#003893" />
            <rect x="0" y="18" width="24" height="6" fill="#CE1126" />
        </g>
        <circle
            cx="12"
            cy="12"
            r="11.5"
            fill="none"
            stroke="currentColor"
            stroke-opacity="0.25"
            stroke-width="0.75"
        />
    </svg>
</template>
