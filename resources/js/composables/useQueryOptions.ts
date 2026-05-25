import { QueryOptions } from '@/types';
import { router } from '@inertiajs/vue3';
import { watchDebounced } from '@vueuse/core';
import { reactive } from 'vue';

export function useQueryOptions(url: URL | string, options?: QueryOptions) {
    const queryOptions = reactive({
        search: options?.search,
    });

    watchDebounced(
        queryOptions,
        () =>
            router.get(url, queryOptions, {
                preserveState: true,
                replace: true,
            }),
        { deep: true, debounce: 300 },
    );

    return queryOptions;
}
