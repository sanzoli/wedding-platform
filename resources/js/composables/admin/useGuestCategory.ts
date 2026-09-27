import {
    add,
    remove,
} from '@/actions/App/Http/Controllers/GuestCategoryController';
import { Category } from '@/types';
import { Guest } from '@/types/guests';
import { router } from '@inertiajs/vue3';

export function useGuestCategory(guest: Guest) {
    const addCategory = (category: Category) => {
        router.post(add({ guest, category }), {
            preserveState: true,
            preserveScroll: true,
        });
    };

    const removeCategory = (category: Category) => {
        router.delete(remove({ guest, category }), {
            preserveState: true,
            preserveScroll: true,
        });
    };

    return { addCategory, removeCategory };
}
