<script setup lang="ts">
import IconButton from '@/components/IconButton.vue';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import {
    Tooltip,
    TooltipContent,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import { getInitials } from '@/composables/useInitials';
import type { CompanionView } from '@/types/guests';
import { usePage } from '@inertiajs/vue3';
import { ArrowUpDown, CornerLeftUp, Pencil, Trash2 } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    companion: CompanionView;
    canChangeGroup: boolean;
}>();

const emit = defineEmits<{
    edit: [];
    delete: [];
    'change-group': [];
    'leave-group': [];
}>();

const trans = usePage().props.trans.guests as Record<string, string>;

const fullName = computed(() =>
    `${props.companion.name} ${props.companion.surname}`.trim(),
);

const initials = computed(() =>
    props.companion.isPending ? '?' : getInitials(fullName.value) || '?',
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
                <template v-if="!companion.isPending">
                    <Tooltip v-if="canChangeGroup">
                        <TooltipTrigger as-child>
                            <IconButton
                                class="cursor-pointer hover:bg-muted hover:text-foreground"
                                :aria-label="trans.action_change_group"
                                @click="emit('change-group')"
                            >
                                <ArrowUpDown :size="16" />
                            </IconButton>
                        </TooltipTrigger>
                        <TooltipContent>{{
                            trans.action_change_group
                        }}</TooltipContent>
                    </Tooltip>
                    <Tooltip>
                        <TooltipTrigger as-child>
                            <IconButton
                                class="cursor-pointer hover:bg-muted hover:text-foreground"
                                :aria-label="trans.action_leave_group"
                                @click="emit('leave-group')"
                            >
                                <CornerLeftUp :size="16" />
                            </IconButton>
                        </TooltipTrigger>
                        <TooltipContent>{{
                            trans.action_leave_group
                        }}</TooltipContent>
                    </Tooltip>
                </template>
                <Tooltip>
                    <TooltipTrigger as-child>
                        <IconButton
                            class="cursor-pointer hover:bg-muted hover:text-foreground"
                            :aria-label="trans.action_edit"
                            @click="emit('edit')"
                        >
                            <Pencil :size="16" class="text-primary" />
                        </IconButton>
                    </TooltipTrigger>
                    <TooltipContent>{{ trans.action_edit }}</TooltipContent>
                </Tooltip>
                <Tooltip>
                    <TooltipTrigger as-child>
                        <IconButton
                            class="cursor-pointer hover:bg-destructive/10 dark:hover:bg-destructive/25"
                            :aria-label="trans.action_delete"
                            @click="emit('delete')"
                        >
                            <Trash2 :size="16" class="text-destructive" />
                        </IconButton>
                    </TooltipTrigger>
                    <TooltipContent>{{ trans.action_delete }}</TooltipContent>
                </Tooltip>
            </div>
        </td>
        <td class="w-px py-2 pr-2.5 pl-2"></td>
    </tr>
</template>
