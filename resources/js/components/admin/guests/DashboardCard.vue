<script setup lang="ts">
import { index as guestsList } from '@/actions/App/Http/Controllers/GuestController';
import { Card } from '@/components/ui/card';
import { Link, usePage } from '@inertiajs/vue3';
import { Users } from 'lucide-vue-next';

const guests = usePage().props.guestsTotal;
</script>

<template>
    <Link :href="guestsList()" class="block">
        <Card
            class="group relative aspect-video overflow-hidden rounded-xl border border-border transition-all duration-200 hover:border-primary/50 hover:shadow-lg"
        >
            <div
                class="flex h-full flex-col items-center justify-center gap-2 p-6 text-center"
            >
                <div
                    class="rounded-full bg-primary/10 p-3 transition-colors group-hover:bg-primary/20"
                >
                    <Users class="h-8 w-8 text-primary" />
                </div>
                <div>
                    <h1 class="text-2xl font-semibold text-foreground">
                        Guests
                    </h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Total registered guests
                    </p>
                </div>
                <div class="mt-2">
                    <span class="text-4xl font-bold text-primary">
                        {{ guests }}
                    </span>
                </div>
            </div>
        </Card>
    </Link>
</template>
