<script setup lang="ts">
import { useId } from 'vue';

const clipId = useId();
</script>

<template>
    <svg viewBox="0 0 24 24" aria-hidden="true">
        <defs>
            <clipPath :id="clipId">
                <circle cx="12" cy="12" r="11.5" />
            </clipPath>
        </defs>
        <g :clip-path="`url(#${clipId})`">
            <rect x="0" y="0" width="24" height="24" fill="#009B3A" />
            <path d="M12 3.5 L20.5 12 L12 20.5 L3.5 12 Z" fill="#FEDF00" />
            <circle cx="12" cy="12" r="4" fill="#002776" />
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
