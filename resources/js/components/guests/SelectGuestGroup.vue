<script setup lang="ts">
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { useForm, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { computed, ref } from 'vue';
import { ArrowRight, Check, Search, X } from 'lucide-vue-next';
import { Guest, SelectableGroup } from '@/types/guests';
import {
    ComboboxAnchor,
    ComboboxContent,
    ComboboxEmpty,
    ComboboxInput,
    ComboboxItem,
    ComboboxItemIndicator,
    ComboboxRoot,
    ComboboxViewport,
} from 'reka-ui';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { change } from '@/actions/App/Http/Controllers/GuestGroupController';
import GroupAvatarCard from '@/components/guests/GroupAvatarCard.vue';

const props = defineProps<{
    guest: Guest;
}>();

const form = useForm({});
const selectedGroup = ref(<SelectableGroup>{});

const groups = computed<SelectableGroup[]>(() => usePage().props.selectableGroups);
const currentGroup = computed(() => groups.value.find(g => g.id === props.guest.group_id));

const open = ref(false)
const submit = () =>
    form.submit(change({ guest: props.guest.id, group: selectedGroup.value.id }), {
        preserveScroll: true,
        onSuccess: () => open.value = false
    });
</script>

<template>
    <Dialog v-model:open="open">
        <DialogTrigger as-child>
            <slot name="trigger"></slot>
        </DialogTrigger>
        <DialogContent>
            <DialogHeader class="space-y-3">
                <DialogTitle>
                    <slot name="title"/>
                </DialogTitle>
                <DialogDescription>
                    Pick a destination group from the list and confirm the
                    transfer.
                </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="submit" class="space-y-4">
                <div class="grid gap-2 py-2">
                    <ComboboxRoot class="relative" v-model="selectedGroup">
                        <ComboboxAnchor
                            class="relative flex h-9.5 w-full items-center gap-2 rounded-[10px] border border-border/60 bg-card pr-9 pl-9 shadow-none transition-colors focus-within:border-ring focus-within:ring-2 focus-within:ring-ring/20 dark:bg-input/30"
                        >
                            <Search
                                class="pointer-events-none absolute top-1/2 left-3 -translate-y-1/2 text-muted-foreground"
                                :size="15"
                                :stroke-width="2"
                            />
                            <ComboboxInput
                                name="group_id"
                                placeholder="Placeholder..."
                                :displayValue="(v) => v.full_name"
                                class="h-full w-full bg-transparent text-sm text-foreground outline-none placeholder:text-muted-foreground disabled:cursor-not-allowed disabled:opacity-50"
                            />
                            <button
                                v-show="selectedGroup.id"
                                type="button"
                                class="absolute top-1/2 right-3 -translate-y-1/2 cursor-pointer text-muted-foreground transition-colors hover:text-foreground disabled:cursor-not-allowed disabled:opacity-50"
                                @click="selectedGroup = <SelectableGroup>{}"
                            >
                                <X :size="14" :stroke-width="2" />
                            </button>
                        </ComboboxAnchor>

                        <ComboboxContent
                            position="popper"
                            :side-offset="4"
                            class="z-50 w-(--reka-popper-anchor-width) overflow-hidden rounded-[10px] border border-border/60 bg-popover text-popover-foreground shadow-md data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0"                        >
                            <ComboboxViewport class="max-h-48 overflow-y-auto p-1">
                                <ComboboxEmpty class="px-2 py-3 text-center text-sm text-muted-foreground">
                                    Group not found
                                </ComboboxEmpty>

                                <ComboboxItem
                                    v-for="group in groups"
                                    :key="group.id"
                                    :value="group"
                                    :disabled="group.id === guest.group_id"
                                    class="relative flex w-full cursor-pointer items-center rounded-sm px-2 py-1.5 text-sm outline-none select-none data-highlighted:bg-accent data-highlighted:text-accent-foreground data-disabled:opacity-60 data-disabled:hover:cursor-not-allowed"
                                >
                                    <ComboboxItemIndicator
                                        class="absolute left-0 inline-flex w-6.25 items-center justify-center"
                                    >
                                        <Check :size="12" />
                                    </ComboboxItemIndicator>
                                    <div
                                        class="flex w-full min-w-0 items-center gap-3"
                                    >
                                        <Avatar class="size-7">
                                            <AvatarFallback
                                                class="admin-type-action bg-secondary text-xs text-secondary-foreground"
                                            >{{ group.initials }}</AvatarFallback
                                            >
                                        </Avatar>
                                        <span class="truncate">{{ group.full_name }}</span>
                                    </div>
                                </ComboboxItem>
                            </ComboboxViewport>
                        </ComboboxContent>
                    </ComboboxRoot>
                </div>

                <div
                    class="flex min-h-36 items-center justify-center gap-3 py-2"
                >
                    <GroupAvatarCard :group="currentGroup"/>
                    <ArrowRight
                        :size="22"
                        class="text-muted-foreground"
                        :class="form.processing ? 'transfer-arrow-in-flight text-primary' : ''"
                    />
                    <GroupAvatarCard :group="selectedGroup"/>
                </div>

                <DialogFooter class="gap-2">
                    <DialogClose as-child>
                        <Button variant="secondary">Cancel</Button>
                    </DialogClose>

                    <Button type="submit" :disabled="form.processing || !selectedGroup?.id">
                        Change group
                    </Button>
                </DialogFooter>
            </Form>
        </DialogContent>
    </Dialog>
</template>

<style scoped>
.transfer-arrow-in-flight {
    animation: transfer-arrow-shuttle 1.2s ease-in-out infinite;
}
</style>
