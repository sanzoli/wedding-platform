<script setup lang="ts">
import { ChevronDown, ChevronUp } from 'lucide-vue-next';

interface Props {
    label: string;
    active: boolean;
    direction: 'asc' | 'desc' | null;
}

defineProps<Props>();

const emit = defineEmits<{
    toggle: [];
}>();
</script>

<template>
    <button
        type="button"
        class="inline-flex items-center gap-1.5 transition-colors hover:text-foreground"
        @click="emit('toggle')"
    >
        <span>{{ label }}</span>
        <ChevronUp
            v-if="active && direction === 'asc'"
            :size="13"
            class="opacity-80"
        />
        <ChevronDown
            v-else-if="active && direction === 'desc'"
            :size="13"
            class="opacity-80"
        />
        <div v-else class="flex flex-col leading-none">
            <ChevronUp :size="12" class="-mb-1 opacity-30" />
            <ChevronDown :size="12" class="opacity-30" />
        </div>
    </button>
</template>
