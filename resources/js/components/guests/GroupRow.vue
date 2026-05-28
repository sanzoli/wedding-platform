<script setup lang="ts">
import { destroy } from '@/actions/App/Http/Controllers/GuestController';
import GuestEditor from '@/components/guests/GuestEditor.vue';
import HighlightableText from '@/components/HighlightableText.vue';
import IconButton from '@/components/IconButton.vue';
import { confirmDelete } from '@/composables/admin/useAlert';
import { GuestGroup } from '@/types/guests';
import { router, usePage } from '@inertiajs/vue3';
import { Pencil, Trash2 } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';

const props = defineProps<{
    group: GuestGroup;
    query?: string;
}>();

const editing = ref(false);

const languages = usePage().props.languages;
const name = computed<string>(() =>
    ((props.group.primary.first_name ?? '') + ' ' + (props.group.primary.last_name ?? '')).trim(),
);
const initials = computed<string>(
    () => (props.group.primary.first_name?.at(0) ?? '') + (props.group.primary.last_name?.at(0) ?? ''),
);
const flag = computed<string>(() => (props.group.primary.lang ? languages[props.group.primary.lang].flag : '-'));

const deleteGuest = () =>
    confirmDelete(() =>
        router.delete(destroy.url(props.group.primary), { only: ['guestGroups'], preserveScroll: true }),
    );
</script>

<template>
    <GuestEditor v-if="editing" :guest="group.primary" @close="editing = false"></GuestEditor>
    <tr v-else class="group/row transition-colors hover:bg-muted/30">
        <td class="px-4 py-3">
            <div class="flex min-w-0 flex-1 items-start gap-3">
                <Avatar>
                    <AvatarFallback class="admin-type-action bg-secondary text-secondary-foreground">
                        {{ initials }}
                    </AvatarFallback>
                </Avatar>
                <div class="min-w-0 flex-1">
                    <HighlightableText :text="name" :query class="type-body text-foreground"></HighlightableText>
                </div>
            </div>
        </td>
        <td class="admin-type-data px-4 py-3 text-center">{{ group.count > 1 ? group.count : '' }}</td>
        <td class="admin-type-data px-6 py-3 text-center">{{ flag }}</td>
        <td class="admin-type-data px-4 py-3 text-center whitespace-nowrap text-muted-foreground">
            <HighlightableText :text="group.primary.mobile" :query></HighlightableText>
        </td>
        <td class="px-4 py-3">
            <div
                class="flex items-center justify-center gap-1 opacity-100 transition-opacity sm:opacity-0 sm:group-focus-within/row:opacity-100 sm:group-hover/row:opacity-100"
            >
                <IconButton
                    @click="editing = true"
                    :data-test="'guest-edit-button-' + group.primary.id"
                    class="hover:bg-primary/10 hover:text-primary"
                    aria-label="edit"
                >
                    <Pencil></Pencil>
                </IconButton>
                <IconButton
                    @click="deleteGuest"
                    class="hover:bg-destructive/10 hover:text-destructive"
                    :data-test="'guest-delete-button-' + group.primary.id"
                    aria-label="delete"
                >
                    <Trash2></Trash2>
                </IconButton>
            </div>
        </td>
    </tr>
</template>
