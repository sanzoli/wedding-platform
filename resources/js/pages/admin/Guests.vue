<script setup lang="ts">
import GuestsTable from '@/components/admin/guests/GuestsTable.vue';
import Heading from '@/components/Heading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import {
    buildGuestGroupViews,
    mockGuestGroups,
    mockGuests,
} from '@/lib/mock/guests';
import type { GuestLanguage } from '@/types/guests';
import { Head, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const trans = usePage().props.trans.guests as Record<string, string>;
trans.page_title ??= 'Guest list';
trans.page_description ??=
    'Manage the guest groups for the Colombia → Brasil wedding.';
trans.meta_guests_one ??= 'GUEST';
trans.meta_guests_other ??= 'GUESTS';
trans.meta_groups_one ??= 'GROUP';
trans.meta_groups_other ??= 'GROUPS';

// TODO(backend): delete this state + the handle* handlers below once the
// API lands. Inertia calls live in Group/Companion/NewGroup/NewCompanion.
const guests = ref([...mockGuests]);
const guestGroups = ref([...mockGuestGroups]);

const groups = computed(() =>
    buildGuestGroupViews(guestGroups.value, guests.value),
);

const strip = computed(() => {
    const totalGuests = groups.value.reduce(
        (acc, g) => acc + g.totalMembers,
        0,
    );
    const totalGroups = groups.value.length;
    return [
        `${totalGuests} ${totalGuests === 1 ? trans.meta_guests_one : trans.meta_guests_other}`,
        `${totalGroups} ${totalGroups === 1 ? trans.meta_groups_one : trans.meta_groups_other}`,
    ].join(' · ');
});

const handleDeleteGroup = (groupId: string) => {
    // Filter guestGroups first so the computed never sees an orphan group
    // (a group with no primary, which makes buildGuestGroupViews throw).
    guestGroups.value = guestGroups.value.filter((g) => g.id !== groupId);
    guests.value = guests.value.filter(
        (g) => g.guest_group_id !== groupId,
    );
};

const handleDeleteCompanion = (companionId: string) => {
    guests.value = guests.value.filter((g) => g.id !== companionId);
};

const handleCreateGroup = (payload: {
    name: string;
    surname: string;
    language: GuestLanguage;
    mobile: string;
}) => {
    const now = new Date().toISOString();
    const groupId = `gg_${Date.now()}`;
    const primaryId = `g_${Date.now()}`;

    guestGroups.value = [
        {
            id: groupId,
            wedding_id: 'wed_colombia_brasil',
            primary_guest_id: primaryId,
            created_at: now,
            updated_at: now,
        },
        ...guestGroups.value,
    ];
    guests.value = [
        {
            id: primaryId,
            name: payload.name,
            surname: payload.surname,
            mobile: payload.mobile || null,
            language: payload.language,
            guest_group_id: groupId,
            is_primary: true,
            name_status: 'known',
            archived_at: null,
            created_at: now,
            updated_at: now,
        },
        ...guests.value,
    ];
};

const handleCreateCompanion = (payload: {
    groupId: string;
    name: string;
    surname: string;
    language: GuestLanguage;
}) => {
    const now = new Date().toISOString();
    const companionId = `g_${Date.now()}`;
    const hasName = payload.name !== '' || payload.surname !== '';

    guests.value = [
        ...guests.value,
        {
            id: companionId,
            name: payload.name,
            surname: payload.surname,
            mobile: null,
            language: payload.language,
            guest_group_id: payload.groupId,
            is_primary: false,
            name_status: hasName ? 'known' : 'pending',
            archived_at: null,
            created_at: now,
            updated_at: now,
        },
    ];
};

const handleUpdateGroup = (payload: {
    id: string;
    name: string;
    surname: string;
    language: GuestLanguage;
    mobile: string;
}) => {
    const now = new Date().toISOString();
    const hasName = payload.name !== '' || payload.surname !== '';
    guests.value = guests.value.map((g) => {
        if (g.guest_group_id !== payload.id || !g.is_primary) return g;
        return {
            ...g,
            name: payload.name,
            surname: payload.surname,
            language: payload.language,
            mobile: payload.mobile || null,
            name_status: hasName ? 'known' : 'pending',
            updated_at: now,
        };
    });
};
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
                :groups="groups"
                @delete-group="handleDeleteGroup"
                @delete-companion="handleDeleteCompanion"
                @create-group="handleCreateGroup"
                @create-companion="handleCreateCompanion"
                @update-group="handleUpdateGroup"
            />
        </div>
    </AppLayout>
</template>
