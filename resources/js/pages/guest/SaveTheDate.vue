<script setup lang="ts">
import { view } from '@/actions/App/Http/Controllers/Guest/SaveTheDateController';
import LanguagePicker from '@/components/LanguagePicker.vue';
import Envelope from '@/components/guest/SaveTheDate/Envelope.vue';
import Greeting from '@/components/guest/SaveTheDate/Greeting.vue';
import Hero from '@/components/guest/SaveTheDate/Hero.vue';
import ResponseCard from '@/components/guest/SaveTheDate/ResponseCard.vue';
import TotalSaved from '@/components/guest/SaveTheDate/TotalSaved.vue';
import GuestStationeryLayout from '@/layouts/GuestStationeryLayout.vue';
import { SaveTheDateProps } from '@/types/save-the-date';
import { lang } from '@erag/lang-sync-inertia/vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const { trans } = lang();
const props = defineProps<SaveTheDateProps>();

const currentLanguage = ref(props.language);

watch(currentLanguage, () =>
    router.get(view(props.id, { query: { language: currentLanguage.value } }), {
        preserveScroll: true,
        preserveState: true,
        only: ['lang'],
    }),
);

/** On mobile the counter waits for the hero to collapse, in step with the sticky header. */
const heroCollapsed = ref(false);
</script>

<template>
    <GuestStationeryLayout class="guest-surface">
        <Head :title="`Save the Date — ${props.coupleNames}`" />

        <template #aside>
            <Hero
                :names="props.coupleNames"
                :date="props.date"
                :location="props.location"
                @collapse="heroCollapsed = $event"
            >
                <template #nav>
                    <LanguagePicker v-model="currentLanguage" />
                </template>
            </Hero>
        </template>

        <div
            class="pointer-events-none sticky top-0 z-20 hidden justify-end px-6 py-5 text-foreground lg:flex"
        >
            <span
                class="guest-island pointer-events-auto inline-flex items-center px-2 py-0.5"
            >
                <LanguagePicker v-model="currentLanguage" />
            </span>
        </div>

        <main class="guest-content mx-auto w-full max-w-2xl">
            <Greeting :currentGuest />

            <section class="space-y-5 px-6 pb-6">
                <ResponseCard
                    v-for="invitation in invitations.data"
                    :key="invitation.id"
                    :invitation
                    :is-you="invitation.guest.id === currentGuest.id"
                />
            </section>

            <TotalSaved
                :saved="invitations.answered"
                :total="invitations.total"
                :class="{ 'guest-island--waiting': !heroCollapsed }"
            />

            <p
                class="px-6 pt-8 pb-16 text-center text-base leading-relaxed text-muted-foreground"
            >
                {{ trans('save_the_date.footer_note') }}
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
    </GuestStationeryLayout>

    <Envelope
        :hint="trans('save_the_date.envelope_hint')"
        :open-label="trans('save_the_date.envelope_open')"
    />
</template>
