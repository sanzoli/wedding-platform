<script setup lang="ts">
defineProps<{
    eyebrow: string;
    names: string;
    date: string;
    location: string;
}>();
</script>

<template>
    <section
        class="relative flex min-h-svh flex-col bg-primary text-primary-foreground"
    >
        <div
            class="pointer-events-none absolute inset-0 bg-linear-to-b from-white/5 via-transparent to-black/25"
        />

        <div class="relative z-10 flex justify-end px-6 pt-6">
            <slot name="nav" />
        </div>

        <div
            class="relative z-10 flex flex-1 flex-col items-center justify-center px-6 pb-12 text-center"
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
                class="guest-fade-up mt-7 font-display text-5xl leading-[1.04] font-medium tracking-tight [animation-delay:140ms] md:text-7xl"
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
        </div>

        <div
            class="relative z-10 flex justify-center pb-8 text-primary-foreground/40"
        >
            <span class="h-8 w-px bg-primary-foreground/25" />
        </div>
    </section>
</template>
