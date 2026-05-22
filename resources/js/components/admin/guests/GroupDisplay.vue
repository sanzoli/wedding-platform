<script setup lang="ts">
import IconButton from '@/components/admin/IconButton.vue';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import {
    Tooltip,
    TooltipContent,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import { getInitials } from '@/composables/useInitials';
import type { GuestGroupView } from '@/types/guests';
import { usePage } from '@inertiajs/vue3';
import {
    ChevronDown,
    ChevronRight,
    CircleAlert,
    Pencil,
    Trash2,
    UserPlus,
} from 'lucide-vue-next';
import { computed } from 'vue';
import Highlight from './Highlight.vue';

const props = defineProps<{
    group: GuestGroupView;
    expanded: boolean;
    search: string;
}>();

const emit = defineEmits<{
    toggle: [];
    edit: [];
    delete: [];
    'add-companion': [];
}>();

const trans = usePage().props.trans.guests as Record<string, string>;

const initials = computed(() => {
    const { name, surname } = props.group.primary;
    return getInitials(`${name} ${surname}`.trim()) || '?';
});

const fullName = computed(() =>
    `${props.group.primary.name} ${props.group.primary.surname}`.trim(),
);

const hasCompanions = computed(() => props.group.companions.length > 0);

const attentionText = computed(() => {
    const reasons: string[] = [];
    if (!props.group.primary.mobile?.trim()) {
        reasons.push(trans.attention_no_mobile);
    }
    const pending = props.group.pendingCompanions;
    if (pending > 0) {
        const template =
            pending === 1
                ? trans.attention_pending_one
                : trans.attention_pending_other;
        reasons.push(template.replace(':count', String(pending)));
    }
    return reasons.join(' · ');
});

const subline = computed(() => {
    const total = props.group.companions.length;
    if (total === 0) return null;

    const totalLabel =
        total === 1 ? trans.companions_one : trans.companions_other;
    const pending = props.group.pendingCompanions;
    const pendingLabel =
        pending === 1 ? trans.pending_one : trans.pending_other;

    return {
        total: `${total} ${totalLabel}`,
        pending: pending > 0 ? `(${pending} ${pendingLabel})` : null,
    };
});
</script>

<template>
    <tr class="group/row transition-colors hover:bg-muted/30">
        <td class="px-4 py-3">
            <div class="flex items-start gap-2">
                <button
                    v-if="hasCompanions"
                    type="button"
                    class="mt-1.5 inline-flex size-5 cursor-pointer items-center justify-center rounded text-muted-foreground hover:bg-muted"
                    :aria-expanded="expanded"
                    :aria-label="
                        expanded ? trans.collapse_group : trans.expand_group
                    "
                    @click="emit('toggle')"
                >
                    <ChevronDown v-if="expanded" :size="16" />
                    <ChevronRight v-else :size="16" />
                </button>
                <span v-else class="inline-block size-5" aria-hidden="true" />

                <div class="flex min-w-0 flex-1 items-start gap-3">
                    <Avatar>
                        <AvatarFallback
                            class="admin-type-action bg-secondary text-secondary-foreground"
                            >{{ initials }}</AvatarFallback
                        >
                    </Avatar>
                    <div class="min-w-0 flex-1">
                        <p class="type-body text-foreground">
                            <Highlight :text="fullName" :query="search" />
                        </p>
                        <p
                            v-if="subline"
                            class="text-[0.78em] leading-[1.4] text-muted-foreground/70"
                        >
                            <span>{{ subline.total }}</span>
                            <span
                                v-if="subline.pending"
                                class="ml-1 text-warning"
                                >{{ subline.pending }}</span
                            >
                        </p>
                    </div>
                </div>
            </div>
        </td>
        <td class="admin-type-data px-4 py-3">{{ group.totalMembers }}</td>
        <td
            class="admin-type-data px-4 py-3 text-muted-foreground uppercase"
        >
            {{ group.primary.language }}
        </td>
        <td
            class="admin-type-data px-4 py-3 whitespace-nowrap text-muted-foreground"
        >
            <template v-if="group.primary.mobile">
                <Highlight :text="group.primary.mobile" :query="search" />
            </template>
            <span v-else>—</span>
        </td>
        <td class="px-4 py-3">
            <div
                class="flex items-center justify-center gap-1 opacity-0 transition-opacity group-hover/row:opacity-100 group-focus-within/row:opacity-100"
            >
                <IconButton
                    :label="trans.action_edit"
                    variant="muted"
                    @click="emit('edit')"
                >
                    <Pencil :size="16" />
                </IconButton>
                <IconButton
                    :label="trans.action_add_companion"
                    variant="primary"
                    @click="emit('add-companion')"
                >
                    <UserPlus :size="16" />
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
        <td class="w-px py-3 pr-4 pl-2">
            <span class="inline-flex size-8 items-center justify-center">
                <Tooltip v-if="group.needsAttention">
                    <TooltipTrigger as-child>
                        <span
                            tabindex="0"
                            class="text-warning"
                            :aria-label="attentionText"
                        >
                            <CircleAlert :size="16" />
                        </span>
                    </TooltipTrigger>
                    <TooltipContent>{{ attentionText }}</TooltipContent>
                </Tooltip>
            </span>
        </td>
    </tr>
</template>
