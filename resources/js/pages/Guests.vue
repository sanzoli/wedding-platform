<script setup lang="ts">
import { destroy, index } from '@/actions/App/Http/Controllers/GuestController';
import SearchBar from '@/components/admin/SearchBar.vue';
import Table from '@/components/admin/Table.vue';
import TableHeader from '@/components/admin/TableHeader.vue';
import Heading from '@/components/Heading.vue';
import HighlightableText from '@/components/HighlightableText.vue';
import IconButton from '@/components/IconButton.vue';
import { confirmDelete } from '@/composables/admin/useAlert';
import { useQueryOptions } from '@/composables/useQueryOptions';
import AppLayout from '@/layouts/AppLayout.vue';
import { QueryOptions } from '@/types';
import { Guest } from '@/types/guests';
import { Head, router } from '@inertiajs/vue3';
import { Pencil, Trash2 } from 'lucide-vue-next';

const props = defineProps<{
    filters: QueryOptions;
    guests: {
        data: Guest[];
    };
}>();

const queryOptions = useQueryOptions(index.url(), props.filters);

const deleteItem = (id: number) =>
    confirmDelete(() => router.delete(destroy.url(id), { only: ['guests'], preserveScroll: true }));
</script>

<template>
    <Head title="Guests" />

    <AppLayout>
        <div class="flex h-full flex-1 flex-col px-6 py-6">
            <Heading title="Guest List"></Heading>
            <Table>
                <template #toolbar>
                    <div class="flex items-center gap-3">
                        <SearchBar v-model:search-value="queryOptions.search" />
                    </div>
                </template>
                <template #header>
                    <TableHeader>Name</TableHeader>
                    <TableHeader>Language</TableHeader>
                    <TableHeader>Mobile</TableHeader>
                    <TableHeader class="w-30"></TableHeader>
                </template>

                <template #body>
                    <tr
                        class="group hidden transition-colors hover:bg-muted/30 md:table-row"
                        v-for="guest in guests.data"
                        :key="guest.id"
                    >
                        <td class="w-75 px-6 py-3 text-[15px] font-medium">
                            <HighlightableText :text="guest.name" :query="queryOptions.search"></HighlightableText>
                        </td>
                        <td class="px-6 py-3">{{ guest.lang }}</td>
                        <td class="px-6 py-3">
                            <HighlightableText :text="guest.mobile" :query="queryOptions.search"></HighlightableText>
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-1.5">
                                <IconButton
                                    @click="$emit('edit')"
                                    class="hover:bg-primary/10 hover:text-primary"
                                    aria-label="edit"
                                >
                                    <Pencil></Pencil>
                                </IconButton>
                                <IconButton
                                    @click="deleteItem(guest.id)"
                                    class="hover:bg-destructive/10 hover:text-destructive"
                                    :data-test="'guest-delete-button-' + guest.id"
                                    aria-label="delete"
                                >
                                    <Trash2></Trash2>
                                </IconButton>
                            </div>
                        </td>
                    </tr>
                </template>
            </Table>
        </div>
    </AppLayout>
</template>
