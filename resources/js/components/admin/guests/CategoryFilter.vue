<script setup lang="ts">
import AddCategoryButton from '@/components/admin/guests/AddCategoryButton.vue';
import Badge from '@/components/Badge.vue';
import { Category } from '@/types';
import { computed, ref, watch } from 'vue';

const props = defineProps<{
    categories: Category[];
    modelValue: number[];
}>();

const emit = defineEmits<{
    'update:modelValue': [value: number[]];
}>();

const selectedIds = ref<number[]>([...new Set(props.modelValue.map(Number))]);

// Sync when parent changes modelValue (page load, back button, Inertia nav)
watch(
    () => props.modelValue,
    (incoming) => {
        const normalized = [...new Set((incoming || []).map(Number))];
        if (
            normalized.length !== selectedIds.value.length ||
            normalized.some((id) => !selectedIds.value.includes(id))
        ) {
            selectedIds.value = normalized;
        }
    },
);

const selectedCategories = computed(() =>
    props.categories.filter((c) => selectedIds.value.includes(c.id)),
);

const availableCategories = computed(() =>
    props.categories.filter((c) => !selectedIds.value.includes(c.id)),
);

const addCategory = (category: Category) => {
    selectedIds.value = [...selectedIds.value, category.id];
};

const removeCategory = (category: Category) => {
    selectedIds.value = selectedIds.value.filter((id) => id !== category.id);
};

watch(selectedIds, (value) => emit('update:modelValue', value), { deep: true });
</script>

<template>
    <div class="flex items-center gap-0.5">
        <h3>Categories:</h3>
        <Badge
            v-for="category in selectedCategories"
            :key="category.id"
            :name="category.name"
            :color="category.color"
            @close="removeCategory(category)"
            can-close
        />
        <AddCategoryButton
            :options="availableCategories"
            @select="addCategory"
        />
    </div>
</template>
