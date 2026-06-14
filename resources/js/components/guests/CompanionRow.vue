<script setup lang="ts">
import CompanionEditor from '@/components/guests/CompanionEditor.vue';
import SelectGuestGroup from '@/components/guests/SelectGuestGroup.vue';
import HighlightableText from '@/components/HighlightableText.vue';
import IconButton from '@/components/IconButton.vue';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import {
    deleteGuest,
    leaveGroup,
    updateGuest,
} from '@/composables/admin/useGuest';
import { Guest } from '@/types/guests';
import { InertiaForm } from '@inertiajs/vue3';
import { ArrowUpDown, CornerLeftUp, Pencil, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';

defineProps<{
    companion: Guest;
    query?: string;
}>();

const editing = ref(false);
const update = (form: InertiaForm<Guest>) =>
    updateGuest(form, { onSuccess: () => (editing.value = false) });
</script>

<template>
    <CompanionEditor
        v-if="editing"
        :companion
        @save="update"
        @close="editing = false"
    />
    <tr v-else class="group/row transition-colors hover:bg-muted/30">
        <td class="py-2 pr-4 pl-12">
            <div class="flex items-center gap-3 border-l border-border/40 pl-4">
                <Avatar>
                    <AvatarFallback
                        class="admin-type-action bg-muted text-muted-foreground"
                    >
                        {{ companion.initials }}
                    </AvatarFallback>
                </Avatar>
                <div class="type-body text-foreground">
                    <HighlightableText
                        :text="companion.full_name"
                        :query
                    ></HighlightableText>
                </div>
            </div>
        </td>
        <td class="px-4 py-2"></td>
        <td class="px-4 py-2 text-center">
            {{ companion.flag }}
        </td>
        <td
            class="admin-type-data px-4 py-2 text-center whitespace-nowrap text-muted-foreground"
        >
            <HighlightableText
                :text="companion.mobile"
                :query
            ></HighlightableText>
        </td>
        <td class="px-4 py-3">
            <div
                class="flex items-center justify-center gap-1 opacity-100 transition-opacity sm:opacity-0 sm:group-focus-within/row:opacity-100 sm:group-hover/row:opacity-100"
            >
                <IconButton
                    v-if="companion.full_name"
                    @click="leaveGroup(companion)"
                    :data-test="'companion-leave-group-button-' + companion.id"
                    class="hover:bg-muted hover:text-foreground"
                    aria-label="edit"
                >
                    <CornerLeftUp></CornerLeftUp>
                </IconButton>
                <SelectGuestGroup v-if="companion.full_name" :guest="companion">
                    <template #title>Change group?</template>
                    <template #trigger>
                        <IconButton
                            :data-test="
                                'companion-change-group-button-' + companion.id
                            "
                            class="hover:bg-muted hover:text-foreground"
                            aria-label="edit"
                        >
                            <ArrowUpDown></ArrowUpDown>
                        </IconButton>
                    </template>
                </SelectGuestGroup>
                <IconButton
                    @click="editing = true"
                    :data-test="'companion-edit-button-' + companion.id"
                    class="hover:bg-primary/10 hover:text-primary"
                    aria-label="edit"
                >
                    <Pencil></Pencil>
                </IconButton>
                <IconButton
                    @click="deleteGuest(companion)"
                    class="hover:bg-destructive/10 hover:text-destructive"
                    :data-test="'companion-delete-button-' + companion.id"
                    aria-label="delete"
                >
                    <Trash2></Trash2>
                </IconButton>
            </div>
        </td>
    </tr>
</template>
