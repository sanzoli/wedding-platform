<script setup lang="ts">
import { Guest } from '@/types/guests';
import { BookCheck, BookCopy, BookPlus } from 'lucide-vue-next';
import IconButton from '@/components/IconButton.vue';
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { store } from '@/routes/invitations';

defineProps<{
    guest: Guest;
}>();

const copied = ref<boolean>(false);
let resetTimeout: ReturnType<typeof setTimeout> | null = null;

const copySaveTheDate = async (value: string) => {
    try {
        await navigator.clipboard.writeText(value);
        copied.value = true;

        if (resetTimeout) clearTimeout(resetTimeout);
        resetTimeout = setTimeout(() => {
            copied.value = false;
            resetTimeout = null;
        }, 3000);
    } catch (err) {
        console.error('Failed to copy:', err);
    }
};
</script>

<template>
    <Link
        v-if="!guest.save_the_date"
        :href="store()"
        :data="{ type: 'SaveTheDate', guest_id: guest.id, default_language: guest.lang }"
    >
        <IconButton
            class="hover:bg-primary/10 hover:text-primary"
            :data-test="'guest-create-save-the-date-button-' + guest.id"
            aria-label="create-save-the-date"
        >
            <BookPlus></BookPlus>
        </IconButton>
    </Link>

    <div v-else class="relative inline-flex">
        <IconButton
            class="hover:bg-primary/10 hover:text-primary"
            :data-test="'guest-copy-save-the-date-button-' + guest.id"
            aria-label="copy-save-the-date-url"
            @click="copySaveTheDate(guest.save_the_date)"
        >
            <BookCheck v-if="copied"></BookCheck>
            <BookCopy v-else></BookCopy>
        </IconButton>

        <Transition
            enter-active-class="transition ease-out duration-150"
            enter-from-class="opacity-0 translate-y-1"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition ease-in duration-100"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 translate-y-1"
        >
            <span
                v-if="copied"
                class="pointer-events-none absolute top-full left-1/2 z-10 mt-2 -translate-x-1/2 rounded-md bg-gray-900 px-2 py-1 text-xs font-medium whitespace-nowrap text-white shadow-md"
                role="status"
            >
                Copied!
                <span
                    class="absolute bottom-full left-1/2 -translate-x-1/2 border-4 border-transparent border-b-gray-900"
                ></span>
            </span>
        </Transition>
    </div>
</template>

<style scoped></style>
