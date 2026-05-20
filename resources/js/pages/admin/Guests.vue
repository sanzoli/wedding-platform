<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { buildGuestGroupViews } from '@/lib/mock/guest-list';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const trans = usePage().props.trans.guest_list as Record<string, string>;
trans.page_title ??= 'Guest list';
trans.page_description ??=
    'Manage the guest groups for the Colombia → Brasil wedding.';
trans.meta_guests_one ??= 'GUEST';
trans.meta_guests_other ??= 'GUESTS';
trans.meta_groups_one ??= 'GROUP';
trans.meta_groups_other ??= 'GROUPS';
trans.meta_attention_one ??= 'NEEDS ATTENTION';
trans.meta_attention_other ??= 'NEED ATTENTION';

const breadcrumbs: BreadcrumbItem[] = [
    { title: trans.page_title, href: '/admin/guests' },
];

const groups = computed(() => buildGuestGroupViews());

const totals = computed(() => {
    const list = groups.value;
    return {
        guests: list.reduce((acc, g) => acc + g.totalMembers, 0),
        groups: list.length,
        attention: list.filter((g) => g.needsAttention).length,
    };
});

const strip = computed(() => {
    const { guests, groups: groupCount, attention } = totals.value;
    const parts = [
        `${guests} ${guests === 1 ? trans.meta_guests_one : trans.meta_guests_other}`,
        `${groupCount} ${groupCount === 1 ? trans.meta_groups_one : trans.meta_groups_other}`,
    ];
    if (attention > 0) {
        parts.push(
            `${attention} ${attention === 1 ? trans.meta_attention_one : trans.meta_attention_other}`,
        );
    }
    return parts.join(' · ');
});
</script>

<template>
    <Head :title="trans.page_title" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 px-6 py-6">
            <header class="space-y-3">
                <Heading
                    :title="trans.page_title"
                    :description="trans.page_description"
                />
                <p
                    class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                >
                    {{ strip }}
                </p>
            </header>

            <!-- Table renders in the next commit. -->
        </div>
    </AppLayout>
</template>
