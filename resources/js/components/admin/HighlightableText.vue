<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    text: string | null;
    query: string | undefined;
}>();

interface Fragment {
    text: string | null | undefined;
    highlighted: boolean;
}

const createFragment = (
    start?: number,
    end?: number,
    highlighted?: boolean,
): Fragment => ({
    text: props.text?.slice(start, end),
    highlighted: highlighted ?? false,
});

const fragments = computed<Fragment[]>(() => {
    let needle = props.query?.trim();
    if (!needle || !props.text) {
        return [{ text: props.text, highlighted: false }];
    }

    needle = needle.toLowerCase();
    const searchable = props.text.toLowerCase();
    const result: Fragment[] = [];

    let currentPosition = 0;
    while (currentPosition < props.text.length) {
        const foundPosition = searchable.indexOf(needle, currentPosition);
        if (foundPosition === -1) {
            result.push(createFragment(currentPosition));
            break;
        }

        if (foundPosition > currentPosition) {
            result.push(createFragment(currentPosition, foundPosition));
        }

        result.push(
            createFragment(foundPosition, foundPosition + needle.length, true),
        );

        currentPosition = foundPosition + needle.length;
    }

    return result;
});
</script>

<template>
    <template v-for="(fragment, i) in fragments" :key="i">
        <mark
            v-if="fragment.highlighted"
            class="rounded-xs bg-accent/30 text-foreground"
            >{{ fragment.text }}</mark
        >
        <template v-else>{{ fragment.text }}</template>
    </template>
</template>
