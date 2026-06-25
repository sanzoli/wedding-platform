<script setup lang="ts">
import GroupConfirmation from '@/components/guest/GroupConfirmation.vue';
import Hero from '@/components/guest/Hero.vue';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import SaveTheDateLayout from '@/layouts/guest/SaveTheDateLayout.vue';
import type { ResponseOption, SaveTheDateProps } from '@/types/save-the-date';
import { Form, Head } from '@inertiajs/vue3';
import { computed, reactive, ref } from 'vue';

const props = defineProps<SaveTheDateProps>();

// Content not yet backed by a contract field.
// MOCK: the couple names and wedding date/place are hardcoded pending a
// backend wedding/event source — no such object exists in the contract.
const coupleNames = 'Lauana & David';

interface Copy {
    eyebrow: string;
    date: string;
    location: string;
    greeting: (name: string) => string;
    intro: string;
    groupTitle: string;
    groupHint: string;
    submit: string;
    submitting: string;
    needOne: string;
    footerNote: string;
    errorMessage: string;
    successTitle: string;
    successBody: string;
}

// English base copy. ES/PT coverage lands in the i18n pass.
const messages: Record<string, Copy> = {
    en: {
        eyebrow: 'Save the Date',
        date: 'April 8, 2027',
        location: 'Maringá, Brazil',
        greeting: (name) => `Hi, ${name}.`,
        intro: "We'd love to know if we can probably count on you.",
        groupTitle: 'Your group',
        groupHint:
            'You can respond for one or more people now. You do not have to confirm everyone yet.',
        submit: 'Send responses',
        submitting: 'Saving…',
        needOne: 'Choose a response for at least one person to continue.',
        footerNote:
            "We'll share more details about travel, accommodation, and schedule soon.",
        errorMessage:
            "We couldn't save your responses. Check your connection and try again.",
        successTitle: 'Thank you, we saved your responses.',
        successBody: "We'll share more details about the wedding soon.",
    },
};

const displayLang = ref(props.lang in messages ? props.lang : 'en');
const t = computed<Copy>(() => messages[displayLang.value] ?? messages.en);

// Selected response per member id — seeded from any previous response.
// Independent of `displayLang`, so switching language never clears it.
const selected = reactive<Record<number, ResponseOption | null>>(
    Object.fromEntries(props.guestGroup.map((m) => [m.id, m.response])),
);

const setResponse = (id: number, response: ResponseOption) => {
    selected[id] = response;
};

// At least one member must have a response before submitting.
const hasAnyResponse = computed(() =>
    Object.values(selected).some((value) => value !== null),
);

// Submission payload: every member with their response (or null).
const buildPayload = () => ({
    responses: props.guestGroup.map((member) => ({
        id: member.id,
        response: selected[member.id] ?? null,
    })),
});
</script>

<template>
    <SaveTheDateLayout>
        <Head :title="`Save the Date — ${coupleNames}`" />

        <div class="flex justify-end px-6 pt-6">
            <div
                class="flex items-center gap-3"
                role="group"
                aria-label="Language"
            >
                <button
                    v-for="(language, code) in languages"
                    :key="code"
                    type="button"
                    :aria-pressed="displayLang === code"
                    class="guest-eyebrow text-[0.625rem] transition-colors hover:text-accent"
                    :class="
                        displayLang === code
                            ? 'text-accent'
                            : 'text-muted-foreground'
                    "
                    @click="displayLang = code"
                >
                    {{ language.value }}
                </button>
            </div>
        </div>

        <Hero
            :eyebrow="t.eyebrow"
            :names="coupleNames"
            :date="t.date"
            :location="t.location"
            :greeting="t.greeting(guest.first_name)"
            :intro="t.intro"
        />

        <Form
            action="/save-the-date/confirm"
            method="post"
            :transform="buildPayload"
            disable-while-processing
            class="pb-20"
            v-slot="{ errors, processing, wasSuccessful }"
        >
            <div v-if="wasSuccessful" class="px-6 py-16 text-center">
                <div class="mx-auto max-w-md">
                    <p
                        class="font-display text-2xl text-foreground md:text-3xl"
                    >
                        {{ t.successTitle }}
                    </p>
                    <p
                        class="mt-3 text-base leading-relaxed text-muted-foreground"
                    >
                        {{ t.successBody }}
                    </p>
                </div>
            </div>

            <template v-else>
                <GroupConfirmation
                    :title="t.groupTitle"
                    :hint="t.groupHint"
                    :members="guestGroup"
                    :options="options"
                    :selected="selected"
                    :current-guest-id="guest.id"
                    @select="setResponse"
                />

                <div class="px-6">
                    <div class="mx-auto max-w-xl text-center">
                        <p
                            v-if="Object.keys(errors).length"
                            class="mb-4 text-sm text-destructive"
                        >
                            {{ t.errorMessage }}
                        </p>

                        <Button
                            type="submit"
                            size="lg"
                            class="w-full rounded-full sm:w-auto sm:px-12"
                            :disabled="!hasAnyResponse || processing"
                        >
                            <Spinner v-if="processing" />
                            {{ processing ? t.submitting : t.submit }}
                        </Button>

                        <p
                            v-if="!hasAnyResponse"
                            class="mt-3 text-sm text-muted-foreground"
                        >
                            {{ t.needOne }}
                        </p>
                        <p
                            v-else
                            class="mt-4 text-sm leading-relaxed text-muted-foreground"
                        >
                            {{ t.footerNote }}
                        </p>
                    </div>
                </div>
            </template>
        </Form>
    </SaveTheDateLayout>
</template>
