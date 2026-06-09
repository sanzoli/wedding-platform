<script setup lang="ts">
import HighlightableText from '@/components/HighlightableText.vue';
import { Pencil, Trash2, UserPlus } from 'lucide-vue-next';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import PrimaryEditor from '@/components/guests/PrimaryEditor.vue';
import IconButton from '@/components/IconButton.vue';
import { Guest } from '@/types/guests';
import { ref } from 'vue';
import { InertiaForm } from '@inertiajs/vue3';
import { deleteGuest, updateGuest } from '@/composables/admin/useGuest';

defineEmits(['addCompanion']);
defineProps<{
    guest: Guest;
    query?: string;
    members?: number;
}>();

const editing = ref(false);
const update = (form: InertiaForm<Guest>) => updateGuest(form, { onSuccess: () => (editing.value = false) });
</script>

<template>
    <PrimaryEditor v-if="editing" :guest="guest" @save="update" @close="editing = false"></PrimaryEditor>

    <tr v-else class="group/row transition-colors hover:bg-muted/30">
        <td class="px-4 py-3">
            <div class="flex min-w-0 flex-1 items-start gap-3">
                <Avatar>
                    <AvatarFallback class="admin-type-action bg-secondary text-secondary-foreground">
                        {{ guest.initials }}
                    </AvatarFallback>
                </Avatar>
                <div class="min-w-0 flex-1">
                    <HighlightableText :text="guest.full_name" :query></HighlightableText>
                </div>
            </div>
        </td>
        <td class="admin-type-data px-4 py-3 text-center">{{ members && members > 1 ? members : '' }}</td>
        <td class="admin-type-data px-6 py-3 text-center">
            {{ guest.flag }}
        </td>
        <td class="admin-type-data px-4 py-3 text-center whitespace-nowrap text-muted-foreground">
            <HighlightableText :text="guest.mobile" :query></HighlightableText>
        </td>
        <td class="px-4 py-3">
            <div
                class="flex items-center justify-center gap-1 opacity-100 transition-opacity sm:opacity-0 sm:group-focus-within/row:opacity-100 sm:group-hover/row:opacity-100"
            >
                <IconButton
                    @click="$emit('addCompanion')"
                    :data-test="'guest-add-companion-button-' + guest.id"
                    aria-label="Add Companion"
                >
                    <UserPlus></UserPlus>
                </IconButton>
                <IconButton
                    @click="editing = true"
                    :data-test="'guest-edit-button-' + guest.id"
                    class="hover:bg-primary/10 hover:text-primary"
                    aria-label="edit"
                >
                    <Pencil></Pencil>
                </IconButton>
                <IconButton
                    @click="deleteGuest(guest)"
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
