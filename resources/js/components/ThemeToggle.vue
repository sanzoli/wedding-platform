<script setup lang="ts">
import { useAppearance } from '@/composables/useAppearance';
import { Moon, Sun } from 'lucide-vue-next';
import { onMounted, onUnmounted, ref, watch } from 'vue';

const { appearance, updateAppearance } = useAppearance();

function toggleTheme() {
    // If current is light or system (and resolved to light), switch to dark
    // If current is dark or system (and resolved to dark), switch to light
    const currentTheme = appearance.value;

    if (currentTheme === 'dark') {
        updateAppearance('light');
    } else if (currentTheme === 'light') {
        updateAppearance('dark');
    } else {
        // For 'system', check actual resolved theme and toggle to opposite
        const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        updateAppearance(systemDark ? 'light' : 'dark');
    }
}

// Compute current resolved theme for icon display
const isDark = ref(false);

// Function to update isDark based on current appearance
const updateIsDark = () => {
    if (appearance.value === 'dark') {
        isDark.value = true;
    } else if (appearance.value === 'light') {
        isDark.value = false;
    } else {
        // system: check actual preference
        isDark.value = window.matchMedia('(prefers-color-scheme: dark)').matches;
    }
};

// Watch for appearance changes
watch(appearance, updateIsDark, { immediate: true });

// Listen for system theme changes when in system mode
const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
const handleSystemChange = () => {
    if (appearance.value === 'system') {
        isDark.value = mediaQuery.matches;
    }
};

onMounted(() => {
    mediaQuery.addEventListener('change', handleSystemChange);
    updateIsDark();
});

onUnmounted(() => {
    mediaQuery.removeEventListener('change', handleSystemChange);
});
</script>

<template>
    <button
        @click="toggleTheme"
        class="inline-flex cursor-pointer items-center justify-center rounded-md p-2 text-muted-foreground transition-colors hover:bg-hover hover:text-foreground"
        :title="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
    >
        <Sun v-if="!isDark" class="h-4 w-4" />
        <Moon v-else class="h-4 w-4" />
        <span class="sr-only">Toggle theme</span>
    </button>
</template>
