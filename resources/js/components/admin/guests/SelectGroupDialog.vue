<script setup lang="ts">
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { getInitials } from '@/composables/useInitials';
import type {
    SelectGroupDialogStatus,
    SelectGroupOption,
    SelectGroupSource,
} from '@/types/guests';
import { usePage } from '@inertiajs/vue3';
import { ArrowRight, Check, Search, TriangleAlert, X } from 'lucide-vue-next';
import {
    ComboboxAnchor,
    ComboboxContent,
    ComboboxInput,
    ComboboxItem,
    ComboboxRoot,
} from 'reka-ui';
import { computed, ref, watch } from 'vue';
import TransferCard from './TransferCard.vue';

const props = withDefaults(
    defineProps<{
        open: boolean;
        status?: SelectGroupDialogStatus;
        source: SelectGroupSource | null;
        groups: SelectGroupOption[];
    }>(),
    { status: 'idle' },
);

const emit = defineEmits<{
    'update:open': [value: boolean];
    confirm: [payload: { sourceGuestId: string; targetGroupId: string }];
}>();

const trans = usePage().props.trans.guests as Record<string, string>;

const searchTerm = ref('');
const selectedId = ref<string | undefined>(undefined);
const suggestionsOpen = ref(false);

const isIdle = computed(() => props.status === 'idle');
const isSubmitting = computed(() => props.status === 'submitting');
const isResult = computed(
    () => props.status === 'success' || props.status === 'error',
);

const normalize = (s: string): string =>
    s
        .normalize('NFD')
        .replace(/\p{Diacritic}/gu, '')
        .toLowerCase()
        .trim();

const filteredGroups = computed<SelectGroupOption[]>(() => {
    const query = normalize(searchTerm.value);
    if (!query) return props.groups;
    return props.groups.filter((g) => {
        const haystack = normalize(`${g.name} ${g.surname}`);
        return haystack.includes(query);
    });
});

const selectedGroup = computed<SelectGroupOption | null>(
    () => props.groups.find((g) => g.groupId === selectedId.value) ?? null,
);

const canConfirm = computed(
    () => selectedId.value !== undefined && isIdle.value,
);

watch(
    () => props.open,
    (isOpening) => {
        if (isOpening) {
            searchTerm.value = '';
            selectedId.value = undefined;
            suggestionsOpen.value = false;
        }
    },
);

watch(selectedId, (picked, previous) => {
    if (picked !== undefined && picked !== previous) {
        searchTerm.value = '';
        suggestionsOpen.value = false;
    }
});

watch(
    () => props.status,
    (status) => {
        if (status !== 'idle') suggestionsOpen.value = false;
    },
);

const onClearSearch = () => {
    searchTerm.value = '';
    suggestionsOpen.value = true;
};

const onConfirm = () => {
    const source = props.source;
    if (selectedId.value === undefined || !source) return;
    suggestionsOpen.value = false;
    emit('confirm', {
        sourceGuestId: source.guestId,
        targetGroupId: selectedId.value,
    });
};

const tryClose = (next: boolean) => {
    if (!next && isSubmitting.value) return;
    emit('update:open', next);
};

const displayValue = () => searchTerm.value;
</script>

<template>
    <Dialog :open="open" @update:open="tryClose">
        <DialogContent
            class="gap-3 sm:max-w-md"
            @close-auto-focus="(e: Event) => e.preventDefault()"
        >
            <DialogHeader>
                <DialogTitle>
                    <slot name="title" />
                </DialogTitle>
                <DialogDescription class="sr-only">{{
                    trans.select_group_dialog_description
                }}</DialogDescription>
            </DialogHeader>

            <Transition mode="out-in" name="content-swap">
                <div
                    v-if="isResult"
                    key="result"
                    class="flex min-h-60 flex-col items-center justify-center gap-3 py-4"
                    role="status"
                    aria-live="polite"
                >
                    <span
                        v-if="status === 'success'"
                        class="inline-flex size-14 items-center justify-center rounded-full bg-emerald-500/15 text-emerald-600 dark:text-emerald-400"
                    >
                        <Check :size="32" :stroke-width="2.5" />
                    </span>
                    <span
                        v-else
                        class="inline-flex size-14 items-center justify-center rounded-full bg-destructive/15 text-destructive"
                    >
                        <TriangleAlert :size="30" :stroke-width="2.5" />
                    </span>
                    <p
                        :class="[
                            'text-base font-medium',
                            status === 'success'
                                ? 'text-emerald-600 dark:text-emerald-400'
                                : 'text-destructive',
                        ]"
                    >
                        {{
                            status === 'success'
                                ? trans.select_group_success
                                : trans.select_group_error
                        }}
                    </p>
                </div>

                <div v-else key="working" class="flex flex-col gap-3">
                    <ComboboxRoot
                        v-model="selectedId"
                        :open="suggestionsOpen"
                        :ignore-filter="true"
                        :reset-search-term-on-select="false"
                        :reset-search-term-on-blur="false"
                        @update:open="suggestionsOpen = $event"
                    >
                        <ComboboxAnchor
                            class="relative flex h-[38px] w-full items-center gap-2 rounded-[10px] border border-border/60 bg-card pr-9 pl-9 shadow-none transition-colors focus-within:border-ring focus-within:ring-2 focus-within:ring-ring/20 dark:bg-input/30"
                        >
                            <Search
                                class="pointer-events-none absolute top-1/2 left-3 -translate-y-1/2 text-muted-foreground"
                                :size="15"
                                :stroke-width="2"
                            />
                            <ComboboxInput
                                v-model="searchTerm"
                                :display-value="displayValue"
                                :placeholder="trans.select_group_placeholder"
                                :disabled="isSubmitting"
                                class="h-full w-full bg-transparent text-sm text-foreground outline-none placeholder:text-muted-foreground disabled:cursor-not-allowed disabled:opacity-50"
                                @input="suggestionsOpen = true"
                                @click="suggestionsOpen = true"
                            />
                            <button
                                v-show="searchTerm"
                                type="button"
                                :disabled="status !== 'idle'"
                                class="absolute top-1/2 right-3 -translate-y-1/2 cursor-pointer text-muted-foreground transition-colors hover:text-foreground disabled:cursor-not-allowed disabled:opacity-50"
                                :aria-label="trans.delete_cancel"
                                @click="onClearSearch"
                            >
                                <X :size="14" :stroke-width="2" />
                            </button>
                        </ComboboxAnchor>
                        <ComboboxContent
                            position="popper"
                            :side-offset="4"
                            class="z-50 w-[var(--reka-popper-anchor-width)] overflow-hidden rounded-[10px] border border-border/60 bg-popover text-popover-foreground shadow-md data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0"
                        >
                            <div class="max-h-48 overflow-y-auto p-1">
                                <ComboboxItem
                                    v-for="group in filteredGroups"
                                    :key="group.groupId"
                                    :value="group.groupId"
                                    class="relative flex w-full cursor-pointer items-center rounded-sm px-2 py-1.5 text-sm outline-none select-none data-[highlighted]:bg-accent data-[highlighted]:text-accent-foreground"
                                >
                                    <div
                                        class="flex w-full min-w-0 items-center gap-3"
                                    >
                                        <Avatar class="size-7">
                                            <AvatarFallback
                                                class="admin-type-action bg-secondary text-xs text-secondary-foreground"
                                                >{{
                                                    getInitials(
                                                        `${group.name} ${group.surname}`.trim(),
                                                    ) || '?'
                                                }}</AvatarFallback
                                            >
                                        </Avatar>
                                        <span class="truncate"
                                            >{{ group.name }}
                                            {{ group.surname }}</span
                                        >
                                    </div>
                                </ComboboxItem>
                                <div
                                    v-if="filteredGroups.length === 0"
                                    class="px-2 py-3 text-center text-sm text-muted-foreground"
                                >
                                    {{ trans.select_group_no_matches }}
                                </div>
                            </div>
                        </ComboboxContent>
                    </ComboboxRoot>

                    <div
                        class="flex min-h-36 items-center justify-center gap-3 py-2"
                    >
                        <TransferCard
                            :name="source?.name ?? ''"
                            :surname="source?.surname ?? ''"
                        />
                        <ArrowRight
                            :size="22"
                            :class="[
                                'text-muted-foreground',
                                status === 'submitting' &&
                                    'transfer-arrow-in-flight text-primary',
                            ]"
                        />
                        <Transition mode="out-in" name="card-pop">
                            <TransferCard
                                v-if="selectedGroup"
                                :key="selectedGroup.groupId"
                                :name="selectedGroup.name"
                                :surname="selectedGroup.surname"
                            />
                            <TransferCard v-else key="empty" />
                        </Transition>
                    </div>

                    <div class="flex justify-end gap-2">
                        <Button
                            variant="outline"
                            class="dark:hover:text-foreground"
                            :disabled="isSubmitting"
                            @click="tryClose(false)"
                            >{{ trans.delete_cancel }}</Button
                        >
                        <Button :disabled="!canConfirm" @click="onConfirm">{{
                            trans.select_group_confirm
                        }}</Button>
                    </div>
                </div>
            </Transition>
        </DialogContent>
    </Dialog>
</template>

<style scoped>
.card-pop-enter-active {
    transition:
        opacity 250ms ease-out,
        transform 250ms cubic-bezier(0.34, 1.56, 0.64, 1);
}
.card-pop-leave-active {
    transition:
        opacity 150ms ease-in,
        transform 150ms ease-in;
}
.card-pop-enter-from {
    opacity: 0;
    transform: scale(0.85);
}
.card-pop-leave-to {
    opacity: 0;
    transform: scale(0.95);
}

.content-swap-enter-active {
    transition:
        opacity 220ms ease-out,
        transform 220ms ease-out;
}
.content-swap-leave-active {
    transition:
        opacity 180ms ease-in,
        transform 180ms ease-in;
}
.content-swap-enter-from {
    opacity: 0;
    transform: translateY(4px);
}
.content-swap-leave-to {
    opacity: 0;
    transform: translateY(-4px);
}

@keyframes transfer-arrow-shuttle {
    0%,
    100% {
        transform: translateX(0);
        opacity: 0.65;
    }
    50% {
        transform: translateX(14px);
        opacity: 1;
    }
}
.transfer-arrow-in-flight {
    animation: transfer-arrow-shuttle 1.2s ease-in-out infinite;
}
</style>
