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
import { InertiaForm, router } from '@inertiajs/vue3';
import { SweetAlertResult } from 'sweetalert2';

const onError: (errors: object) => Promise<SweetAlertResult<Awaited<any>>> = (
    errors: object,
): Promise<SweetAlertResult<Awaited<any>>> =>
    toastError(Object.values(errors)[0]);

export function deleteGuest(guest: Guest) {
    return confirmDelete(() =>
        router.delete(destroy.url(guest), {
            only: ['guestGroups'],
            preserveScroll: true,
            onError,
        }),
    );
}

export function storeGuest(form: InertiaForm<Guest>, options?: object) {
    form.lang ??= '';
    return form.submit(store(), {
        preserveScroll: true,
        onError,
        ...options,
    });
}

export function updateGuest(form: InertiaForm<Guest>, options?: object) {
    form.lang ??= '';
    return form.submit(update(form), {
        preserveScroll: true,
        onError,
        ...options,
    });
}

export function leaveGroup(guest: Guest, options?: object) {
    return router.post(leave(guest), {
        preserveScroll: true,
        onError,
        ...options,
    });
}

export function splitGroup(guest: Guest, options?: object) {
    return router.post(split(guest.group_id), {
        preserveScroll: true,
        onError,
        ...options,
    });
}
