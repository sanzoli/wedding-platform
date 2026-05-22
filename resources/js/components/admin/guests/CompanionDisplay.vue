<script setup lang="ts">
import IconButton from '@/components/admin/IconButton.vue';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import type { Guest } from '@/types/guests';
import { usePage } from '@inertiajs/vue3';
import { Pencil, Trash2 } from 'lucide-vue-next';
import { computed } from 'vue';
import Highlight from './Highlight.vue';

const props = defineProps<{
    companion: Guest;
    search: string;
}>();

const emit = defineEmits<{
    edit: [];
    delete: [];
}>();

const trans = usePage().props.trans.guests as Record<string, string>;
trans.pending_placeholder ??= 'Companion';
trans.action_edit ??= 'Edit';
trans.action_delete ??= 'Delete';

const isPending = computed(
    () =>
        props.companion.name_status === 'pending' ||
        (!props.companion.name.trim() && !props.companion.surname.trim()),
);

const initials = computed(() => {
    if (isPending.value) return '?';
    const first = props.companion.name.charAt(0).toUpperCase();
    const last = props.companion.surname.charAt(0).toUpperCase();
    return `${first}${last}` || '?';
});

const fullName = computed(() =>
    `${props.companion.name} ${props.companion.surname}`.trim(),
);
</script>

<template>
    <tr class="group/row transition-colors hover:bg-muted/30">
        <td class="py-2 pr-4 pl-12">
            <div
                class="flex items-center gap-3 border-l border-border/40 pl-4"
            >
                <Avatar>
                    <AvatarFallback
                        class="admin-type-action bg-muted text-muted-foreground"
                        >{{ initials }}</AvatarFallback
                    >
                </Avatar>
                <span
                    v-if="isPending"
                    class="type-body text-muted-foreground italic"
                    >{{ trans.pending_placeholder }}</span
                >
                <span v-else class="type-body text-foreground">
                    <Highlight :text="fullName" :query="search" />
                </span>
            </div>
        </td>
        <td class="px-4 py-2"></td>
        <td
            class="admin-type-data px-4 py-2 text-muted-foreground uppercase"
        >
            {{ companion.language }}
        </td>
        <td class="px-4 py-2"></td>
        <td class="px-4 py-2">
            <div
                class="flex items-center justify-center gap-1 opacity-0 transition-opacity group-hover/row:opacity-100"
            >
                <IconButton
                    :label="trans.action_edit"
                    variant="muted"
                    @click="emit('edit')"
                >
                    <Pencil :size="16" />
                </IconButton>
                <IconButton
                    :label="trans.action_delete"
                    variant="destructive"
                    @click="emit('delete')"
                >
                    <Trash2 :size="16" class="text-destructive" />
                </IconButton>
            </div>
        </td>
        <td class="w-px py-2 pr-2.5 pl-2"></td>
    </tr>
</template>
