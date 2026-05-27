import { QueryOptions, SortOptions } from '@/types';
import { isReactive, reactive } from 'vue';

export function useSortOptions(
    queryOptions?: QueryOptions,
): [SortOptions, (type: string, direction: 'asc' | 'desc' | null) => void] {
    const sortOptions: SortOptions = reactive({
        type: queryOptions?.sortBy,
        direction: queryOptions?.sort,
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
