<script setup lang="ts">
import { destroy } from '@/actions/App/Http/Controllers/GuestController';
import HighlightableText from '@/components/HighlightableText.vue';
import IconButton from '@/components/IconButton.vue';
import { confirmDelete } from '@/composables/admin/useAlert';
import { Guest } from '@/types/guests';
import { router, usePage } from '@inertiajs/vue3';
import { Pencil, Trash2 } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import GuestEditor from '@/components/guests/GuestEditor.vue';

const props = defineProps<{
    guest: Guest;
    query?: string;
}>();

const editing = ref(false);

const languages = usePage().props.languages;
const name = computed<string>(() => ((props.guest.first_name ?? '') + ' ' + (props.guest.last_name ?? '')).trim());
const flag = computed<string>(() => (props.guest.lang ? languages[props.guest.lang].flag : '-'));

const deleteGuest = () =>
    confirmDelete(() => router.delete(destroy.url(props.guest), { only: ['guests'], preserveScroll: true }));
</script>

<template>
    <GuestEditor v-if="editing" :guest @close="editing = false"></GuestEditor>
    <tr v-else class="group hidden transition-colors hover:bg-muted/30 md:table-row">
        <td class="w-100 px-6 py-3 text-[15px] font-medium">
            <HighlightableText :text="name" :query></HighlightableText>
        </td>
        <td class="px-6 py-3 text-center">{{ flag }}</td>
        <td class="px-6 py-3 text-center">
            <HighlightableText :text="guest.mobile" :query></HighlightableText>
        </td>
        <td class="px-6 py-3">
            <div class="flex items-center gap-1.5">
                <IconButton
                    @click="editing = true"
                    :data-test="'guest-edit-button-' + guest.id"
                    class="hover:bg-primary/10 hover:text-primary"
                    aria-label="edit"
                >
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
