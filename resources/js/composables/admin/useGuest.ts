import {
    destroy,
    store,
    update,
} from '@/actions/App/Http/Controllers/GuestController';
import { confirmDelete, toastError } from '@/composables/admin/useAlert';
import { Guest } from '@/types/guests';
import { InertiaForm, router } from '@inertiajs/vue3';

export function deleteGuest(guest: Guest) {
    return confirmDelete(() =>
        router.delete(destroy.url(guest), {
            only: ['guestGroups'],
            preserveScroll: true,
        }),
    );
}

export function storeGuest(form: InertiaForm<Guest>, options?: object) {
    form.lang ??= '';
    return form.submit(store(), {
        preserveScroll: true,
        onError: (errors: object) => toastError(Object.values(errors)[0]),
        ...options,
    });
}

export function updateGuest(form: InertiaForm<Guest>, options?: object) {
    form.lang ??= '';
    return form.submit(update(form), {
        preserveScroll: true,
        onError: (errors: object) => toastError(Object.values(errors)[0]),
        ...options,
    });
}
