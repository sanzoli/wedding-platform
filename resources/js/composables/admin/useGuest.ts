import {
    destroy,
    store,
    update,
} from '@/actions/App/Http/Controllers/GuestController';
import {
    leave,
    split,
} from '@/actions/App/Http/Controllers/GuestGroupController';
import { confirmDelete, toastError } from '@/composables/admin/useAlert';
import { Guest } from '@/types/guests';
import { InertiaForm, router, useForm } from '@inertiajs/vue3';

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

export function leaveGroup(guest: Guest, options?: object) {
    return useForm().submit(leave(guest), {
        preserveScroll: true,
        onError: (errors: object) => toastError(Object.values(errors)[0]),
        ...options,
    });
}

export function splitGroup(guest: Guest, options?: object) {
    return useForm().submit(split(guest.group_id), {
        preserveScroll: true,
        onError: (errors: object) => toastError(Object.values(errors)[0]),
        ...options,
    });
}
