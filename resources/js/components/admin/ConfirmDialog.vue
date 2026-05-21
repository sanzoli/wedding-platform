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

/**
 * Confirmation dialog pattern.
 *
 * Wraps the AlertDialog primitive with a fixed shape (title, optional
 * description, confirm + cancel buttons) and a default styling that the
 * caller varies via `confirmVariant`.
 *
 * Use for any decision that must be answered before continuing: destructive
 * deletes, irreversible state changes, important sends. For modals that
 * collect input (forms, settings) use ui/dialog directly.
 */

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
