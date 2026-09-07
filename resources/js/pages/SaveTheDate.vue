<script setup lang="ts">
import EnvelopeIntro from '@/components/guest/EnvelopeIntro.vue';
import GroupConfirmation from '@/components/guest/GroupConfirmation.vue';
import Hero from '@/components/guest/Hero.vue';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import FullPageLayout from '@/layouts/FullPageLayout.vue';
import type { ResponseOption, SaveTheDateProps } from '@/types/save-the-date';
import { Form, Head } from '@inertiajs/vue3';
import { Check } from 'lucide-vue-next';
import { computed, reactive, ref } from 'vue';

const props = defineProps<SaveTheDateProps>();

const coupleNames = 'Lauana & David';

interface Copy {
    envelopeHint: string;
    envelopeOpen: string;
    eyebrow: string;
    scrollCue: string;
    youLabel: string;
    date: string;
    location: string;
    greeting: (name: string) => string;
    intro: string;
    groupHint: string;
    submit: string;
    submitting: string;
    needOne: string;
    footerNote: string;
    errorMessage: string;
    successTitle: string;
    successBody: string;
    editResponses: string;
    options: Record<ResponseOption, string>;
}

const messages: Record<string, Copy> = {
    en: {
        envelopeHint: 'Tap the seal to open',
        envelopeOpen: 'Open the invitation',
        eyebrow: 'Save the Date',
        scrollCue: 'Scroll',
        youLabel: 'You',
        date: 'April 8, 2027',
        location: 'Maringá, Brazil',
        greeting: (name) => `Hi, ${name}!`,
        intro: "We'd love to know if we can probably count on you.",
        groupHint:
            'Mark your response and that of anyone with you. You do not have to answer for everyone now.',
        submit: 'Send responses',
        submitting: 'Saving…',
        needOne: 'Choose a response for at least one person to continue.',
        footerNote:
            "We'll share more details about travel, accommodation, and schedule soon.",
        errorMessage:
            "We couldn't save your responses. Check your connection and try again.",
        successTitle: 'Responses received!',
        successBody: "We'll share more details about the wedding soon.",
        editResponses: 'Edit responses',
        options: {
            yes: 'Yes',
            probably_yes: 'Probably yes',
            probably_no: 'Probably no',
            no: 'No',
        },
    },
    es: {
        envelopeHint: 'Toca el sello para abrir',
        envelopeOpen: 'Abrir la invitación',
        eyebrow: 'Save the Date',
        scrollCue: 'Desliza',
        youLabel: 'Tú',
        date: '8 de abril de 2027',
        location: 'Maringá, Brasil',
        greeting: (name) => `¡Hola, ${name}!`,
        intro: 'Queremos saber si probablemente podremos contar contigo.',
        groupHint:
            'Marca tu respuesta y la de quienes te acompañan. No es obligatorio responder por todos ahora.',
        submit: 'Enviar respuestas',
        submitting: 'Guardando…',
        needOne:
            'Elige una respuesta para al menos una persona para continuar.',
        footerNote:
            'Pronto compartiremos más detalles sobre viaje, hospedaje y agenda.',
        errorMessage:
            'No pudimos guardar tus respuestas. Revisa tu conexión e intenta de nuevo.',
        successTitle: '¡Respuestas recibidas!',
        successBody: 'Pronto compartiremos más detalles de la boda.',
        editResponses: 'Editar respuestas',
        options: {
            yes: 'Sí',
            probably_yes: 'Probablemente sí',
            probably_no: 'Probablemente no',
            no: 'No',
        },
    },
    pt: {
        envelopeHint: 'Toque o selo para abrir',
        envelopeOpen: 'Abrir o convite',
        eyebrow: 'Save the Date',
        scrollCue: 'Deslize',
        youLabel: 'Você',
        date: '8 de abril de 2027',
        location: 'Maringá, Brasil',
        greeting: (name) => `Olá, ${name}!`,
        intro: 'Queremos saber se provavelmente poderemos contar com você.',
        groupHint:
            'Marque sua resposta e a de quem acompanha você. Não é obrigatório responder por todos agora.',
        submit: 'Enviar respostas',
        submitting: 'Salvando…',
        needOne:
            'Escolha uma resposta para pelo menos uma pessoa para continuar.',
        footerNote:
            'Em breve compartilharemos mais detalhes sobre viagem, hospedagem e programação.',
        errorMessage:
            'Não foi possível salvar suas respostas. Verifique sua conexão e tente novamente.',
        successTitle: 'Respostas recebidas!',
        successBody: 'Em breve compartilharemos mais detalhes do casamento.',
        editResponses: 'Editar respostas',
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

const optionLabels = computed<Record<ResponseOption, string>>(() =>
    displayLang.value === props.lang
        ? props.options
        : (messages[displayLang.value]?.options ?? props.options),
);

const selected = reactive<Record<number, ResponseOption | null>>(
    Object.fromEntries(props.guestGroup.map((m) => [m.id, m.response])),
);

const setResponse = (id: number, response: ResponseOption) => {
    selected[id] = response;
};

const editing = ref(false);

const hasAnyResponse = computed(() =>
    Object.values(selected).some((value) => value !== null),
);

const buildPayload = () => ({
    responses: props.guestGroup.map((member) => ({
        id: member.id,
        response: selected[member.id] ?? null,
    })),
});
</script>

<template>
    <FullPageLayout class="guest-surface">
        <Head :title="`Save the Date — ${coupleNames}`" />

        <template #aside>
            <Hero
                :eyebrow="t.eyebrow"
                :names="coupleNames"
                :date="t.date"
                :location="t.location"
                :scroll-cue="t.scrollCue"
            >
                <template #nav>
                    <div
                        class="flex items-center gap-0.5"
                        role="group"
                        aria-label="Language"
                    >
                        <button
                            v-for="(language, code) in languages"
                            :key="code"
                            type="button"
                            :aria-pressed="displayLang === code"
                            class="guest-eyebrow inline-flex min-h-11 min-w-11 items-center justify-center rounded-full text-[0.625rem] transition-colors hover:text-accent focus-visible:ring-2 focus-visible:ring-accent focus-visible:outline-none"
                            :class="
                                displayLang === code
                                    ? 'guest-accent-on-dark'
                                    : 'text-primary-foreground/80'
                            "
                            @click="displayLang = code"
                        >
                            {{ language.value }}
                        </button>
                    </div>
                </template>
            </Hero>
        </template>

        <main class="mx-auto w-full max-w-2xl">
            <div
                class="px-6 pt-[calc(var(--guest-header-height)+2rem)] pb-8 text-center lg:pt-14"
            >
                <p class="font-display text-4xl text-foreground md:text-5xl">
                    {{ t.greeting(currentGuest.first_name) }}
                </p>
                <p
                    class="mx-auto mt-3 max-w-md text-lg leading-relaxed text-foreground/90"
                >
                    {{ t.intro }}
                </p>

                <span
                    class="mx-auto mt-7 block h-px w-10 bg-border"
                    aria-hidden="true"
                />

                <p
                    class="mx-auto mt-5 max-w-md text-base leading-relaxed text-muted-foreground"
                >
                    {{ t.groupHint }}
                </p>
            </div>

            <Form
                action="/save-the-date/confirm"
                method="post"
                :transform="buildPayload"
                disable-while-processing
                class="pb-16"
                v-slot="{ errors, processing, wasSuccessful }"
                @success="editing = false"
            >
                <div
                    v-if="wasSuccessful && !editing"
                    class="px-6 py-12 text-center"
                >
                    <span
                        class="mx-auto flex size-16 items-center justify-center rounded-full bg-accent text-accent-foreground"
                    >
                        <Check class="size-8" aria-hidden="true" />
                    </span>

                    <p
                        class="mt-6 font-display text-2xl text-foreground md:text-3xl"
                    >
                        {{ t.successTitle }}
                    </p>
                    <p
                        class="mt-3 text-base leading-relaxed text-muted-foreground"
                    >
                        {{ t.successBody }}
                    </p>

                    <button
                        type="button"
                        class="guest-accent-ink mt-6 inline-flex min-h-11 items-center rounded-full px-4 text-base underline underline-offset-4 focus-visible:ring-2 focus-visible:ring-accent focus-visible:outline-none"
                        @click="editing = true"
                    >
                        {{ t.editResponses }}
                    </button>
                </div>

                <template v-else>
                    <GroupConfirmation
                        :members="guestGroup"
                        :options="optionLabels"
                        :selected="selected"
                        :current-guest-id="currentGuest.id"
                        :you-label="t.youLabel"
                        @select="setResponse"
                    />

                    <div class="px-6 text-center">
                        <p
                            v-if="Object.keys(errors).length"
                            class="mb-4 text-sm text-destructive"
                        >
                            {{ t.errorMessage }}
                        </p>

                        <Button
                            type="submit"
                            size="lg"
                            class="min-h-12 w-full rounded-full text-base sm:w-auto sm:px-12"
                            :disabled="!hasAnyResponse || processing"
                        >
                            <Spinner v-if="processing" />
                            {{ processing ? t.submitting : t.submit }}
                        </Button>

                        <p
                            v-if="!hasAnyResponse"
                            class="mt-3 text-base text-muted-foreground"
                        >
                            {{ t.needOne }}
                        </p>
                        <p
                            v-else
                            class="mt-4 text-base leading-relaxed text-muted-foreground"
                        >
                            {{ t.footerNote }}
                        </p>
                    </div>
                </template>
            </Form>

            <div class="guest-safe-bottom px-6 pb-14 text-center">
                <div
                    class="mx-auto flex max-w-xs items-center justify-center gap-3 text-muted-foreground"
                >
                    <span class="h-px w-10 bg-border" />
                    <span class="font-display text-sm tracking-[0.2em]"
                        >L &amp; D</span
                    >
                    <span class="h-px w-10 bg-border" />
                </div>
            </div>
        </main>
    </FullPageLayout>

    <EnvelopeIntro :hint="t.envelopeHint" :open-label="t.envelopeOpen" />
</template>
