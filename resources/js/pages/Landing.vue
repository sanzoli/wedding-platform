<script setup lang="ts">
import ThemeToggle from '@/components/ThemeToggle.vue';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { Head, Link } from '@inertiajs/vue3';
import {
    ArrowRight,
    CalendarDays,
    Heart,
    ListChecks,
    Users,
    Wallet,
} from 'lucide-vue-next';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
}>();

const features = [
    {
        icon: Wallet,
        title: 'Budget tracking',
        description:
            'Set your total budget and track every expense with clarity. Know exactly where your money goes.',
    },
    {
        icon: CalendarDays,
        title: 'Timeline planning',
        description:
            'Organize tasks by date and priority so nothing falls through the cracks before the big day.',
    },
    {
        icon: Users,
        title: 'Guest management',
        description:
            'Keep your guest list organized with RSVPs, dietary needs, and seating all in one place.',
    },
    {
        icon: ListChecks,
        title: 'Vendor coordination',
        description:
            'Track contracts, payments, and deadlines for every vendor you work with.',
    },
    {
        icon: Heart,
        title: 'Wishlist & registry',
        description:
            'Curate your wishlist and share it with guests through a simple, elegant link.',
    },
    {
        icon: CalendarDays,
        title: 'Day-of schedule',
        description:
            'Build a minute-by-minute timeline for the wedding day so everyone knows their role.',
    },
];

const steps = [
    {
        number: '01',
        title: 'Set your vision',
        description:
            'Start by defining your budget, date, and priorities. We help you structure everything from the very first step.',
    },
    {
        number: '02',
        title: 'Plan with clarity',
        description:
            'Use our tools to manage vendors, guests, and timelines. Every detail lives in one calm, organized space.',
    },
    {
        number: '03',
        title: 'Enjoy your day',
        description:
            'Arrive at your wedding feeling prepared and present. No loose ends, no last-minute surprises.',
    },
];
</script>

<template>
    <Head title="Plan your wedding">
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link
            rel="preconnect"
            href="https://fonts.gstatic.com"
            crossorigin="anonymous"
        />
        <link
            href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap"
            rel="stylesheet"
        />
    </Head>

    <div class="min-h-screen bg-background text-foreground">
        <!-- Navigation -->
        <nav class="w-full border-b border-border/50">
            <div
                class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4"
            >
                <Link
                    href="/"
                    class="font-display text-xl tracking-tight text-foreground"
                >
                    Ceremonia
                </Link>
                <div class="flex items-center gap-3">
                    <ThemeToggle />
                    <Link v-if="canLogin" href="/login">
                        <Button variant="ghost" size="sm"> Log in </Button>
                    </Link>
                    <Link v-if="canRegister" href="/register">
                        <Button size="sm"> Get started </Button>
                    </Link>
                </div>
            </div>
        </nav>

        <main>
            <!-- ============================================
                 SECTION 1: Hero
                 ============================================ -->
            <section class="py-20 lg:py-32">
                <div class="mx-auto max-w-6xl px-6">
                    <div
                        class="grid items-center gap-12 lg:grid-cols-2 lg:gap-20"
                    >
                        <!-- Text block -->
                        <div class="max-w-xl">
                            <p class="type-label mb-6 text-muted-foreground">
                                Wedding planning, simplified
                            </p>
                            <h1
                                class="font-display text-4xl leading-[1.15] tracking-tight text-foreground sm:text-5xl lg:text-6xl"
                            >
                                Plan the wedding you actually want
                            </h1>
                            <p
                                class="type-body mt-6 max-w-md text-muted-foreground"
                            >
                                A calm, organized space to manage your budget,
                                guests, vendors, and every detail — so you can
                                focus on what matters most.
                            </p>
                            <div
                                class="mt-10 flex flex-wrap items-center gap-4"
                            >
                                <Button size="lg">
                                    Start planning
                                    <ArrowRight :size="16" />
                                </Button>
                                <Button variant="outline" size="lg">
                                    See how it works
                                </Button>
                            </div>
                        </div>

                        <!-- Visual block -->
                        <div class="relative flex items-center justify-center">
                            <div
                                class="aspect-[4/3] w-full rounded-2xl border border-border bg-muted/50"
                            >
                                <div
                                    class="flex h-full flex-col items-center justify-center gap-3 p-8"
                                >
                                    <Heart
                                        :size="32"
                                        class="text-muted-foreground/40"
                                    />
                                    <span
                                        class="type-label text-muted-foreground/60"
                                    >
                                        Product preview
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ============================================
                 SECTION 2: Trust / Social Proof
                 ============================================ -->
            <section class="border-y border-border/50 py-16">
                <div class="mx-auto max-w-6xl px-6 text-center">
                    <p class="type-body text-muted-foreground">
                        Trusted by couples planning weddings across Latin
                        America
                    </p>
                    <div
                        class="mt-10 flex flex-wrap items-center justify-center gap-8 lg:gap-16"
                    >
                        <div
                            v-for="n in 5"
                            :key="n"
                            class="h-8 w-24 rounded-md bg-muted"
                        />
                    </div>
                </div>
            </section>

            <!-- ============================================
                 SECTION 3: Feature Grid
                 ============================================ -->
            <section class="py-24 lg:py-32">
                <div class="mx-auto max-w-6xl px-6">
                    <div class="mx-auto max-w-2xl text-center">
                        <p class="type-label mb-4 text-muted-foreground">
                            Features
                        </p>
                        <h2
                            class="font-display text-3xl tracking-tight text-foreground sm:text-4xl"
                        >
                            Everything you need, nothing you don't
                        </h2>
                        <p class="type-body mt-4 text-muted-foreground">
                            Thoughtfully designed tools that keep your planning
                            focused and stress-free.
                        </p>
                    </div>

                    <div class="mt-16 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <article
                            v-for="feature in features"
                            :key="feature.title"
                            class="group rounded-xl border border-border bg-card p-8 transition-colors hover:bg-muted/50"
                        >
                            <div
                                class="mb-5 flex h-10 w-10 items-center justify-center rounded-lg bg-muted"
                            >
                                <component
                                    :is="feature.icon"
                                    :size="20"
                                    class="text-foreground"
                                />
                            </div>
                            <h3 class="font-display text-lg text-foreground">
                                {{ feature.title }}
                            </h3>
                            <p
                                class="mt-2 text-sm leading-relaxed text-muted-foreground"
                            >
                                {{ feature.description }}
                            </p>
                        </article>
                    </div>
                </div>
            </section>

            <Separator class="mx-auto max-w-6xl" />

            <!-- ============================================
                 SECTION 4: Product Preview
                 ============================================ -->
            <section class="py-24 lg:py-32">
                <div class="mx-auto max-w-6xl px-6">
                    <div class="mx-auto max-w-2xl text-center">
                        <p class="type-label mb-4 text-muted-foreground">
                            How it looks
                        </p>
                        <h2
                            class="font-display text-3xl tracking-tight text-foreground sm:text-4xl"
                        >
                            A space designed to feel calm
                        </h2>
                        <p class="type-body mt-4 text-muted-foreground">
                            Clean interfaces, clear information, and intentional
                            design — so planning feels like a joy, not a chore.
                        </p>
                    </div>

                    <div
                        class="mx-auto mt-16 max-w-4xl overflow-hidden rounded-xl border border-border bg-muted"
                    >
                        <div
                            class="flex aspect-video flex-col items-center justify-center gap-3 p-8"
                        >
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full bg-background"
                            >
                                <Heart
                                    :size="20"
                                    class="text-muted-foreground/50"
                                />
                            </div>
                            <span class="type-label text-muted-foreground/60">
                                Application screenshot
                            </span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ============================================
                 SECTION 5: Workflow / Steps
                 ============================================ -->
            <section class="bg-muted/40 py-24 lg:py-32">
                <div class="mx-auto max-w-6xl px-6">
                    <div class="mx-auto max-w-2xl text-center">
                        <p class="type-label mb-4 text-muted-foreground">
                            How it works
                        </p>
                        <h2
                            class="font-display text-3xl tracking-tight text-foreground sm:text-4xl"
                        >
                            Three steps to a stress-free wedding
                        </h2>
                    </div>

                    <div class="mt-16 grid gap-8 lg:grid-cols-3">
                        <article
                            v-for="step in steps"
                            :key="step.number"
                            class="flex flex-col items-start rounded-xl border border-border bg-card p-8"
                        >
                            <span
                                class="mb-6 flex h-12 w-12 items-center justify-center rounded-full bg-primary font-display text-sm text-primary-foreground"
                            >
                                {{ step.number }}
                            </span>
                            <h3 class="font-display text-xl text-foreground">
                                {{ step.title }}
                            </h3>
                            <p
                                class="mt-3 text-sm leading-relaxed text-muted-foreground"
                            >
                                {{ step.description }}
                            </p>
                        </article>
                    </div>
                </div>
            </section>

            <!-- ============================================
                 SECTION 6: Final CTA
                 ============================================ -->
            <section class="py-24 lg:py-32">
                <div class="mx-auto max-w-6xl px-6">
                    <div
                        class="mx-auto max-w-3xl rounded-2xl border border-border bg-muted/50 px-8 py-16 text-center sm:px-16 sm:py-20"
                    >
                        <h2
                            class="font-display text-3xl tracking-tight text-foreground sm:text-4xl"
                        >
                            Start planning your wedding today
                        </h2>
                        <p
                            class="type-body mx-auto mt-4 max-w-lg text-muted-foreground"
                        >
                            Join couples who are planning their wedding with
                            intention, clarity, and calm. It's free to get
                            started.
                        </p>
                        <div
                            class="mt-10 flex flex-wrap items-center justify-center gap-4"
                        >
                            <Button size="lg">
                                Create your free account
                                <ArrowRight :size="16" />
                            </Button>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="border-t border-border/50 py-12">
            <div class="mx-auto max-w-6xl px-6">
                <div
                    class="flex flex-col items-center justify-between gap-4 sm:flex-row"
                >
                    <span
                        class="font-display text-lg tracking-tight text-foreground"
                    >
                        Ceremonia
                    </span>
                    <p class="text-sm text-muted-foreground">
                        &copy; {{ new Date().getFullYear() }} Ceremonia. All
                        rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
