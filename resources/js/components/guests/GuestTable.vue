<script setup lang="ts">
import Table from '@/components/admin/Table.vue';
import TableHeader from '@/components/admin/TableHeader.vue';
import IconButton from '@/components/IconButton.vue';
import { Pencil, Trash2 } from 'lucide-vue-next';
import { Guest } from '@/types/guests';

defineProps<{
    guests: Guest[];
}>();
</script>

<template>
    <Table>
        <template #header>
            <TableHeader>Name</TableHeader>
            <TableHeader>Language</TableHeader>
            <TableHeader>Mobile</TableHeader>
            <TableHeader class="w-30"></TableHeader>
        </template>

        <template #body>
            <tr
                class="group hidden transition-colors hover:bg-muted/30 md:table-row"
                v-for="guest in guests"
                :key="guest.id"
            >
                <td class="w-75 px-6 py-3 text-[15px] font-medium">
                    {{ guest.name }}
                </td>
                <td class="px-6 py-3">{{ guest.lang }}</td>
                <td class="px-6 py-3">{{ guest.mobile }}</td>
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
                            @click="$emit('delete')"
                            class="hover:bg-destructive/10 hover:text-destructive"
                            aria-label="delete"
                        >
                            <Trash2></Trash2>
                        </IconButton>
                    </div>
                </td>
            </tr>
        </template>
    </Table>
</template>
