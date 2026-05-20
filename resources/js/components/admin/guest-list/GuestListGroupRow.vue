<script setup lang="ts">
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import type { GuestGroupView } from '@/types/guest-list';
import { usePage } from '@inertiajs/vue3';
import { ChevronDown, CircleAlert } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    group: GuestGroupView;
}>();

const trans = usePage().props.trans.guest_list as Record<string, string>;
trans.companions_one ??= 'companion';
trans.companions_other ??= 'companions';
trans.pending_companions_one ??= 'pending companion';
trans.pending_companions_other ??= 'pending companions';

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

    const parts = [
        `${total} ${total === 1 ? trans.companions_one : trans.companions_other}`,
    ];

    const pending = props.group.pendingCompanions;
    if (pending > 0) {
        parts.push(
            `${pending} ${pending === 1 ? trans.pending_companions_one : trans.pending_companions_other}`,
        );
    }

    return parts.join(' · ');
});
</script>

<template>
    <tr class="group/row transition-colors hover:bg-muted/30">
        <td class="px-4 py-3">
            <div class="flex items-start gap-2">
                <span
                    v-if="hasCompanions"
                    class="mt-1.5 inline-flex size-5 items-center justify-center text-muted-foreground"
                    aria-hidden="true"
                >
                    <ChevronDown :size="16" />
                </span>
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
                            {{ subline }}
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
                <!-- Hover action buttons land in commit 4. -->
            </div>
        </td>
    </tr>
</template>
