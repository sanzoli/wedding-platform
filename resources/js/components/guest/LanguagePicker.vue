<script setup lang="ts">
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import type { Language } from '@/types/save-the-date';
import { Check, ChevronDown } from 'lucide-vue-next';
import type { Component } from 'vue';
import BR from './flags/BR.vue';
import CO from './flags/CO.vue';
import US from './flags/US.vue';

defineProps<{
    languages: Record<string, Language>;
    modelValue: string;
}>();

const emit = defineEmits<{ 'update:modelValue': [code: string] }>();

const flags: Record<string, Component> = { en: US, es: CO, pt: BR };
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <button
                type="button"
                class="inline-flex min-h-11 items-center gap-1.5 px-1 opacity-80 transition hover:text-accent hover:opacity-100 focus-visible:text-accent focus-visible:underline focus-visible:underline-offset-4 focus-visible:outline-none"
                aria-label="Idioma"
            >
                <component :is="flags[modelValue]" class="size-[18px]" />
                <span class="text-xs font-medium tracking-wide uppercase">{{
                    modelValue
                }}</span>
                <ChevronDown class="size-3 opacity-70" aria-hidden="true" />
            </button>
        </DropdownMenuTrigger>

        <DropdownMenuContent align="end" class="min-w-48">
            <DropdownMenuItem
                v-for="(language, code) in languages"
                :key="code"
                class="justify-between gap-3"
                @select="emit('update:modelValue', code)"
            >
                <span class="flex items-center gap-2.5">
                    <component :is="flags[code]" class="size-5 shrink-0" />
                    {{ language.label }}
                </span>
                <Check
                    v-if="modelValue === code"
                    class="size-4 text-accent"
                    aria-hidden="true"
                />
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
