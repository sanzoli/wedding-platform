<script setup lang="ts">
import IconButton from '@/components/admin/IconButton.vue';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { getInitials } from '@/composables/useInitials';
import type { CompanionView } from '@/types/guests';
import { usePage } from '@inertiajs/vue3';
import { Pencil, Trash2 } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    companion: CompanionView;
}>();

const emit = defineEmits<{
    edit: [];
    delete: [];
}>();

const trans = usePage().props.trans.guests as Record<string, string>;

const initials = computed(() => {
    if (props.companion.isPending) return '?';
    const { name, surname } = props.companion;
    return getInitials(`${name} ${surname}`.trim()) || '?';
});

const fullName = computed(() =>
    `${props.companion.name} ${props.companion.surname}`.trim(),
);
</script>

<template>
    <tr class="group/row transition-colors hover:bg-muted/30">
        <td class="w-px py-2 pr-2 pl-4"></td>
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
                    v-if="companion.isPending"
                    class="type-body text-muted-foreground italic"
                    >{{ trans.pending_placeholder }}</span
                >
                <span v-else class="type-body text-foreground">{{
                    fullName
                }}</span>
            </div>
        </td>
        <td class="px-4 py-2"></td>
        <td
            class="admin-type-data px-4 py-2 text-center text-muted-foreground uppercase"
        >
            {{ companion.language }}
        </td>
        <td
            class="admin-type-data px-4 py-2 text-center whitespace-nowrap text-muted-foreground"
        >
            <template v-if="companion.mobile">{{
                companion.mobile
            }}</template>
        </td>
        <td class="px-4 py-2">
            <div
                class="flex items-center justify-center gap-1 opacity-100 transition-opacity sm:opacity-0 sm:group-hover/row:opacity-100 sm:group-focus-within/row:opacity-100"
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
