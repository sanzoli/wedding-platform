<script setup lang="ts">
import GuestsTable from '@/components/admin/guests/GuestsTable.vue';
import Heading from '@/components/Heading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import {
    mockGuestGroupViews,
    mockTotalGroups,
    mockTotalGuests,
} from '@/lib/mock/guests';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const groups = mockGuestGroupViews;
const totalGuests = mockTotalGuests;
const totalGroups = mockTotalGroups;
const trans = usePage().props.trans.guests as Record<string, string>;

const strip = computed(() =>
    [
        `${totalGuests} ${totalGuests === 1 ? trans.meta_guests_one : trans.meta_guests_other}`,
        `${totalGroups} ${totalGroups === 1 ? trans.meta_groups_one : trans.meta_groups_other}`,
    ].join(' · '),
);
</script>

<template>
    <Head :title="trans.page_title" />

    <AppLayout>
        <div class="flex h-full flex-1 flex-col px-6 py-6">
            <Heading
                :title="trans.page_title"
                :description="trans.page_description"
            />
            <p
                class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
            >
                {{ strip }}
            </p>

            <GuestsTable :groups="groups" />
        </div>
    </AppLayout>
</template>
