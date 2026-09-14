<script setup lang="ts">
import type {
    GuestGroupMember,
    ResponseOption,
    SaveStatus,
} from '@/types/save-the-date';
import { usePage } from '@inertiajs/vue3';
import { Check, Minus, X } from 'lucide-vue-next';
import { computed, type Component } from 'vue';

const props = defineProps<{
    member: GuestGroupMember;
    selected: ResponseOption | null;
    status?: SaveStatus;
    isYou?: boolean;
}>();

const trans = usePage().props.trans;

const statusLabel = computed(
    () =>
        ({
            saving: trans.save_the_date.saving,
            saved: trans.save_the_date.saved,
            error: trans.save_the_date.save_error,
        })[props.status ?? 'saved'],
);

defineEmits<{
    select: [response: ResponseOption];
    retry: [];
}>();

const choices: { value: ResponseOption; icon: Component }[] = [
    { value: 'yes', icon: Check },
    { value: 'probably_yes', icon: Check },
    { value: 'probably_no', icon: Minus },
    { value: 'no', icon: X },
];
</script>

<template>
    <article class="guest-card">
        <div class="mb-4 flex items-baseline justify-between gap-3">
            <p class="font-display text-xl text-primary">
                {{ member.full_name }}
                <span
                    v-if="isYou"
                    class="guest-accent-ink ml-1 align-middle text-xs"
                >
                    · {{ trans.save_the_date.you_label }}
                </span>
            </p>

            <p
                class="guest-card-status"
                :class="{
                    'guest-card-status--saving': status === 'saving',
                    'guest-accent-ink': status === 'error',
                    invisible: !status,
                }"
            >
                <Check
                    v-if="status === 'saved'"
                    class="size-3 shrink-0"
                    aria-hidden="true"
                />
                {{ statusLabel }}
                <button
                    v-if="status === 'error'"
                    type="button"
                    class="-my-3 min-h-11 underline underline-offset-4 focus-visible:ring-2 focus-visible:ring-accent focus-visible:outline-none"
                    @click="$emit('retry')"
                >
                    {{ trans.save_the_date.retry }}
                </button>
            </p>
        </div>

        <fieldset>
            <legend class="sr-only">{{ member.full_name }}</legend>

            <div class="grid gap-2 sm:grid-cols-2">
                <label
                    v-for="choice in choices"
                    :key="choice.value"
                    class="guest-option"
                >
                    <input
                        type="radio"
                        class="sr-only"
                        :name="`member-${member.id}`"
                        :value="choice.value"
                        :checked="selected === choice.value"
                        @change="$emit('select', choice.value)"
                    />
                    <component
                        :is="choice.icon"
                        class="size-4 shrink-0"
                        aria-hidden="true"
                    />
                    <span>{{ trans.save_the_date.options[choice.value] }}</span>
                </label>
            </div>
        </fieldset>
    </article>
</template>
