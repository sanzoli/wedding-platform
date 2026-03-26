<script setup lang="ts">
import type { SelectRootEmits, SelectRootProps } from 'reka-ui'
import { SelectContent, SelectIcon, SelectPortal, SelectRoot, SelectScrollDownButton, SelectTrigger, SelectValue, SelectViewport, useForwardPropsEmits } from 'reka-ui'
import { ChevronDown, X } from 'lucide-vue-next';

const props = defineProps<SelectRootProps>()
const emits = defineEmits<SelectRootEmits>()

const forward = useForwardPropsEmits(props, emits)
</script>

<template>
    <SelectRoot v-bind="forward">
        <SelectTrigger
            data-placeholder
            class="flex h-9 w-full items-center justify-between gap-2 whitespace-nowrap rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-xs transition-[color,box-shadow] outline-none placeholder:text-muted-foreground disabled:cursor-not-allowed disabled:opacity-50 md:text-sm focus:border-ring focus:ring-ring/50 focus:ring-[3px] data-[placeholder]:text-muted-foreground"
        >
            <SelectValue />
            <SelectIcon as-child>
                <ChevronDown class="size-4 shrink-0 opacity-50" />
            </SelectIcon>
        </SelectTrigger>
        <SelectPortal>
            <SelectContent
                position="popper"
                class="relative z-50 max-h-96 min-w-32 overflow-hidden rounded-md border bg-popover text-popover-foreground shadow-md data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95 data-[side=bottom]:slide-in-from-top-2 data-[side=left]:slide-in-from-right-2 data-[side=right]:slide-in-from-left-2 data-[side=top]:slide-in-from-bottom-2 data-[side=bottom]:translate-y-1 data-[side=left]:-translate-x-1 data-[side=right]:translate-x-1 data-[side=top]:-translate-y-1"
            >
                <SelectViewport class="p-1">
                    <slot />
                </SelectViewport>
                <SelectScrollDownButton>
                    <ChevronDown />
                </SelectScrollDownButton>
            </SelectContent>
        </SelectPortal>
    </SelectRoot>
</template>
