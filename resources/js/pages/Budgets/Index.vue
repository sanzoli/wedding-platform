<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { store } from '@/routes/budgets';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    name: 'Wedding Budget',
    draft: false,
});

const createBudget = () => {
    form.post(store.url());
};
</script>

<template>
    <div class="flex min-h-screen items-center justify-center bg-background">
        <div class="space-y-3 text-center">
            <p class="text-lg font-medium text-foreground">
                Start planning your budget
            </p>
            <p class="text-sm text-muted-foreground">
                Set up your planning workspace
            </p>
            <Button @click="createBudget" :disabled="form.processing">
                Create Budget
            </Button>
            <p
                v-if="form.hasErrors"
                class="text-sm font-medium text-destructive"
            >
                Something went wrong. Please try again.
            </p>
        </div>
    </div>
</template>
