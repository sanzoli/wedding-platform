<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { show } from '@/routes/budgets';
import type { Budget } from '@/types';
import { router } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const error = ref<string | null>(null);
const isLoading = ref(true);

const getCsrfToken = (): string | null => {
    const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
    return match ? decodeURIComponent(match[1]) : null;
};

const redirectToBudget = (budgetId: string) => {
    router.visit(show.url(budgetId), { replace: true });
};

const requestJson = async <T,>(
    url: string,
    init: RequestInit = {},
): Promise<T> => {
    const response = await fetch(url, {
        credentials: 'same-origin',
        ...init,
        headers: {
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            ...(init.headers ?? {}),
        },
    });

    if (!response.ok) {
        throw new Error(`Request failed with status ${response.status}`);
    }

    return response.json() as Promise<T>;
};

const bootstrapBudget = async () => {
    error.value = null;
    isLoading.value = true;

    try {
        // TODO(api): replace local fetch with backend API call for budgets list
        const budgets = await requestJson<Budget[]>('/web/budgets');

        if (budgets.length > 0) {
            redirectToBudget(budgets[0].id);
            return;
        }

        // TODO(api): replace local create flow with backend POST for new budget
        const budget = await requestJson<Budget>('/web/budgets', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-XSRF-TOKEN': getCsrfToken() || '',
            },
            body: JSON.stringify({
                name: 'Wedding Budget',
                draft: false,
            }),
        });

        redirectToBudget(budget.id);
    } catch (err) {
        error.value =
            err instanceof Error ? err.message : 'An unexpected error occurred';
        isLoading.value = false;
    }
};

const retry = () => {
    bootstrapBudget();
};

onMounted(() => {
    bootstrapBudget();
});
</script>

<template>
    <div class="flex min-h-screen items-center justify-center bg-background">
        <div class="space-y-3 text-center">
            <template v-if="isLoading && !error">
                <p class="text-lg font-medium text-foreground">
                    Preparing your budget...
                </p>
                <p class="text-sm text-muted-foreground">
                    Setting up your planning workspace
                </p>
            </template>

            <template v-else-if="error">
                <p class="text-sm font-medium text-destructive">{{ error }}</p>
                <Button
                    variant="link"
                    @click="retry"
                    class="text-sm text-muted-foreground"
                >
                    Try again
                </Button>
            </template>
        </div>
    </div>
</template>
