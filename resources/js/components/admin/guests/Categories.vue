<script setup lang="ts">
import Badge from '@/components/Badge.vue';
import AddCategoryButton from '@/components/admin/guests/AddCategoryButton.vue';
import { Category } from '@/types';
import { computed } from 'vue';

defineEmits<{
    add: [category: Category];
    remove: [category: Category];
}>();

const props = defineProps<{
    categories: Category[] | null;
    all: Category[];
    guest_id: number;
}>();

const options = computed(() =>
    props.all.filter((c) => !props.categories?.some((s) => s.id === c.id)),
);
</script>

<template>
    <div
        v-if="categories?.length"
        class="mt-1 flex flex-wrap items-center gap-1"
    >
        <Badge
            v-for="category in categories"
            :key="category.id"
            :name="category.name"
            :color="category.color"
            @close="$emit('remove', category)"
            can-close
        />

        <AddCategoryButton
            @select="$emit('add', $event)"
            :options
        />
    </div>
</template>
