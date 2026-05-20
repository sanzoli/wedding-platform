<script setup lang="ts">
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import {
    Tooltip,
    TooltipContent,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import type { Guest } from '@/types/guest-list';
import { usePage } from '@inertiajs/vue3';
import { Trash2 } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    companion: Guest;
}>();

const emit = defineEmits<{
    delete: [];
}>();

const trans = usePage().props.trans.guest_list as Record<string, string>;
trans.pending_placeholder ??= 'Companion';
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
                <span v-else class="type-body text-foreground">{{
                    fullName
                }}</span>
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
                class="flex items-center justify-end gap-1 opacity-0 transition-opacity group-hover/row:opacity-100"
            >
                <Tooltip>
                    <TooltipTrigger as-child>
                        <Button
                            variant="ghost"
                            size="icon"
                            class="size-8 cursor-pointer hover:bg-destructive/10"
                            :aria-label="trans.action_delete"
                            @click="emit('delete')"
                        >
                            <Trash2 :size="16" class="text-destructive" />
                        </Button>
                    </TooltipTrigger>
                    <TooltipContent>{{
                        trans.action_delete
                    }}</TooltipContent>
                </Tooltip>
            </div>
        </td>
    </tr>
</template>
