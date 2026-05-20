<script setup lang="ts">
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import {
    Tooltip,
    TooltipContent,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import type { GuestGroupView } from '@/types/guest-list';
import { usePage } from '@inertiajs/vue3';
import {
    ChevronDown,
    ChevronRight,
    CircleAlert,
    Trash2,
} from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    group: GuestGroupView;
    expanded: boolean;
}>();

const emit = defineEmits<{
    toggle: [];
    delete: [];
}>();

const trans = usePage().props.trans.guest_list as Record<string, string>;
trans.companions_one ??= 'companion';
trans.companions_other ??= 'companions';
trans.pending_one ??= 'pending';
trans.pending_other ??= 'pending';
trans.action_delete ??= 'Delete';

const initials = computed(() => {
    const first = props.group.primary.name.charAt(0).toUpperCase();
    const last = props.group.primary.surname.charAt(0).toUpperCase();
    return `${first}${last}` || '?';
});

const fullName = computed(() =>
    `${props.group.primary.name} ${props.group.primary.surname}`.trim(),
);

const hasCompanions = computed(() => props.group.companions.length > 0);

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
                        expanded ? 'Collapse group' : 'Expand group'
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
                        <p class="type-body text-foreground">{{ fullName }}</p>
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
            <template v-if="group.primary.mobile">{{
                group.primary.mobile
            }}</template>
            <span v-else>—</span>
        </td>
        <td class="px-4 py-3">
            <div class="flex items-center justify-end gap-1">
                <span
                    v-if="group.needsAttention"
                    class="inline-flex size-8 items-center justify-center text-warning"
                    aria-label="Necesita atención"
                >
                    <CircleAlert :size="16" />
                </span>

                <div
                    class="flex items-center gap-1 opacity-0 transition-opacity group-hover/row:opacity-100"
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
                                <Trash2
                                    :size="16"
                                    class="text-destructive"
                                />
                            </Button>
                        </TooltipTrigger>
                        <TooltipContent>{{
                            trans.action_delete
                        }}</TooltipContent>
                    </Tooltip>
                </div>
            </div>
        </td>
    </tr>
</template>
