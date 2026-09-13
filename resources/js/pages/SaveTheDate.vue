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
    intro: (alone: boolean) => string;
    hint: (alone: boolean) => string;
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
        intro: () => "We'd like to know if you can join us that day.",
        hint: (alone) =>
            alone
                ? 'Mark the option that fits. Your response saves right away, and you can change it later.'
                : 'Answer for yourself and for whoever is coming with you. Each response saves as you mark it, and you can change it later.',
        footerNote:
            "We'll soon share more details and tips about the trip and the plan for the day.",
        progress: (saved, total) => `${saved} of ${total} responses saved`,
        saving: 'Saving…',
        saved: 'Saved',
        saveError: 'Not saved',
        retry: 'Retry',
        options: {
            yes: 'Yes',
            probably_yes: 'Most likely, still confirming',
            probably_no: "Tough, I'll check if I can",
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
        intro: (alone) =>
            alone
                ? 'Nos gustaría saber si puedes acompañarnos ese día.'
                : 'Nos gustaría saber si pueden acompañarnos ese día.',
        hint: (alone) =>
            alone
                ? 'Marca la opción que corresponda. Tu respuesta se guarda enseguida, y puedes cambiarla luego.'
                : 'Responde por ti y por quienes te acompañarán. Cada respuesta se guarda al marcarla, y puedes cambiarla luego.',
        footerNote:
            'Pronto compartiremos más detalles y sugerencias sobre el viaje y la agenda de ese día.',
        progress: (saved, total) => `${saved} de ${total} respuestas guardadas`,
        saving: 'Guardando…',
        saved: 'Guardado',
        saveError: 'No se guardó',
        retry: 'Reintentar',
        options: {
            yes: 'Sí',
            probably_yes: 'Casi seguro, por confirmar',
            probably_no: 'Difícil, revisaré si puedo',
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
        intro: (alone) =>
            alone
                ? 'Gostaríamos de saber se você pode nos acompanhar nesse dia.'
                : 'Gostaríamos de saber se vocês podem nos acompanhar nesse dia.',
        hint: (alone) =>
            alone
                ? 'Marque a opção que corresponder. Sua resposta é salva na hora, e você pode mudar depois.'
                : 'Responda por você e por quem vier com você. Cada resposta é salva ao marcar, e você pode mudar depois.',
        footerNote:
            'Em breve compartilharemos mais detalhes e sugestões sobre a viagem e a programação do dia.',
        progress: (saved, total) => `${saved} de ${total} respostas salvas`,
        saving: 'Salvando…',
        saved: 'Salvo',
        saveError: 'Não foi salvo',
        retry: 'Tentar de novo',
        options: {
            yes: 'Sim',
            probably_yes: 'Quase certo, a confirmar',
            probably_no: 'Difícil, vou ver se consigo',
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

/** A lone guest answers only for themselves: no counter, and the copy drops the plural. */
const alone = computed(() => props.guestGroup.length === 1);

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
        router.on('invalid', (event: Event) => event.preventDefault()),
        router.on('exception', (event: Event) => event.preventDefault()),
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

        <main class="guest-content mx-auto w-full max-w-2xl">
            <div
                class="px-6 pt-[calc(var(--guest-header-height)+2rem)] pb-8 text-center lg:pt-14"
            >
                <p class="guest-greeting font-display text-foreground">
                    {{ t.greeting(currentGuest.first_name) }}
                </p>
                <p
                    class="mx-auto mt-3 max-w-md text-lg leading-relaxed text-foreground/90"
                >
                    {{ t.intro(alone) }}
                </p>

                <span
                    class="mx-auto mt-7 block h-px w-10 bg-border"
                    aria-hidden="true"
                />

                <p
                    class="mx-auto mt-5 max-w-md text-base leading-relaxed text-muted-foreground"
                >
                    {{ t.hint(alone) }}
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
                v-if="!alone"
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
