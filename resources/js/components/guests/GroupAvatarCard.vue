<script setup lang="ts">
import { SelectableGroup } from '@/types/guests';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { User } from 'lucide-vue-next';

defineProps<{
    group?: SelectableGroup;
}>();
</script>

<template>
    <div
        class="flex h-36 w-36 flex-col items-center justify-center gap-3 rounded-md border bg-card px-3"
    >
        <Avatar v-if="group?.id" class="size-12">
            <AvatarFallback
                class="bg-secondary text-base text-secondary-foreground"
                >{{ group.initials }}</AvatarFallback
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
                group
                    ? 'text-muted-foreground italic'
                    : 'font-medium text-foreground',
            ]"
        >
            {{ group?.full_name ?? '—' }}
        </span>
    </div>
</template>
