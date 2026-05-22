<script setup lang="ts">
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Select, SelectItem } from '@/components/ui/select';
import { getInitials } from '@/composables/useInitials';
import type { GuestLanguage } from '@/types/guests';
import { usePage } from '@inertiajs/vue3';
import { Check, X } from 'lucide-vue-next';
import { computed, ref } from 'vue';

const emit = defineEmits<{
    save: [
        payload: {
            name: string;
            surname: string;
            language: GuestLanguage;
            mobile: string;
        },
    ];
    cancel: [];
}>();

const trans = usePage().props.trans.guests as Record<string, string>;

const name = ref('');
const surname = ref('');
const language = ref<GuestLanguage>('es');
const mobile = ref('');

const initials = computed(
    () => getInitials(`${name.value} ${surname.value}`.trim()) || '?',
);

const canSave = computed(
    () => name.value.trim() !== '' || surname.value.trim() !== '',
);

const onSave = () => {
    if (!canSave.value) return;
    emit('save', {
        name: name.value.trim(),
        surname: surname.value.trim(),
        language: language.value,
        mobile: mobile.value.trim(),
    });
};

// TODO(backend): swap the handler above for the Inertia version below;
// then delete handleCreateGroup in Guests.vue.
//   import { router } from '@inertiajs/vue3';
//   import { store } from '@/actions/App/Http/Controllers/GuestGroupsController';
//   const onSave = () => {
//       if (!canSave.value) return;
//       router.post(store(), {
//           name: name.value.trim(),
//           surname: surname.value.trim(),
//           language: language.value,
//           mobile: mobile.value.trim(),
//       }, { only: ['guests'], onSuccess: () => emit('cancel') });
//   };
</script>

<template>
    <tr
        class="animate-in fade-in-0 slide-in-from-top-2 bg-muted/20 duration-300"
    >
        <td class="px-4 py-3">
            <div class="flex items-center gap-3 pl-7">
                <Avatar>
                    <AvatarFallback
                        class="admin-type-action bg-secondary text-secondary-foreground"
                        >{{ initials }}</AvatarFallback
                    >
                </Avatar>
                <div class="flex gap-2">
                    <Input
                        v-model="name"
                        autofocus
                        :placeholder="trans.create_name_placeholder"
                        class="h-9 max-w-[10rem]"
                        @keydown.enter="onSave"
                        @keydown.esc="emit('cancel')"
                    />
                    <Input
                        v-model="surname"
                        :placeholder="trans.create_surname_placeholder"
                        class="h-9 max-w-[10rem]"
                        @keydown.enter="onSave"
                        @keydown.esc="emit('cancel')"
                    />
                </div>
            </div>
        </td>
        <td class="admin-type-data px-4 py-3 text-muted-foreground">1</td>
        <td class="px-4 py-3">
            <Select v-model="language">
                <SelectItem value="es">ES</SelectItem>
                <SelectItem value="pt">PT</SelectItem>
                <SelectItem value="en">EN</SelectItem>
            </Select>
        </td>
        <td class="px-4 py-3">
            <Input
                v-model="mobile"
                :placeholder="trans.create_mobile_placeholder"
                class="h-9 max-w-[12rem]"
                @keydown.enter="onSave"
                @keydown.esc="emit('cancel')"
            />
        </td>
        <td class="px-4 py-3">
            <div class="flex items-center justify-center gap-1">
                <Button
                    variant="ghost"
                    size="icon"
                    class="size-8 cursor-pointer hover:bg-primary/10 dark:hover:bg-primary/25"
                    :disabled="!canSave"
                    :aria-label="trans.create_confirm"
                    @click="onSave"
                >
                    <Check :size="16" class="text-primary" />
                </Button>
                <Button
                    variant="ghost"
                    size="icon"
                    class="size-8 cursor-pointer"
                    :aria-label="trans.create_cancel"
                    @click="emit('cancel')"
                >
                    <X :size="16" />
                </Button>
            </div>
        </td>
        <td class="w-px py-3 pr-4 pl-2"></td>
    </tr>
</template>
