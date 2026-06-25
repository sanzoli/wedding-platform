<script setup lang="ts">
defineProps<{
    eyebrow: string;
    names: string;
    date: string;
    location: string;
    greeting: string;
    intro: string;
}>();
</script>

<template>
    <header class="px-6 pt-20 pb-12 text-center md:pt-28 md:pb-16">
        <div class="mx-auto max-w-2xl">
            <p class="guest-eyebrow guest-fade-up text-accent">
                {{ eyebrow }}
            </p>
            <div
                class="guest-fade-up mx-auto mt-5 h-px w-10 bg-accent/50 [animation-delay:80ms]"
            />

            <h1
                class="guest-fade-up mt-8 font-display text-5xl leading-[1.05] font-medium tracking-tight text-foreground [animation-delay:140ms] md:text-7xl"
            >
                {{ names }}
            </h1>

            <div
                class="guest-fade-up mt-9 flex flex-col items-center gap-3 [animation-delay:220ms]"
            >
                <p
                    class="text-lg font-light tracking-wide text-foreground md:text-xl"
                >
                    {{ date }}
                </p>
                <p class="guest-eyebrow text-muted-foreground">
                    {{ location }}
                </p>
            </div>

            <div
                class="guest-fade-up mx-auto mt-12 max-w-md border-t border-border/70 pt-10 [animation-delay:320ms]"
            >
                <p class="font-display text-2xl text-foreground md:text-3xl">
                    {{ greeting }}
                </p>
                <p class="mt-3 text-base leading-relaxed text-muted-foreground">
                    {{ intro }}
                </p>
            </div>
        </div>
    </header>
</template>
