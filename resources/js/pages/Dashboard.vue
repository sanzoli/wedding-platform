<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout>
    <div class="px-6 py-4">
        <div
            class="-mx-6 mb-1 flex items-center justify-between bg-background/95 px-6 pt-2 backdrop-blur-sm transition-shadow duration-200 sm:mb-2"
        >
            <div>
                <h1 class="type-display text-foreground">
                    Dashboard
                </h1>
                <p class="mt-2 text-[15px] text-muted-foreground">
                    Overview of your wedding planning progress
                </p>
            </div>
        </div>

        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-border"
                >
                    <PlaceholderPattern />
                </div>
                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-border"
                >
                    <PlaceholderPattern />
                </div>
                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-border"
                >
                    <PlaceholderPattern />
                </div>
            </div>
            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-border md:min-h-min"
            >
                <PlaceholderPattern />
            </div>
        </div>
    </div>
    </AppLayout>
</template>
