<script setup lang="ts">
import {
    AlertDialog,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { Button, buttonVariants } from '@/components/ui/button';
import { computed } from 'vue';

const props = withDefaults(
    defineProps<{
        open: boolean;
        title: string;
        description?: string;
        confirmLabel?: string;
        cancelLabel?: string;
        confirmVariant?: 'default' | 'destructive';
    }>(),
    {
        confirmLabel: 'Confirm',
        cancelLabel: 'Cancel',
        confirmVariant: 'default',
    },
);

const emit = defineEmits<{
    'update:open': [value: boolean];
    confirm: [];
}>();

const confirmClass = computed(() =>
    buttonVariants({ variant: props.confirmVariant }),
);
</script>

<template>
    <AlertDialog :open="open" @update:open="emit('update:open', $event)">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>{{ title }}</AlertDialogTitle>
                <AlertDialogDescription v-if="description">{{
                    description
                }}</AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel>{{ cancelLabel }}</AlertDialogCancel>
                <Button :class="confirmClass" @click="emit('confirm')">{{
                    confirmLabel
                }}</Button>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
