<script setup lang="ts">
import { Card } from '@/components/ui/card';

interface Props {
    title: string;
    description: string;
    centered?: boolean;
}

withDefaults(defineProps<Props>(), {
    centered: true,
});
</script>

<template>
    <Card
        class="px-4 py-10"
        :class="{
            'items-center text-center': centered,
        }"
    >
        <p class="text-sm font-medium text-foreground/80">
            {{ title }}
        </p>
        <p class="text-xs text-muted-foreground">
            {{ description }}
        </p>
        <slot name="action" />
    </Card>
</template>
