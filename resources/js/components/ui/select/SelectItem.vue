<script setup lang="ts">
import type { SelectItemProps } from 'reka-ui'
import { SelectItem, SelectItemIndicator, SelectItemText } from 'reka-ui'
import { Check } from 'lucide-vue-next';

const props = defineProps<SelectItemProps>()
</script>

<template>
    <SelectItem v-bind="props" class="relative flex w-full cursor-pointer select-none items-center rounded-sm py-1.5 pl-2 pr-8 text-sm outline-none focus:bg-accent focus:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50">
        <SelectItemIndicator class="absolute right-2 flex size-3.5 items-center justify-center">
            <Check class="size-4"/>
        </SelectItemIndicator>
        <SelectItemText>
            <slot />
        </SelectItemText>
    </SelectItem>
</template>
