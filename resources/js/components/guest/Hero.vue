<script setup lang="ts">
import { ChevronDown } from 'lucide-vue-next';
import { onBeforeUnmount, onMounted, ref } from 'vue';

defineProps<{
    eyebrow: string;
    names: string;
    date: string;
    location: string;
    scrollCue: string;
}>();

const DESKTOP_QUERY = '(min-width: 1024px)';

/** Kept in step with --guest-nudge-delay. */
const NUDGE_DELAY = 5000;

const sentinel = ref<HTMLElement | null>(null);
const collapsed = ref(false);
const nudging = ref(false);

let nudgeTimer: number | undefined;

let observer: IntersectionObserver | null = null;
let desktop: MediaQueryList | null = null;

const prefersReducedMotion = () =>
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const stopNudging = () => {
    window.clearTimeout(nudgeTimer);
    nudging.value = false;
};

const stopObserving = () => {
    observer?.disconnect();
    observer = null;
};

const startObserving = () => {
    if (observer || !sentinel.value) {
        return;
    }

    // Trimming the root by the band's own height hands over exactly as the
    // hero's foot clears the top: the band takes the hero's place instead
    // of sliding in over a hero that is still on screen.
    observer = new IntersectionObserver(
        ([entry]) => (collapsed.value = !entry.isIntersecting),
        { rootMargin: '-60px 0px 0px 0px' },
    );
    observer.observe(sentinel.value);
};

const syncToViewport = () => {
    if (desktop?.matches) {
        stopObserving();
        collapsed.value = false;

        return;
    }

    startObserving();
};

onMounted(() => {
    desktop = window.matchMedia(DESKTOP_QUERY);
    desktop.addEventListener('change', syncToViewport);
    syncToViewport();

    if (prefersReducedMotion()) {
        return;
    }

    nudgeTimer = window.setTimeout(() => (nudging.value = true), NUDGE_DELAY);
    window.addEventListener('scroll', stopNudging, {
        passive: true,
        once: true,
    });
});

onBeforeUnmount(() => {
    desktop?.removeEventListener('change', syncToViewport);
    window.removeEventListener('scroll', stopNudging);
    window.clearTimeout(nudgeTimer);
    stopObserving();
});
</script>

<template>
    <section
        class="relative flex min-h-(--guest-hero-mobile-height) flex-col bg-primary text-primary-foreground lg:h-full lg:min-h-0"
    >
        <div
            class="pointer-events-none absolute inset-0 bg-linear-to-b from-white/5 via-transparent to-black/25"
        />

        <div class="guest-safe-top relative z-10 flex justify-end px-6">
            <slot name="nav" />
        </div>

        <div
            class="relative z-10 flex flex-1 flex-col items-center justify-center px-6 pt-6 pb-4 text-center"
        >
            <div
                class="guest-accent-on-dark guest-fade-up guest-hero-monogram mb-8 flex items-center gap-3"
            >
                <span class="h-px w-8 bg-accent/40" />
                <span class="font-display text-sm tracking-[0.2em]"
                    >L &amp; D</span
                >
                <span class="h-px w-8 bg-accent/40" />
            </div>

            <p
                class="guest-eyebrow guest-accent-on-dark guest-fade-up [animation-delay:60ms]"
            >
                {{ eyebrow }}
            </p>

            <h1
                class="guest-fade-up guest-hero-title mt-7 font-display text-5xl leading-[1.04] font-medium tracking-tight [animation-delay:140ms] md:text-7xl lg:text-6xl xl:text-7xl"
            >
                {{ names }}
            </h1>

            <div
                class="guest-fade-up guest-hero-stack mt-9 flex flex-col items-center gap-3 [animation-delay:240ms]"
            >
                <span class="h-px w-12 bg-primary-foreground/30" />
                <p class="text-lg font-light tracking-wide md:text-xl">
                    {{ date }}
                </p>
                <p class="guest-eyebrow text-primary-foreground/75">
                    {{ location }}
                </p>
            </div>
        </div>

        <div
            class="guest-safe-bottom relative z-10 flex flex-col items-center gap-1 text-primary-foreground/75 lg:hidden"
        >
            <span class="text-sm">{{ scrollCue }}</span>
            <ChevronDown
                class="size-4"
                :class="{ 'guest-scroll-cue--nudge': nudging }"
                aria-hidden="true"
            />
        </div>

        <div ref="sentinel" class="h-px w-full lg:hidden" />
    </section>

    <header
        class="guest-sticky-header fixed inset-x-0 top-0 z-30 flex items-center gap-2 bg-primary px-3 pb-2 text-primary-foreground lg:hidden"
        :class="collapsed ? 'guest-sticky-header--shown' : ''"
        :inert="!collapsed"
    >
        <div class="min-w-0 flex-1">
            <p class="truncate font-display text-base leading-tight font-medium">
                {{ names }}
            </p>
            <p class="truncate text-xs leading-tight text-primary-foreground/75">
                {{ date }}
            </p>
        </div>

        <slot name="nav" />
    </header>
</template>
