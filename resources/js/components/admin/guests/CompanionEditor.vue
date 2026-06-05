<script setup lang="ts">
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Select, SelectItem } from '@/components/ui/select';
import { getInitials } from '@/composables/useInitials';
import type { CompanionView, GuestLanguage } from '@/types/guests';
import { usePage } from '@inertiajs/vue3';
import { Check, X } from 'lucide-vue-next';
import { computed, nextTick, onMounted, ref, useTemplateRef } from 'vue';

const props = defineProps<{
    companion: CompanionView;
}>();

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

const nameInput = useTemplateRef('nameInput');
onMounted(() =>
    nextTick(() => (nameInput.value as any)?.$el?.focus()),
);

const name = ref(props.companion.name);
const surname = ref(props.companion.surname);
const language = ref<GuestLanguage>(props.companion.language);
const mobile = ref(props.companion.mobile ?? '');

const initials = computed(
    () => getInitials(`${name.value} ${surname.value}`.trim()) || '?',
);

const onSave = () =>
    emit('save', {
        name: name.value,
        surname: surname.value,
        language: language.value,
        mobile: mobile.value,
    });
</script>

<template>
    <tr class="bg-muted/20">
        <td class="w-px py-2 pr-2 pl-4"></td>
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
                        ref="nameInput"
                        v-model="name"
                        :placeholder="trans.create_name_placeholder"
                        class="h-9 max-w-40"
                    />
                    <Input
                        v-model="surname"
                        :placeholder="trans.create_surname_placeholder"
                        class="h-9 max-w-40"
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
        <td class="px-4 py-2">
            <Input
                v-model="mobile"
                :placeholder="trans.create_mobile_placeholder"
                class="mx-auto h-9 max-w-48"
            />
        </td>
        <td class="px-4 py-2">
            <div class="flex items-center justify-center gap-1">
                <Button
                    variant="ghost"
                    size="icon"
                    class="size-8 cursor-pointer hover:bg-primary/10 dark:hover:bg-primary/25"
                    :aria-label="trans.edit_confirm"
                    @click="onSave"
                >
                    <Check :size="16" class="text-primary" />
                </Button>
                <Button
                    variant="ghost"
                    size="icon"
                    class="size-8 cursor-pointer"
                    :aria-label="trans.edit_cancel"
                    @click="emit('cancel')"
                >
                    <X :size="16" />
                </Button>
            </div>
        </td>
        <td class="w-px py-2 pr-2.5 pl-2"></td>
    </tr>
</template>
