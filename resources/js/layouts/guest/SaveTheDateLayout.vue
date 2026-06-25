<script setup lang="ts">
/**
 * Full-page, chrome-less layout for the Save the Date view. Unlike the
 * admin AppLayout there is no sidebar or app nav — this is a focused,
 * single-purpose guest surface that owns the whole viewport.
 */
</script>

<template>
    <div class="min-h-svh bg-background text-foreground antialiased">
        <slot />
    </div>
</template>
