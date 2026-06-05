<script setup lang="ts">
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { getInitials } from '@/composables/useInitials';
import { User } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    name?: string;
    surname?: string;
}>();

const fullName = computed(() =>
    `${props.name ?? ''} ${props.surname ?? ''}`.trim(),
);

const initials = computed(() => getInitials(fullName.value) || '?');

const isEmpty = computed(() => fullName.value.length === 0);
</script>

<template>
    <div
        class="flex h-36 w-36 flex-col items-center justify-center gap-3 rounded-md border bg-card px-3"
    >
        <Avatar v-if="!isEmpty" class="size-12">
            <AvatarFallback
                class="bg-secondary text-secondary-foreground text-base"
                >{{ initials }}</AvatarFallback
            >
        </Avatar>
        <Avatar v-else class="size-12">
            <AvatarFallback
                class="border border-dashed border-border bg-muted/40 text-muted-foreground"
            >
                <User :size="20" />
            </AvatarFallback>
        </Avatar>
        <span
            :class="[
                'type-body line-clamp-1 w-full text-center text-sm',
                isEmpty
                    ? 'text-muted-foreground italic'
                    : 'font-medium text-foreground',
            ]"
        >
            {{ isEmpty ? '—' : fullName }}
        </span>
    </div>
</template>
