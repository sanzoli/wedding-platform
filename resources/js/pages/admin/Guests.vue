<script setup lang="ts">
import GuestsTable from '@/components/admin/guests/GuestsTable.vue';
import Heading from '@/components/Heading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { GuestGroupView } from '@/types/guests';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    groups: GuestGroupView[];
    totalGuests: number;
    totalGroups: number;
}>();

const trans = usePage().props.trans.guests as Record<string, string>;

const strip = computed(() =>
    [
        `${props.totalGuests} ${props.totalGuests === 1 ? trans.meta_guests_one : trans.meta_guests_other}`,
        `${props.totalGroups} ${props.totalGroups === 1 ? trans.meta_groups_one : trans.meta_groups_other}`,
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

            <GuestsTable
                :table-groups="props.groups"
                :all-groups="props.groups"
            />
        </div>
    </AppLayout>
</template>
