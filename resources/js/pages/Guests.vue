<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import GuestTable from '@/components/guests/GuestTable.vue';
import { Guest } from '@/types/guests';
import Heading from '@/components/Heading.vue';

defineProps<{
    guests: {
        data: Guest[];
    };
}>();
</script>

<template>
    <Head title="Guests" />

    <AppLayout>
        <div class="flex h-full flex-1 flex-col px-6 py-6">
            <Heading title="Guest List"></Heading>
            <GuestTable :guests="guests.data"></GuestTable>
        </div>
    </AppLayout>
</template>
