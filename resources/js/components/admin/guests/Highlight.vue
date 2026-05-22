<script setup lang="ts">
// Wraps substrings of `text` matching `query` in <mark>. Parent decides filtering.
import { computed } from 'vue';

const props = defineProps<{
    text: string;
    query: string;
}>();

const parts = computed<Array<{ text: string; match: boolean }>>(() => {
    const q = props.query.trim();
    if (!q || !props.text) {
        return [{ text: props.text, match: false }];
    }

    const lower = props.text.toLowerCase();
    const needle = q.toLowerCase();
    const result: Array<{ text: string; match: boolean }> = [];
    let i = 0;
    while (i < props.text.length) {
        const idx = lower.indexOf(needle, i);
        if (idx === -1) {
            result.push({ text: props.text.slice(i), match: false });
            break;
        }
        if (idx > i) {
            result.push({ text: props.text.slice(i, idx), match: false });
        }
        result.push({
            text: props.text.slice(idx, idx + needle.length),
            match: true,
        });
        i = idx + needle.length;
    }
    return result;
});
</script>

<template>
    <template v-for="(p, idx) in parts" :key="idx">
        <mark
            v-if="p.match"
            class="rounded-sm bg-accent/30 px-0.5 text-foreground"
            >{{ p.text }}</mark
        >
        <template v-else>{{ p.text }}</template>
    </template>
</template>
