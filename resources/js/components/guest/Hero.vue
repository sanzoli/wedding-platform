<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref } from 'vue';

defineProps<{
    eyebrow: string;
    names: string;
    date: string;
    location: string;
}>();

const DESKTOP_QUERY = '(min-width: 1024px)';

const sentinel = ref<HTMLElement | null>(null);
const collapsed = ref(false);

let observer: IntersectionObserver | null = null;
let desktop: MediaQueryList | null = null;

const stopObserving = () => {
    observer?.disconnect();
    observer = null;
};

const startObserving = () => {
    if (observer || !sentinel.value) {
        return;
    }

    observer = new IntersectionObserver(
        ([entry]) => (collapsed.value = !entry.isIntersecting),
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
});

onBeforeUnmount(() => {
    desktop?.removeEventListener('change', syncToViewport);
    stopObserving();
});
</script>

<template>
    <section
        class="relative flex min-h-svh flex-col bg-primary text-primary-foreground lg:h-full lg:min-h-0"
    >
        <div
            class="pointer-events-none absolute inset-0 bg-linear-to-b from-white/5 via-transparent to-black/25"
        />

        <div class="relative z-10 hidden justify-end px-6 pt-6 lg:flex">
            <slot name="nav" />
        </div>

        <div
            class="relative z-10 flex flex-1 flex-col items-center justify-center px-6 pt-6 pb-12 text-center"
        >
            <div class="guest-fade-up mb-8 flex items-center gap-3 text-accent">
                <span class="h-px w-8 bg-accent/40" />
                <span class="font-display text-sm tracking-[0.2em]"
                    >L &amp; D</span
                >
                <span class="h-px w-8 bg-accent/40" />
            </div>

            <p
                class="guest-eyebrow guest-fade-up text-accent [animation-delay:60ms]"
            >
                {{ eyebrow }}
            </p>

            <h1
                class="guest-fade-up mt-7 font-display text-5xl leading-[1.04] font-medium tracking-tight [animation-delay:140ms] md:text-7xl lg:text-6xl xl:text-7xl"
            >
                {{ names }}
            </h1>

            <div
                class="guest-fade-up mt-9 flex flex-col items-center gap-3 [animation-delay:240ms]"
            >
                <span class="h-px w-12 bg-primary-foreground/30" />
                <p class="text-lg font-light tracking-wide md:text-xl">
                    {{ date }}
                </p>
                <p class="guest-eyebrow text-primary-foreground/60">
                    {{ location }}
                </p>
            </div>

            <div ref="sentinel" class="mt-10 h-px w-full lg:hidden" />

            <div class="mt-6 flex justify-center lg:hidden">
                <slot name="nav" />
            </div>
        </div>

        <div
            class="relative z-10 flex justify-center pb-8 text-primary-foreground/40 lg:hidden"
        >
            <span class="h-8 w-px bg-primary-foreground/25" />
        </div>
    </section>

    <header
        class="guest-sticky-header fixed inset-x-0 top-0 z-30 flex h-16 items-center justify-between gap-4 bg-primary/95 px-4 text-primary-foreground backdrop-blur lg:hidden"
        :class="collapsed ? 'guest-sticky-header--shown' : ''"
        :aria-hidden="!collapsed"
        :inert="!collapsed"
    >
        <div class="min-w-0">
            <p class="truncate font-display text-base leading-tight">
                {{ names }}
            </p>
            <p
                class="truncate text-xs leading-tight text-primary-foreground/65"
            >
                {{ date }}
            </p>
        </div>

        <slot name="nav" />
    </header>
</template>
