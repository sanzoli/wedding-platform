<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Plus } from 'lucide-vue-next';

defineEmits(['add']);
</script>

<template>
    <Button class="hidden sm:inline-flex" @click="$emit('add')">
        <Plus :size="15" :stroke-width="2"></Plus>
        <slot></slot>
    </Button>

    <Teleport to="body">
        <button
            class="fixed right-5 bottom-6 z-40 flex h-13 w-13 items-center justify-center rounded-full bg-primary text-primary-foreground shadow-lg transition-transform active:scale-95 sm:hidden"
            aria-label="Add item"
            @click="$emit('add')"
        >
            <Plus :size="20" :stroke-width="2" />
        </button>
    </Teleport>
</template>
