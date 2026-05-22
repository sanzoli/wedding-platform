<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Tooltip,
    TooltipContent,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import { computed } from 'vue';

const props = withDefaults(
    defineProps<{
        label?: string;
        variant?: 'muted' | 'primary' | 'destructive';
    }>(),
    { variant: 'muted' },
);

const emit = defineEmits<{ click: [] }>();

const hoverClass = computed(() => {
    switch (props.variant) {
        case 'primary':
            return 'hover:bg-primary/10 hover:text-foreground dark:hover:bg-primary/25';
        case 'destructive':
            return 'hover:bg-destructive/10 dark:hover:bg-destructive/25';
        default:
            return 'hover:bg-muted hover:text-foreground';
    }
});

// Blur first so Radix doesn't refocus after a dialog closes, which
// would re-open a stuck tooltip.
const onClick = (event: MouseEvent) => {
    (event.currentTarget as HTMLElement | null)?.blur();
    emit('click');
};
</script>

<template>
    <Tooltip v-if="label">
        <TooltipTrigger as-child>
            <Button
                variant="ghost"
                size="icon"
                :class="['size-8 cursor-pointer', hoverClass]"
                :aria-label="label"
                @click="onClick"
            >
                <slot />
            </Button>
        </TooltipTrigger>
        <TooltipContent>{{ label }}</TooltipContent>
    </Tooltip>
    <Button
        v-else
        variant="ghost"
        size="icon"
        :class="['size-8 cursor-pointer', hoverClass]"
        @click="onClick"
    >
        <slot />
    </Button>
</template>
