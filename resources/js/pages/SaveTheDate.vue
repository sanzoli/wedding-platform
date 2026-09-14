<script setup lang="ts">
import GuestSvgDefs from '@/components/guest/GuestSvgDefs.vue';
import EnvelopeIntro from '@/components/guest/save-the-date/EnvelopeIntro.vue';
import GroupConfirmation from '@/components/guest/save-the-date/GroupConfirmation.vue';
import Hero from '@/components/guest/save-the-date/Hero.vue';
import LanguagePicker from '@/components/language/LanguagePicker.vue';
import FullPageLayout from '@/layouts/FullPageLayout.vue';
import { format } from '@/lib/trans';
import type {
    ResponseOption,
    SaveStatus,
    SaveTheDateProps,
} from '@/types/save-the-date';
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted, reactive, ref } from 'vue';

const props = defineProps<SaveTheDateProps>();

const coupleNames = 'Lauana & David';

const trans = usePage().props.trans;

/** The guest's language is resolved on the server, so switching asks for a new render. */
const changeLanguage = (code: string) =>
    router.get(
        window.location.pathname,
        { lang: code },
        { preserveScroll: true, preserveState: true },
    );

const selected = reactive<Record<number, ResponseOption | null>>(
    Object.fromEntries(props.guestGroup.map((m) => [m.id, m.response])),
);

/** Coalesces a guest changing their mind into a single request. */
const SAVE_DELAY = 400;

const statuses = reactive<Record<number, SaveStatus | undefined>>({});
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
                if (
                    latestSave.get(id) === ticket &&
                    statuses[id] === 'saving'
                ) {
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
    pendingSaves.set(
        id,
        window.setTimeout(() => save(id), SAVE_DELAY),
    );
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
                :eyebrow="trans.save_the_date.eyebrow"
                :names="coupleNames"
                :date="trans.save_the_date.date"
                :location="trans.save_the_date.location"
                :scroll-cue="trans.save_the_date.scroll_cue"
                @collapse="heroCollapsed = $event"
            >
                <template #nav>
                    <LanguagePicker
                        :languages="languages"
                        :model-value="lang"
                        @update:model-value="changeLanguage"
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
                <LanguagePicker
                    :languages="languages"
                    :model-value="lang"
                    @update:model-value="changeLanguage"
                />
            </span>
        </div>

        <main class="guest-content mx-auto w-full max-w-2xl">
            <div
                class="px-6 pt-[calc(var(--guest-header-height)+2rem)] pb-8 text-center lg:pt-14"
            >
                <p class="guest-greeting font-display text-foreground">
                    {{
                        format(trans.save_the_date.greeting, {
                            name: currentGuest.first_name,
                        })
                    }}
                </p>
                <p
                    class="mx-auto mt-3 max-w-md text-lg leading-relaxed text-foreground/90"
                >
                    {{
                        alone
                            ? trans.save_the_date.intro.alone
                            : trans.save_the_date.intro.group
                    }}
                </p>

                <span
                    class="mx-auto mt-7 block h-px w-10 bg-border"
                    aria-hidden="true"
                />

                <p
                    class="mx-auto mt-5 max-w-md text-base leading-relaxed text-muted-foreground"
                >
                    {{
                        alone
                            ? trans.save_the_date.hint.alone
                            : trans.save_the_date.hint.group
                    }}
                </p>
            </div>

            <GroupConfirmation
                :members="guestGroup"
                :selected="selected"
                :statuses="statuses"
                :current-guest-id="currentGuest.id"
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
                    {{
                        format(trans.save_the_date.progress, {
                            saved: savedCount,
                            total: guestGroup.length,
                        })
                    }}
                </p>
            </div>

            <p
                class="px-6 pt-8 pb-16 text-center text-base leading-relaxed text-muted-foreground"
            >
                {{ trans.save_the_date.footer_note }}
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

    <EnvelopeIntro
        :hint="trans.save_the_date.envelope_hint"
        :open-label="trans.save_the_date.envelope_open"
    />
</template>
