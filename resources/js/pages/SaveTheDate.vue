<script setup lang="ts">
import EnvelopeIntro from '@/components/guest/EnvelopeIntro.vue';
import GroupConfirmation from '@/components/guest/GroupConfirmation.vue';
import GuestSvgDefs from '@/components/guest/GuestSvgDefs.vue';
import Hero from '@/components/guest/Hero.vue';
import LanguagePicker from '@/components/guest/LanguagePicker.vue';
import FullPageLayout from '@/layouts/FullPageLayout.vue';
import type {
    ResponseOption,
    SaveStatus,
    SaveTheDateProps,
} from '@/types/save-the-date';
import { Head, router } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted, reactive, ref } from 'vue';

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
    footerNote: string;
    progress: (saved: number, total: number) => string;
    saving: string;
    saved: string;
    saveError: string;
    retry: string;
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
        footerNote:
            "We'll share more details about travel, accommodation, and schedule soon.",
        progress: (saved, total) => `${saved} of ${total} responses saved`,
        saving: 'Saving…',
        saved: 'Saved',
        saveError: 'Not saved',
        retry: 'Retry',
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
        footerNote:
            'Pronto compartiremos más detalles sobre viaje, hospedaje y agenda.',
        progress: (saved, total) => `${saved} de ${total} respuestas guardadas`,
        saving: 'Guardando…',
        saved: 'Guardado',
        saveError: 'No se guardó',
        retry: 'Reintentar',
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
        footerNote:
            'Em breve compartilharemos mais detalhes sobre viagem, hospedagem e programação.',
        progress: (saved, total) => `${saved} de ${total} respostas salvas`,
        saving: 'Salvando…',
        saved: 'Salvo',
        saveError: 'Não foi salvo',
        retry: 'Tentar de novo',
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

/** Coalesces a guest changing their mind into a single request. */
const SAVE_DELAY = 400;

const statuses = reactive<Record<number, SaveStatus | undefined>>({});
const statusLabels = computed<Record<SaveStatus, string>>(() => ({
    saving: t.value.saving,
    saved: t.value.saved,
    error: t.value.saveError,
}));

const pendingSaves = new Map<number, number>();

/** Only the newest request for a guest may write their status. */
const latestSave = new Map<number, number>();

const save = (id: number) => {
    const ticket = (latestSave.get(id) ?? 0) + 1;
    latestSave.set(id, ticket);
    statuses[id] = 'saving';

    router.patch(
        `/save-the-date/guests/${id}/response`,
        { response: selected[id] },
        {
            preserveScroll: true,
            preserveState: true,
            only: ['guestGroup'],
            onSuccess: () => {
                if (latestSave.get(id) === ticket) {
                    statuses[id] = 'saved';
                }
            },
            onError: () => {
                if (latestSave.get(id) === ticket) {
                    statuses[id] = 'error';
                }
            },
            // onError only fires for validation; network and server failures land here
            onFinish: () => {
                if (latestSave.get(id) === ticket && statuses[id] === 'saving') {
                    statuses[id] = 'error';
                }
            },
        },
    );
};

/** On mobile the counter waits for the hero to collapse, in step with the sticky header. */
const heroCollapsed = ref(false);

/** A response counts once it is on the server: still saving or failed does not. */
const savedCount = computed(
    () =>
        props.guestGroup.filter(
            (member) =>
                selected[member.id] !== null &&
                statuses[member.id] !== 'saving' &&
                statuses[member.id] !== 'error',
        ).length,
);

const setResponse = (id: number, response: ResponseOption) => {
    selected[id] = response;

    window.clearTimeout(pendingSaves.get(id));
    pendingSaves.set(id, window.setTimeout(() => save(id), SAVE_DELAY));
};

/** The failing card already says so; a guest must never meet Inertia's error overlay. */
let stopOverlay: (() => void)[] = [];

onMounted(() => {
    stopOverlay = [
        router.on('invalid', (event) => event.preventDefault()),
        router.on('exception', (event) => event.preventDefault()),
    ];
});

onBeforeUnmount(() => {
    pendingSaves.forEach((timer) => window.clearTimeout(timer));
    stopOverlay.forEach((stop) => stop());
});
</script>

<template>
    <FullPageLayout class="guest-surface">
        <Head :title="`Save the Date — ${coupleNames}`" />

        <GuestSvgDefs />

        <template #aside>
            <Hero
                :eyebrow="t.eyebrow"
                :names="coupleNames"
                :date="t.date"
                :location="t.location"
                :scroll-cue="t.scrollCue"
                @collapse="heroCollapsed = $event"
            >
                <template #nav>
                    <LanguagePicker
                        :languages="languages"
                        v-model="displayLang"
                    />
                </template>
            </Hero>
        </template>

        <div
            class="pointer-events-none sticky top-0 z-20 hidden justify-end px-6 py-5 text-foreground lg:flex"
        >
            <span
                class="guest-island pointer-events-auto inline-flex items-center px-2 py-0.5"
            >
                <LanguagePicker :languages="languages" v-model="displayLang" />
            </span>
        </div>

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

            <GroupConfirmation
                :members="guestGroup"
                :options="optionLabels"
                :selected="selected"
                :statuses="statuses"
                :status-labels="statusLabels"
                :retry-label="t.retry"
                :current-guest-id="currentGuest.id"
                :you-label="t.youLabel"
                @select="setResponse"
                @retry="save"
            />

            <div
                class="pointer-events-none sticky bottom-4 z-10 flex justify-center px-6"
            >
                <p
                    class="guest-island guest-island--count pointer-events-auto px-5 py-2.5 text-sm"
                    :class="{ 'guest-island--waiting': !heroCollapsed }"
                    aria-live="polite"
                >
                    {{ t.progress(savedCount, guestGroup.length) }}
                </p>
            </div>

            <p
                class="px-6 pt-8 pb-16 text-center text-base leading-relaxed text-muted-foreground"
            >
                {{ t.footerNote }}
            </p>

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
