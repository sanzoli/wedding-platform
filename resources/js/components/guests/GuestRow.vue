<script setup lang="ts">
import { destroy } from '@/actions/App/Http/Controllers/GuestController';
import HighlightableText from '@/components/HighlightableText.vue';
import IconButton from '@/components/IconButton.vue';
import { confirmDelete } from '@/composables/admin/useAlert';
import { Guest } from '@/types/guests';
import { router, usePage } from '@inertiajs/vue3';
import { Pencil, Trash2 } from 'lucide-vue-next';

const props = defineProps<{
    guest: Guest;
    query?: string;
}>();

const languages = usePage().props.languages;

const deleteGuest = () =>
    confirmDelete(() => router.delete(destroy.url(props.guest), { only: ['guests'], preserveScroll: true }));
</script>

<template>
    <tr class="group hidden transition-colors hover:bg-muted/30 md:table-row">
        <td class="w-100 px-6 py-3 text-[15px] font-medium">
            <HighlightableText :text="guest.name" :query></HighlightableText>
        </td>
        <td class="px-6 py-3 text-center">
            {{ guest.lang ? languages[guest.lang].flag : '-' }}
        </td>
        <td class="px-6 py-3 text-center">
            <HighlightableText :text="guest.mobile" :query></HighlightableText>
        </td>
        <td class="px-6 py-3">
            <div class="flex items-center gap-1.5">
                <IconButton @click="$emit('edit')" class="hover:bg-primary/10 hover:text-primary" aria-label="edit">
                    <Pencil></Pencil>
                </IconButton>
                <IconButton
                    @click="deleteGuest"
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
