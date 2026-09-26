<script setup lang="ts">
import { useSlots } from 'vue';

/** Chrome-less viewport shell; with an `aside` slot it splits on lg (sticky aside, scrolling content) and stacks below. */
const hasAside = !!useSlots().aside;
</script>

<template>
    <div
        class="relative flex min-h-svh flex-col bg-background text-foreground antialiased lg:flex-row"
    >
        <svg class="absolute size-0" aria-hidden="true" focusable="false">
            <filter
                id="guest-deckle"
                x="-10%"
                y="-10%"
                width="120%"
                height="120%"
            >
                <feTurbulence
                    type="fractalNoise"
                    baseFrequency="0.022 0.022"
                    numOctaves="3"
                    seed="11"
                    result="grain"
                />
                <feDisplacementMap
                    in="SourceGraphic"
                    in2="grain"
                    scale="6"
                    xChannelSelector="R"
                    yChannelSelector="G"
                />
            </filter>
        </svg>

        <div
            v-if="hasAside"
            class="lg:sticky lg:top-0 lg:h-svh lg:w-[44%] lg:shrink-0"
        >
            <slot name="aside" />
        </div>

        <div
            :class="hasAside ? 'flex-1 lg:h-svh lg:overflow-y-auto' : 'flex-1'"
        >
            <slot />
        </div>
    </div>
</template>
