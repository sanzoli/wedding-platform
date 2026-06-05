<script setup lang="ts">
import GuestsTable from '@/components/admin/guests/GuestsTable.vue';
import Heading from '@/components/Heading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import {
    mockGuestGroupViews,
    mockTotalGroups,
    mockTotalGuests,
} from '@/lib/mock/guests';
import type { SelectGroupDialogStatus } from '@/types/guests';
import { Head, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const trans = usePage().props.trans.guests as Record<string, string>;

// Canonical pool — stand-in for the wired allGroups (refreshed only on catalog mutations).
const allGroups = mockGuestGroupViews;
const totalGuests = mockTotalGuests;
const totalGroups = mockTotalGroups;

// --- demo-only controls (dev panel) --------------------------------------

const dialogStatuses: SelectGroupDialogStatus[] = [
    'idle',
    'submitting',
    'success',
    'error',
];
const dialogStatus = ref<SelectGroupDialogStatus>('idle');

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

            <GuestsTable
                :table-groups="allGroups"
                :all-groups="allGroups"
                :dialog-status="dialogStatus"
            />
        </div>
    </AppLayout>

    <!-- Dev-only panel: drives the dialog status for visual review. -->
    <aside
        @pointerdown.stop
        class="pointer-events-auto fixed right-4 bottom-4 z-[100] max-w-56 rounded-md border-2 border-dashed border-border bg-card/95 p-3 text-xs shadow-md backdrop-blur"
        aria-label="Dev controls"
    >
        <div
            class="mb-2 text-[10px] font-semibold tracking-wider text-muted-foreground uppercase"
        >
            Dev
        </div>

        <fieldset class="space-y-0.5">
            <legend class="mb-1 text-muted-foreground">Dialog status</legend>
            <label
                v-for="s in dialogStatuses"
                :key="s"
                class="flex items-center gap-2"
            >
                <input
                    v-model="dialogStatus"
                    type="radio"
                    name="dialog-status"
                    :value="s"
                />
                <span class="capitalize">{{ s }}</span>
            </label>
        </fieldset>
    </aside>
</template>
