<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Head } from '@inertiajs/vue3';
import { Heart, Plane } from 'lucide-vue-next';
import { onMounted, onUnmounted, ref } from 'vue';

const scrolled = ref(false);

const handleScroll = () => {
    scrolled.value = window.scrollY > 120;
};

const scrollToSection = (id: string) => {
    const element = document.getElementById(id);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
    <Head title="Lauana & David" />

    <!-- Sticky Navigation -->
    <nav
        :class="[
            'fixed top-0 right-0 left-0 z-50 transition-all duration-300',
            scrolled
                ? 'border-b border-border/50 bg-background/80 backdrop-blur-md'
                : 'bg-transparent',
        ]"
    >
        <div class="mx-auto max-w-6xl px-6">
            <div
                class="flex items-center justify-between py-4 md:justify-center"
            >
                <!-- Mobile: Couple names on left -->
                <button
                    @click="scrollToSection('hero')"
                    :class="[
                        'font-display text-base tracking-tight transition-colors md:hidden',
                        scrolled
                            ? 'text-foreground'
                            : 'text-primary-foreground',
                    ]"
                >
                    Lauana &amp; David
                </button>

                <!-- Desktop: Full navigation -->
                <div class="hidden items-center gap-8 md:flex">
                    <button
                        @click="scrollToSection('story')"
                        :class="[
                            'text-[11px] font-medium tracking-[0.2em] uppercase transition-colors hover:text-accent',
                            scrolled
                                ? 'text-muted-foreground'
                                : 'text-primary-foreground/70',
                        ]"
                    >
                        Our Story
                    </button>
                    <button
                        @click="scrollToSection('travel')"
                        :class="[
                            'text-[11px] font-medium tracking-[0.2em] uppercase transition-colors hover:text-accent',
                            scrolled
                                ? 'text-muted-foreground'
                                : 'text-primary-foreground/70',
                        ]"
                    >
                        Travel
                    </button>
                    <button
                        @click="scrollToSection('hero')"
                        :class="[
                            'font-display text-xl tracking-tight transition-colors',
                            scrolled
                                ? 'text-foreground'
                                : 'text-primary-foreground',
                        ]"
                    >
                        Lauana &amp; David
                    </button>
                    <button
                        @click="scrollToSection('dress-code')"
                        :class="[
                            'text-[11px] font-medium tracking-[0.2em] uppercase transition-colors hover:text-accent',
                            scrolled
                                ? 'text-muted-foreground'
                                : 'text-primary-foreground/70',
                        ]"
                    >
                        Dress Code
                    </button>
                    <button
                        @click="scrollToSection('rsvp')"
                        :class="[
                            'text-[11px] font-medium tracking-[0.2em] uppercase transition-colors hover:text-accent',
                            scrolled
                                ? 'text-muted-foreground'
                                : 'text-primary-foreground/70',
                        ]"
                    >
                        RSVP
                    </button>
                </div>

                <!-- Mobile: RSVP on right -->
                <button
                    @click="scrollToSection('rsvp')"
                    :class="[
                        'text-[11px] font-medium tracking-[0.2em] uppercase transition-colors md:hidden',
                        scrolled ? 'text-accent' : 'text-primary-foreground/80',
                    ]"
                >
                    RSVP
                </button>
            </div>
        </div>
    </nav>

    <main class="min-h-screen">
        <!-- ==============================================
             SECTION 1: Hero — full-screen, dark surface
             ============================================== -->
        <section
            id="hero"
            class="relative flex h-screen w-full items-center justify-center bg-primary"
        >
            <!-- Hero background image -->
            <img
                src="https://images.unsplash.com/photo-1519741497674-611481863552?w=1920&q=80"
                alt="Wedding couple"
                class="absolute inset-0 h-full w-full object-cover"
            />
            <div
                class="absolute inset-0 bg-gradient-to-b from-black/40 via-black/20 to-black/60"
            />

            <!-- Content -->
            <div
                class="relative z-10 flex flex-col items-center px-6 text-center text-primary-foreground"
            >
                <p
                    class="mb-8 text-[11px] font-medium tracking-[0.3em] text-primary-foreground/60 uppercase"
                >
                    We're getting married
                </p>
                <h1
                    class="font-display text-5xl font-medium tracking-wide md:text-7xl lg:text-8xl"
                >
                    Lauana &amp; David
                </h1>
                <div class="mt-10 flex flex-col items-center gap-4">
                    <div class="h-px w-12 bg-primary-foreground/30" />
                    <p class="text-lg font-light tracking-wide md:text-xl">
                        April 8, 2027
                    </p>
                    <p
                        class="text-sm tracking-[0.15em] text-primary-foreground/60"
                    >
                        Maringá, Paraná &middot; Brazil
                    </p>
                </div>
                <div class="mt-12">
                    <Button
                        size="lg"
                        class="rounded-full bg-accent/90 px-10 font-medium tracking-wide text-accent-foreground hover:bg-accent"
                    >
                        Confirm Your Presence
                    </Button>
                </div>
            </div>

            <!-- Scroll cue -->
            <div
                class="absolute bottom-10 left-1/2 flex -translate-x-1/2 flex-col items-center gap-3"
            >
                <span
                    class="text-[11px] font-light tracking-[0.25em] text-primary-foreground/40 uppercase"
                >
                    Scroll
                </span>
                <div class="h-8 w-px bg-primary-foreground/30" />
            </div>
        </section>

        <!-- ==============================================
             SECTION 2: Our Story — 2-col image + text
             ============================================== -->
        <section id="story" class="bg-muted py-28 md:py-44">
            <div class="mx-auto max-w-6xl px-6 lg:px-8">
                <div
                    class="grid items-center gap-12 md:grid-cols-12 md:gap-16 lg:gap-24"
                >
                    <!-- Our Story image — takes more visual weight -->
                    <div class="md:col-span-7">
                        <div
                            class="relative aspect-[4/5] overflow-hidden rounded-2xl shadow-2xl md:aspect-[3/4]"
                        >
                            <img
                                src="https://images.unsplash.com/photo-1522673607200-164d1b6ce486?w=800&q=80"
                                alt="Couple together"
                                class="h-full w-full object-cover"
                            />
                        </div>
                    </div>

                    <!-- Text — narrower column, editorial rhythm -->
                    <div class="md:col-span-5">
                        <div>
                            <p class="type-label text-accent">Our Story</p>
                            <div class="mt-5 h-px w-10 bg-accent/50" />
                        </div>
                        <h2
                            class="mt-8 font-display text-4xl leading-[1.1] text-foreground md:text-5xl"
                        >
                            Two hearts, one journey
                        </h2>
                        <div class="mt-8 space-y-5">
                            <p
                                class="text-base leading-[1.8] text-muted-foreground"
                            >
                                From a chance meeting in Bogotá to building our
                                life together, our love story spans two
                                beautiful countries. David from Colombia and
                                Lauana from Brazil found each other and knew it
                                was meant to be.
                            </p>
                            <p
                                class="text-base leading-[1.8] text-muted-foreground"
                            >
                                Now we invite you to celebrate this next chapter
                                with us in the beautiful city of Maringá, known
                                as the Green City of Brazil.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==============================================
             SECTION 3: Wedding Day Timeline
             ============================================== -->
        <section id="schedule" class="relative bg-card py-28 md:py-44">
            <div class="mx-auto max-w-4xl px-6">
                <!-- Header -->
                <div class="mb-16 text-center">
                    <p class="type-label mb-5 text-accent">The Big Day</p>
                    <h2
                        class="mb-6 font-display text-4xl leading-[1.15] text-foreground md:text-5xl"
                    >
                        April 8, 2027
                    </h2>
                    <p class="text-lg text-muted-foreground">
                        LaLinda Eventos, Maringá
                    </p>
                </div>

                <!-- Timeline -->
                <div class="relative">
                    <!-- Timeline line -->
                    <div
                        class="absolute top-0 bottom-0 left-[19px] w-px bg-border md:left-1/2 md:-translate-x-1/2"
                    />

                    <!-- Timeline moments -->
                    <div class="space-y-12 md:space-y-16">
                        <!-- Arrival -->
                        <div class="relative flex items-start gap-8 md:gap-12">
                            <!-- Time indicator -->
                            <div
                                class="relative z-10 flex h-10 w-10 shrink-0 items-center justify-center rounded-full border-2 border-accent bg-card md:absolute md:left-1/2 md:-translate-x-1/2"
                            >
                                <div class="h-2 w-2 rounded-full bg-accent" />
                            </div>

                            <!-- Content -->
                            <div
                                class="flex-1 md:grid md:grid-cols-2 md:gap-12"
                            >
                                <div class="md:text-right">
                                    <p
                                        class="mb-2 text-sm font-medium tracking-wide text-accent"
                                    >
                                        2:30 PM
                                    </p>
                                    <h3
                                        class="mb-3 font-display text-2xl text-foreground md:text-3xl"
                                    >
                                        Arrival
                                    </h3>
                                    <p
                                        class="text-base leading-relaxed text-muted-foreground"
                                    >
                                        Guests arrive and are welcomed at
                                        LaLinda Eventos.
                                    </p>
                                </div>
                                <div class="hidden md:block" />
                            </div>
                        </div>

                        <!-- Ceremony -->
                        <div class="relative flex items-start gap-8 md:gap-12">
                            <div
                                class="relative z-10 flex h-10 w-10 shrink-0 items-center justify-center rounded-full border-2 border-accent bg-card md:absolute md:left-1/2 md:-translate-x-1/2"
                            >
                                <div class="h-2 w-2 rounded-full bg-accent" />
                            </div>

                            <div
                                class="flex-1 md:grid md:grid-cols-2 md:gap-12"
                            >
                                <div class="hidden md:block" />
                                <div>
                                    <p
                                        class="mb-2 text-sm font-medium tracking-wide text-accent"
                                    >
                                        3:00 PM
                                    </p>
                                    <h3
                                        class="mb-3 font-display text-2xl text-foreground md:text-3xl"
                                    >
                                        Ceremony
                                    </h3>
                                    <p
                                        class="text-base leading-relaxed text-muted-foreground"
                                    >
                                        The wedding ceremony begins.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Cocktails -->
                        <div class="relative flex items-start gap-8 md:gap-12">
                            <div
                                class="relative z-10 flex h-10 w-10 shrink-0 items-center justify-center rounded-full border-2 border-accent bg-card md:absolute md:left-1/2 md:-translate-x-1/2"
                            >
                                <div class="h-2 w-2 rounded-full bg-accent" />
                            </div>

                            <div
                                class="flex-1 md:grid md:grid-cols-2 md:gap-12"
                            >
                                <div class="md:text-right">
                                    <p
                                        class="mb-2 text-sm font-medium tracking-wide text-accent"
                                    >
                                        5:00 PM
                                    </p>
                                    <h3
                                        class="mb-3 font-display text-2xl text-foreground md:text-3xl"
                                    >
                                        Cocktails
                                    </h3>
                                    <p
                                        class="text-base leading-relaxed text-muted-foreground"
                                    >
                                        Sunset cocktails and celebration begin.
                                    </p>
                                </div>
                                <div class="hidden md:block" />
                            </div>
                        </div>

                        <!-- Dinner & Reception -->
                        <div class="relative flex items-start gap-8 md:gap-12">
                            <div
                                class="relative z-10 flex h-10 w-10 shrink-0 items-center justify-center rounded-full border-2 border-accent bg-card md:absolute md:left-1/2 md:-translate-x-1/2"
                            >
                                <div class="h-2 w-2 rounded-full bg-accent" />
                            </div>

                            <div
                                class="flex-1 md:grid md:grid-cols-2 md:gap-12"
                            >
                                <div class="hidden md:block" />
                                <div>
                                    <p
                                        class="mb-2 text-sm font-medium tracking-wide text-accent"
                                    >
                                        7:00 PM
                                    </p>
                                    <h3
                                        class="mb-3 font-display text-2xl text-foreground md:text-3xl"
                                    >
                                        Dinner &amp; Reception
                                    </h3>
                                    <p
                                        class="text-base leading-relaxed text-muted-foreground"
                                    >
                                        Dinner, dancing, and the main
                                        celebration.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- View on Map CTA -->
                <div class="mt-16 text-center">
                    <a
                        href="https://maps.google.com/?q=LaLinda+Eventos+Maringá"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 text-sm font-medium tracking-wide text-accent transition-colors hover:text-accent/80"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="16"
                            height="16"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path
                                d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"
                            />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                        <span>View LaLinda Eventos on Map</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- ==============================================
             SECTION 4: Travel Guide — dark surface
             ============================================== -->
        <section
            id="travel"
            class="bg-primary py-28 text-primary-foreground md:py-44"
        >
            <div class="mx-auto max-w-5xl px-6">
                <!-- Header -->
                <div class="mb-16 text-center">
                    <div class="mb-5 flex items-center justify-center gap-4">
                        <Plane :size="24" class="text-accent" />
                        <p class="type-label text-accent">Travel Guide</p>
                    </div>
                    <h2
                        class="font-display text-4xl leading-[1.15] md:text-5xl"
                    >
                        Journey to Maringá
                    </h2>
                </div>

                <!-- Hero Image -->
                <div class="mb-12">
                    <div
                        class="relative aspect-[21/9] overflow-hidden rounded-2xl md:aspect-[24/9]"
                    >
                        <img
                            src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?w=1600&q=80"
                            alt="Maringá Cathedral"
                            class="h-full w-full object-cover"
                        />
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"
                        />
                    </div>
                    <p
                        class="mx-auto mt-6 max-w-2xl text-center text-base leading-relaxed text-primary-foreground/60"
                    >
                        Maringá is a green city in southern Brazil known for its
                        beautiful parks and iconic cathedral. We can't wait to
                        celebrate with you here.
                    </p>
                </div>

                <!-- Getting There -->
                <div class="mb-12">
                    <h3
                        class="mb-6 text-center font-display text-xl text-accent"
                    >
                        Getting There
                    </h3>
                    <div
                        class="rounded-xl border border-primary-foreground/15 bg-primary-foreground/[0.07] p-8"
                    >
                        <p
                            class="mb-4 text-sm leading-relaxed text-primary-foreground/70"
                        >
                            Fly from Bogotá (BOG) or Medellín (MDE) to São Paulo
                            (GRU), then connect to Maringá (MGF). Total journey
                            time is approximately 6-7 hours. LATAM and Avianca
                            offer the most convenient connections.
                        </p>
                        <p
                            class="text-sm leading-relaxed text-primary-foreground/70"
                        >
                            Maringá Regional Airport is small and easy to
                            navigate. Taxis and ride-sharing apps are readily
                            available—the drive to the city center takes about
                            15 minutes.
                        </p>
                    </div>
                </div>

                <!-- Where to Stay -->
                <div class="mb-12">
                    <h3
                        class="mb-6 text-center font-display text-xl text-accent"
                    >
                        Where to Stay
                    </h3>
                    <div
                        class="rounded-xl border border-primary-foreground/15 bg-primary-foreground/[0.07] p-8"
                    >
                        <p
                            class="mb-4 text-sm leading-relaxed text-primary-foreground/70"
                        >
                            We recommend staying in <strong>Zona 7</strong> or
                            <strong>Novo Centro</strong> for easy access to
                            LaLinda Eventos and the city's best restaurants.
                        </p>
                        <p
                            class="text-sm leading-relaxed text-primary-foreground/70"
                        >
                            <strong>Suggested hotels:</strong> Meliá Maringá,
                            Deville Prime, Comfort Hotel, and Slaviero
                            Executive. All are within 10-15 minutes of the
                            venue.
                        </p>
                    </div>
                </div>

                <!-- Practical Info -->
                <div class="mb-12">
                    <h3
                        class="mb-6 text-center font-display text-xl text-accent"
                    >
                        Practical Info
                    </h3>
                    <div
                        class="rounded-xl border border-primary-foreground/15 bg-primary-foreground/[0.07] p-8"
                    >
                        <div
                            class="space-y-4 text-sm leading-relaxed text-primary-foreground/70"
                        >
                            <p>
                                <strong>Documents:</strong> Colombian citizens
                                don't need a visa for Brazil (stays up to 90
                                days). Bring your passport valid for at least 6
                                months.
                            </p>
                            <p>
                                <strong>Currency:</strong> Brazilian Real (BRL).
                                Credit cards widely accepted. ATMs available at
                                airports and throughout the city.
                            </p>
                            <p>
                                <strong>Language:</strong> Portuguese is the
                                official language. Spanish is understood in
                                tourist areas.
                            </p>
                            <p>
                                <strong>Weather in April:</strong> Warm and
                                pleasant, 20-28°C (68-82°F). Occasional rain
                                showers. Pack light layers and comfortable
                                shoes.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Explore Maringá -->
                <div class="text-center">
                    <h3
                        class="mb-6 font-display text-lg text-primary-foreground/60"
                    >
                        While You're Here
                    </h3>
                    <div
                        class="mx-auto max-w-2xl space-y-2 text-sm text-primary-foreground/50"
                    >
                        <p>
                            Catedral Basílica · Parque do Ingá · Japanese Garden
                        </p>
                        <p>
                            Local cuisine: feijoada, pão de queijo,
                            churrascarias
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==============================================
             SECTION 5: Dress Code — 2 image cards
             ============================================== -->
        <section id="dress-code" class="bg-muted py-28 md:py-44">
            <div class="mx-auto max-w-3xl px-6">
                <div class="mb-12 text-center">
                    <p class="type-label mb-5 text-accent">What to Wear</p>
                    <h2
                        class="mb-6 font-display text-4xl leading-[1.15] text-foreground md:text-5xl"
                    >
                        Dress Code
                    </h2>
                    <p class="text-base text-muted-foreground">
                        Tropical Elegance — sophisticated yet comfortable for
                        Maringá's warm April weather.
                    </p>
                </div>

                <!-- Guidance Grid -->
                <div class="mb-10 grid gap-8 md:grid-cols-2">
                    <!-- Ladies -->
                    <div class="text-left">
                        <h3 class="mb-4 font-display text-xl text-foreground">
                            For the Ladies
                        </h3>
                        <ul
                            class="space-y-2 text-sm leading-relaxed text-muted-foreground"
                        >
                            <li>Flowing dresses or elegant jumpsuits</li>
                            <li>Soft colors and breathable fabrics</li>
                            <li>Wedges or block heels recommended</li>
                            <li>Light shawl for air-conditioned spaces</li>
                        </ul>
                    </div>

                    <!-- Gentlemen -->
                    <div class="text-left">
                        <h3 class="mb-4 font-display text-xl text-foreground">
                            For the Gentlemen
                        </h3>
                        <ul
                            class="space-y-2 text-sm leading-relaxed text-muted-foreground"
                        >
                            <li>Light suits in linen or cotton</li>
                            <li>Tie optional</li>
                            <li>Breathable fabrics recommended</li>
                            <li>Loafers or dress shoes</li>
                        </ul>
                    </div>
                </div>

                <!-- Weather Note -->
                <div class="text-center">
                    <p class="text-sm text-muted-foreground/70">
                        <strong>April in Maringá:</strong> 20-28°C (68-82°F)
                        with possible evening showers.
                    </p>
                </div>
            </div>
        </section>

        <!-- ==============================================
             SECTION 6: Final CTA — dark surface with overlay
             ============================================== -->
        <section id="rsvp" class="relative bg-primary py-32 md:py-40">
            <!-- Final CTA background image -->
            <img
                src="https://images.unsplash.com/photo-1465495976277-4387d4b0b4c6?w=1920&q=80"
                alt="Wedding celebration"
                class="absolute inset-0 h-full w-full object-cover"
            />
            <div class="absolute inset-0 bg-black/50" />
            <div class="relative z-10 px-6 text-center text-primary-foreground">
                <h2
                    class="font-display text-4xl leading-[1.15] md:text-5xl lg:text-6xl"
                >
                    We can't wait to celebrate with you
                </h2>
                <p
                    class="mx-auto mt-8 mb-10 max-w-xl text-xl leading-relaxed text-primary-foreground/80"
                >
                    Please let us know if you'll be joining us for our special
                    day.
                </p>
                <Button
                    size="lg"
                    class="rounded-full bg-accent/90 px-12 font-medium tracking-wide text-accent-foreground hover:bg-accent"
                >
                    Confirm Your Presence
                </Button>
            </div>
        </section>

        <!-- ==============================================
             FOOTER
             ============================================== -->
        <footer
            class="border-t border-primary-foreground/10 bg-primary py-20 text-primary-foreground"
        >
            <div class="mx-auto max-w-4xl px-6">
                <!-- Quick Links -->
                <div class="mb-12 flex flex-wrap justify-center gap-6">
                    <button
                        @click="scrollToSection('story')"
                        class="text-sm text-primary-foreground/60 transition-colors hover:text-accent"
                    >
                        Our Story
                    </button>
                    <button
                        @click="scrollToSection('travel')"
                        class="text-sm text-primary-foreground/60 transition-colors hover:text-accent"
                    >
                        Travel
                    </button>
                    <button
                        @click="scrollToSection('dress-code')"
                        class="text-sm text-primary-foreground/60 transition-colors hover:text-accent"
                    >
                        Dress Code
                    </button>
                    <button
                        @click="scrollToSection('rsvp')"
                        class="text-sm text-primary-foreground/60 transition-colors hover:text-accent"
                    >
                        RSVP
                    </button>
                </div>

                <!-- Couple Names -->
                <div class="mb-8 text-center">
                    <p class="font-display text-2xl">Lauana &amp; David</p>
                    <p class="mt-2 text-sm text-primary-foreground/60">
                        April 8, 2027 &middot; Maringá, Brazil
                    </p>
                </div>

                <!-- Contact Note -->
                <div class="mb-8 text-center">
                    <p
                        class="text-sm leading-relaxed text-primary-foreground/50"
                    >
                        Questions about travel or the event? Feel free to reach
                        out to us.
                    </p>
                </div>

                <!-- Language Selector -->
                <div class="mb-8 flex justify-center gap-4">
                    <button
                        class="text-xs tracking-wider text-accent transition-colors hover:text-accent/80"
                    >
                        EN
                    </button>
                    <span class="text-xs text-primary-foreground/30">|</span>
                    <button
                        class="text-xs tracking-wider text-primary-foreground/50 transition-colors hover:text-accent"
                    >
                        ES
                    </button>
                    <span class="text-xs text-primary-foreground/30">|</span>
                    <button
                        class="text-xs tracking-wider text-primary-foreground/50 transition-colors hover:text-accent"
                    >
                        PT
                    </button>
                </div>

                <!-- Heart Icon -->
                <div class="text-center">
                    <Heart :size="20" class="mx-auto text-accent" />
                </div>
            </div>
        </footer>
    </main>
</template>
