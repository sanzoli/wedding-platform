import { QueryOptions, SortOptions } from '@/types';
import { isReactive, reactive } from 'vue';

export function useSortOptions(
    queryOptions?: QueryOptions,
): [SortOptions, (type: string, direction: 'asc' | 'desc' | null) => void] {
    const sortOptions: SortOptions = reactive({
        type: queryOptions?.sort,
        direction: queryOptions?.sortDirection,
    });

    const sort = function (type: string, direction: 'asc' | 'desc' | null) {
        sortOptions.direction = direction;
        sortOptions.type = direction ? type : null;

        if (queryOptions && isReactive(queryOptions)) {
            queryOptions.sortBy = sortOptions.type ?? undefined;
            queryOptions.sort = sortOptions.direction ?? undefined;
        }
    };

    return [sortOptions, sort];
}
