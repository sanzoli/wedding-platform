<script setup lang="ts">
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Select, SelectItem } from '@/components/ui/select';
import type { GuestLanguage } from '@/types/guest-list';
import { usePage } from '@inertiajs/vue3';
import { Check, X } from 'lucide-vue-next';
import { computed, ref } from 'vue';

const emit = defineEmits<{
    save: [
        payload: {
            name: string;
            surname: string;
            language: GuestLanguage;
        },
    ];
    cancel: [];
}>();

const trans = usePage().props.trans.guest_list as Record<string, string>;
trans.create_name_placeholder ??= 'Name';
trans.create_surname_placeholder ??= 'Surname';
trans.create_confirm ??= 'Confirm';
trans.create_cancel ??= 'Cancel';

const name = ref('');
const surname = ref('');
const language = ref<GuestLanguage>('es');

const initials = computed(() => {
    const f = name.value.charAt(0).toUpperCase();
    const l = surname.value.charAt(0).toUpperCase();
    return `${f}${l}` || '?';
});

// Companions are allowed to save with empty name/surname; the parent flips
// the row to a "pending" placeholder in that case.
const onSave = () => {
    emit('save', {
        name: name.value.trim(),
        surname: surname.value.trim(),
        language: language.value,
    });
};
</script>

<template>
    <tr
        class="animate-in fade-in-0 slide-in-from-top-2 bg-muted/20 duration-300"
    >
        <td class="py-2 pr-4 pl-12">
            <div
                class="flex items-center gap-3 border-l border-border/40 pl-4"
            >
                <Avatar>
                    <AvatarFallback
                        class="admin-type-action bg-muted text-muted-foreground"
                        >{{ initials }}</AvatarFallback
                    >
                </Avatar>
                <div class="flex gap-2">
                    <Input
                        v-model="name"
                        autofocus
                        :placeholder="trans.create_name_placeholder"
                        class="h-9 max-w-[10rem]"
                    />
                    <Input
                        v-model="surname"
                        :placeholder="trans.create_surname_placeholder"
                        class="h-9 max-w-[10rem]"
                    />
                </div>
            </div>
        </td>
        <td class="px-4 py-2"></td>
        <td class="px-4 py-2">
            <Select v-model="language">
                <SelectItem value="es">ES</SelectItem>
                <SelectItem value="pt">PT</SelectItem>
                <SelectItem value="en">EN</SelectItem>
            </Select>
        </td>
        <td class="px-4 py-2"></td>
        <td class="px-4 py-2">
            <div class="flex items-center justify-end gap-1">
                <Button
                    variant="ghost"
                    size="icon"
                    class="size-8 cursor-pointer hover:bg-primary/10"
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
    </tr>
</template>
