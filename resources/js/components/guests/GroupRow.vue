<script setup lang="ts">
import { Guest, GuestGroup } from '@/types/guests';
import PrimaryRow from '@/components/guests/PrimaryRow.vue';
import { ref } from 'vue';
import CompanionEditor from '@/components/guests/CompanionEditor.vue';
import { InertiaForm } from '@inertiajs/vue3';
import { storeGuest } from '@/composables/admin/useGuest';
import CompanionRow from '@/components/guests/CompanionRow.vue';

defineProps<{
    group: GuestGroup;
    query?: string;
}>();

const adding = ref(false);
const storeCompanion = (form: InertiaForm<Guest>) => storeGuest(form, { onSuccess: () => (adding.value = false) });
</script>

<template>
    <PrimaryRow :guest="group.primary" :query :members="group.count" @addCompanion="adding = true" />
    <CompanionRow v-for="(companion, i) in group.companies" :key="i" :companion></CompanionRow>
    <CompanionEditor
        v-if="adding"
        :group-id="group.id"
        @save="storeCompanion"
        @close="adding = false"
    ></CompanionEditor>
</template>
