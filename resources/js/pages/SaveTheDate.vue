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
    options: Record<ResponseOption, string>;
}

// UI copy per language. The per-language `options` localize the response
// labels for the client-side language switch; the backend-provided
// `options` prop stays authoritative for the guest's own language.
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
        options: {
            yes: 'Yes',
            probably_yes: 'Probably yes',
            probably_no: 'Probably no',
            no: 'No',
        },
    },
    es: {
        eyebrow: 'Save the Date',
        date: '08 de abril, 2027',
        location: 'Maringá, Brasil',
        greeting: (name) => `Hola, ${name}.`,
        intro: 'Queremos saber si probablemente podremos contar contigo.',
        groupTitle: 'Tu grupo',
        groupHint:
            'Puedes responder por una o más personas ahora. No es obligatorio confirmar a todos.',
        submit: 'Enviar respuestas',
        submitting: 'Guardando…',
        needOne:
            'Elige una respuesta para al menos una persona para continuar.',
        footerNote:
            'Pronto compartiremos más detalles sobre viaje, hospedaje y agenda.',
        errorMessage:
            'No pudimos guardar tus respuestas. Revisa tu conexión e intenta de nuevo.',
        successTitle: 'Gracias, guardamos tus respuestas.',
        successBody: 'Pronto compartiremos más detalles de la boda.',
        options: {
            yes: 'Sí',
            probably_yes: 'Probablemente sí',
            probably_no: 'Probablemente no',
            no: 'No',
        },
    },
    pt: {
        eyebrow: 'Save the Date',
        date: '08 de abril de 2027',
        location: 'Maringá, Brasil',
        greeting: (name) => `Olá, ${name}.`,
        intro: 'Queremos saber se provavelmente poderemos contar com você.',
        groupTitle: 'Seu grupo',
        groupHint:
            'Você pode responder por uma ou mais pessoas agora. Não é obrigatório confirmar todos.',
        submit: 'Enviar respostas',
        submitting: 'Salvando…',
        needOne:
            'Escolha uma resposta para pelo menos uma pessoa para continuar.',
        footerNote:
            'Em breve compartilharemos mais detalhes sobre viagem, hospedagem e programação.',
        errorMessage:
            'Não foi possível salvar suas respostas. Verifique sua conexão e tente novamente.',
        successTitle: 'Obrigado, salvamos suas respostas.',
        successBody: 'Em breve compartilharemos mais detalhes do casamento.',
        options: {
            yes: 'Sim',
            probably_yes: 'Provavelmente sim',
            probably_no: 'Provavelmente não',
            no: 'Não',
        },
    },
};

const displayLang = ref(props.lang in messages ? props.lang : 'en');
const t = computed<Copy>(() => messages[displayLang.value] ?? messages.en);

// Option labels follow the selected language; the guest's own language
// keeps the backend's authoritative labels.
const optionLabels = computed<Record<ResponseOption, string>>(() =>
    displayLang.value === props.lang
        ? props.options
        : (messages[displayLang.value]?.options ?? props.options),
);

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
                    :options="optionLabels"
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
