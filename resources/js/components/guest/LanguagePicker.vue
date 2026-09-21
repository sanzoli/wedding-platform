<script setup lang="ts">
import BR from '@/components/guest/svg/flags/BR.vue';
import CO from '@/components/guest/svg/flags/CO.vue';
import US from '@/components/guest/svg/flags/US.vue';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import type { Language } from '@/types/save-the-date';
import { Check, ChevronDown } from 'lucide-vue-next';
import type { Component } from 'vue';

const model = defineModel<Language>({ required: true });

const languages: Record<Language, { name: string; flag: Component }> = {
    en: { name: 'English', flag: US },
    es: { name: 'Español', flag: CO },
    pt: { name: 'Português', flag: BR },
};
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <button
                type="button"
                class="inline-flex min-h-11 items-center gap-1.5 px-1 opacity-80 transition hover:text-accent hover:opacity-100 focus-visible:text-accent focus-visible:underline focus-visible:underline-offset-4 focus-visible:outline-none lg:min-h-9"
                aria-label="Idioma"
            >
                <component :is="languages[model].flag" class="size-4.5" />
                <span class="text-xs font-medium tracking-wide uppercase">{{
                    model
                }}</span>
                <ChevronDown class="size-3 opacity-70" aria-hidden="true" />
            </button>
        </DropdownMenuTrigger>

        <DropdownMenuContent
            align="end"
            class="guest-menu min-w-48 rounded-2xl"
        >
            <DropdownMenuItem
                v-for="(settings, code) in languages"
                :key="code"
                class="justify-between gap-3"
                @select="model = code"
            >
                <span class="flex items-center gap-2.5">
                    <component :is="settings.flag" class="size-5 shrink-0" />
                    {{ settings.name }}
                </span>
                <Check
                    v-if="model === code"
                    class="size-4 text-accent"
                    aria-hidden="true"
                />
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
