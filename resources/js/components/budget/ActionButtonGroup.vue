<script setup lang="ts">
import { Button } from '@/components/ui/button';
import type { Component } from 'vue';

export interface ActionButton {
    label?: string;
    ariaLabel?: string;
    icon?: Component;
    variant?: 'default' | 'destructive' | 'outline' | 'secondary' | 'ghost' | 'link';
    size?: 'default' | 'sm' | 'lg' | 'icon';
    class?: string;
    disabled?: boolean;
    loading?: boolean;
    onClick?: () => void;
    type?: 'button' | 'submit' | 'reset';
    title?: string;
}

interface Props {
    actions: ActionButton[];
    class?: string;
}

const props = defineProps<Props>();
</script>

<template>
    <div class="flex items-center gap-1.5" :class="props.class">
        <Button
            v-for="(action, index) in actions"
            :key="index"
            @click="action.onClick"
            :variant="action.variant ?? 'ghost'"
            :size="action.size ?? 'icon'"
            :class="action.class"
            :disabled="action.disabled || action.loading"
            :type="action.type ?? 'button'"
            :title="action.title"
            :aria-label="action.ariaLabel"
        >
            <component v-if="action.icon" :is="action.icon" />
            <template v-if="action.label">{{ action.label }}</template>
        </Button>
    </div>
</template>
