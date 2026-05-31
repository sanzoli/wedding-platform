<script setup lang="ts">
import { index } from '@/actions/App/Http/Controllers/GuestController';
import AddButton from '@/components/admin/AddButton.vue';
import SearchBar from '@/components/admin/SearchBar.vue';
import Table from '@/components/admin/Table.vue';
import TableHeader from '@/components/admin/TableHeader.vue';
import GuestEditor from '@/components/guests/GuestEditor.vue';
import GroupRow from '@/components/guests/GroupRow.vue';
import Heading from '@/components/Heading.vue';
import { useSortOptions } from '@/composables/admin/useSortOptions';
import { useQueryOptions } from '@/composables/useQueryOptions';
import AppLayout from '@/layouts/AppLayout.vue';
import { QueryOptions } from '@/types';
import { Guest, GuestGroup } from '@/types/guests';
import { Head, InertiaForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { storeGuest } from '@/composables/admin/useGuest';

const props = defineProps<{
    filters: QueryOptions;
    guestGroups: {
        data: GuestGroup[];
    };
}>();

const queryOptions = useQueryOptions(index.url(), props.filters);
const [sortOptions, sort] = useSortOptions(queryOptions);
const adding = ref(false);

const addGuest = (form: InertiaForm<Guest>) => storeGuest(form, { onSuccess: () => (adding.value = false) });
</script>

<template>
    <Head title="Guests" />
    <AppLayout>
        <div class="flex h-full flex-1 flex-col px-6 py-6">
            <Heading title="Guest List"></Heading>

            <Table @add-item="adding = true" :search="queryOptions.search">
                <template #toolbar>
                    <div class="flex items-center gap-3">
                        <SearchBar v-model:search-value="queryOptions.search" />
                        <div class="ml-auto">
                            <AddButton @add="adding = true">Add Guest</AddButton>
                        </div>
                    </div>
                </template>

                <template #header>
                    <TableHeader sort-by="first_name" :sortOptions @update:sort-options="sort" class="text-left">
                        Name
                    </TableHeader>
                    <TableHeader>Members</TableHeader>
                    <TableHeader sort-by="lang" :sortOptions @update:sort-options="sort">Language</TableHeader>
                    <TableHeader sort-by="mobile" :sortOptions @update:sort-options="sort">Mobile</TableHeader>
                    <TableHeader class="w-30"></TableHeader>
                </template>

                <template #body>
                    <GuestEditor v-if="adding" @save="addGuest" @close="adding = false"></GuestEditor>
                    <GroupRow
                        v-for="group in guestGroups.data"
                        :key="group.id"
                        :group
                        :query="queryOptions.search">
                    </GroupRow>
                </template>
            </Table>
        </div>
    </AppLayout>
</template>
