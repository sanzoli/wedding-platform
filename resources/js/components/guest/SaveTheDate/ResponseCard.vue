<script setup lang="ts">
import { response } from '@/actions/App/Http/Controllers/SaveTheDateController';
import { Invitation, type ResponseOption } from '@/types/save-the-date';
import { vueLang } from '@erag/lang-sync-inertia/vue';
import { router, usePage } from '@inertiajs/vue3';
import { Check, Minus, X } from 'lucide-vue-next';
import { ref, type Component } from 'vue';

const { trans } = vueLang();

const props = defineProps<{
    invitation: Invitation;
    isYou: boolean;
}>();

const options: Record<ResponseOption, Component> = {
    yes: Check,
    probably_yes: Check,
    probably_no: Minus,
    no: X,
};

const selected = ref(props.invitation.response);
const retryOption = ref();
const status = ref();

const save = (option: ResponseOption) => {
    status.value = 'saving';
    selected.value = option;
    router.post(
        response(props.invitation.id),
        { response: selected.value },
        {
            preserveScroll: true,
            preserveState: true,
            only: ['invitations'],
            onSuccess: () => (status.value = 'saved'),
            onError: () => {
                status.value = 'save_error';
                retryOption.value = option;
                selected.value = props.invitation.response;
            },
        },
    );
};
</script>

<template>
    <article class="guest-card">
        <div class="mb-4 flex items-baseline justify-between gap-3">
            <p class="font-display text-xl text-primary">
                {{ invitation.guest.name }}
                <span
                    v-if="isYou"
                    class="guest-accent-ink ml-1 align-middle text-xs"
                >
                    · {{ trans('save_the_date.you_label') }}
                </span>
            </p>

            <p
                class="guest-card-status"
                :class="{
                    'guest-card-status--saving': status === 'saving',
                    'guest-accent-ink': status === 'save_error',
                    invisible: !status,
                }"
            >
                <Check
                    v-if="status === 'saved'"
                    class="size-3 shrink-0"
                    aria-hidden="true"
                />
                {{ trans('save_the_date.' + status) }}
                <button
                    v-if="status === 'save_error' && retryOption"
                    type="button"
                    class="-my-3 min-h-11 underline underline-offset-4 focus-visible:ring-2 focus-visible:ring-accent focus-visible:outline-none"
                    @click="save(retryOption)"
                >
                    {{ trans('save_the_date.retry') }}
                </button>
            </p>
        </div>

        <fieldset>
            <legend class="sr-only">{{ invitation.guest.name }}</legend>

            <div class="grid gap-2 sm:grid-cols-2">
                <label
                    v-for="(icon, option) in options"
                    :key="option"
                    class="guest-option"
                >
                    <input
                        type="radio"
                        class="sr-only"
                        :value="option"
                        :checked="selected === option"
                        @click="save(option)"
                    />
                    <component
                        :is="icon"
                        class="size-4 shrink-0"
                        aria-hidden="true"
                    />
                    <span>{{ trans('save_the_date.options.' + option) }}</span>
                </label>
            </div>
        </fieldset>
    </article>
</template>
