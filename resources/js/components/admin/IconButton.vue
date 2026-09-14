<script setup lang="ts">
import { Button } from '@/components/ui/button';
</script>

<template>
    <Button size="icon" variant="ghost" class="h-9 w-9 text-muted-foreground">
        <slot></slot>
    </Button>
</template>
