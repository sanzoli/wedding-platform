<script setup lang="ts">
import Badge from '@/components/Badge.vue';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Category } from '@/types';

defineEmits<{
    select: [category: Category];
}>();

defineProps<{
    options: Category[];
}>();
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <button
                type="button"
                class="inline-flex min-h-11 items-center gap-1.5 px-1 opacity-80 transition hover:text-accent hover:opacity-100 focus-visible:text-accent focus-visible:underline focus-visible:underline-offset-4 focus-visible:outline-none lg:min-h-9"
                aria-label="Add category"
            >
                <Badge name="+" color="#d1d1d1"></Badge>
            </button>
        </DropdownMenuTrigger>

        <DropdownMenuContent
            align="end"
            class="guest-menu min-w-48 rounded-2xl"
        >
            <DropdownMenuItem
                v-for="(category, index) in options"
                :key="index"
                class="justify-between gap-3 data-highlighted:bg-mauve-100"
                @select="$emit('select', category)"
            >
                <Badge :name="category.name" :color="category.color" />
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
