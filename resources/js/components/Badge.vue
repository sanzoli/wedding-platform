<script setup lang="ts">
import { X } from 'lucide-vue-next';
import { computed } from 'vue';

defineEmits(['close']);

const props = defineProps<{
    name: string;
    color: string;
    canClose?: boolean;
}>();

const badgeStyle = computed(() => ({
    backgroundColor: `${props.color}1a`, // ~10% opacity
    borderColor: `${props.color}66`, // ~40% opacity
    color: props.color,
}));
</script>

<template>
    <span
        class="inline-flex items-center rounded-full border px-1.5 py-0.5 text-xs font-medium"
        :style="badgeStyle"
    >
        {{ name }}
        <X
            v-if="canClose"
            @click="$emit('close')"
            :size="14"
            :stroke-width="1"
        ></X>
    </span>
</template>
