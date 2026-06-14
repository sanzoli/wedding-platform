<script setup lang="ts">
import PrimaryEditor from '@/components/guests/PrimaryEditor.vue';
import HighlightableText from '@/components/HighlightableText.vue';
import IconButton from '@/components/IconButton.vue';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import {
    deleteGuest,
    splitGroup,
    updateGuest,
} from '@/composables/admin/useGuest';
import { Guest } from '@/types/guests';
import { InertiaForm } from '@inertiajs/vue3';
import {
    CornerDownRight,
    Pencil,
    Split,
    Trash2,
    UserPlus,
} from 'lucide-vue-next';
import { ref } from 'vue';
import SelectGuestGroup from '@/components/guests/SelectGuestGroup.vue';

defineEmits(['addCompanion']);
defineProps<{
    guest: Guest;
    query?: string;
    members?: number;
}>();

const editing = ref(false);
const update = (form: InertiaForm<Guest>) =>
    updateGuest(form, { onSuccess: () => (editing.value = false) });
</script>

<template>
    <PrimaryEditor
        v-if="editing"
        :guest="guest"
        @save="update"
        @close="editing = false"
    ></PrimaryEditor>

    <tr v-else class="group/row transition-colors hover:bg-muted/30">
        <td class="px-4 py-3">
            <div class="flex min-w-0 flex-1 items-start gap-3">
                <Avatar>
                    <AvatarFallback
                        class="admin-type-action bg-secondary text-secondary-foreground"
                    >
                        {{ guest.initials }}
                    </AvatarFallback>
                </Avatar>
                <div class="min-w-0 flex-1">
                    <HighlightableText
                        :text="guest.full_name"
                        :query
                    ></HighlightableText>
                </div>
            </div>
        </td>
        <td class="admin-type-data px-4 py-3 text-center">
            {{ members && members > 1 ? members : '' }}
        </td>
        <td class="admin-type-data px-6 py-3 text-center">
            {{ guest.flag }}
        </td>
        <td
            class="admin-type-data px-4 py-3 text-center whitespace-nowrap text-muted-foreground"
        >
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
                    v-if="members && members > 1"
                    @click="splitGroup(guest)"
                    :data-test="'split-guest-group-button-' + guest.id"
                    aria-label="Split Guest Group"
                >
                    <Split></Split>
                </IconButton>
                <SelectGuestGroup v-else :guest="guest">
                    <template #title>Add to group?</template>
                    <template #trigger>
                        <IconButton
                            :data-test="
                                'primary-add-to-group-button-' + guest.id
                            "
                            class="hover:bg-muted hover:text-foreground"
                            aria-label="edit"
                        >
                            <CornerDownRight></CornerDownRight>
                        </IconButton>
                    </template>
                </SelectGuestGroup>
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
