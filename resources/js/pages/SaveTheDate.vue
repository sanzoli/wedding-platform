<script setup lang="ts">
import EnvelopeIntro from '@/components/guest/SaveTheDate/EnvelopeIntro.vue';
import Greeting from '@/components/guest/SaveTheDate/Greeting.vue';
import GuestSvgDefs from '@/components/guest/svg/GuestSvgDefs.vue';
import Hero from '@/components/guest/SaveTheDate/Hero.vue';
import LanguagePicker from '@/components/guest/LanguagePicker.vue';
import FullPageLayout from '@/layouts/FullPageLayout.vue';
import { SaveTheDateProps} from '@/types/save-the-date';
import { lang } from '@erag/lang-sync-inertia/vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import ResponseCard from '@/components/guest/SaveTheDate/ResponseCard.vue';
import TotalSaved from '@/components/guest/SaveTheDate/TotalSaved.vue';

const { trans } = lang();
const props = defineProps<SaveTheDateProps>();

const displayLang = ref(props.language);

/** On mobile the counter waits for the hero to collapse, in step with the sticky header. */
const heroCollapsed = ref(false);
</script>

<template>
    <FullPageLayout class="guest-surface">
        <Head :title="`Save the Date — ${props.coupleNames}`" />

        <GuestSvgDefs />

        <template #aside>
            <Hero
                :names="props.coupleNames"
                :date="props.date"
                :location="props.location"
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

            <p class="px-6 pt-8 pb-16 text-center text-base leading-relaxed text-muted-foreground">
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
    </FullPageLayout>

    <EnvelopeIntro
        :hint="trans('save_the_date.envelope_hint')"
        :open-label="trans('save_the_date.envelope_open')"
    />
</template>
