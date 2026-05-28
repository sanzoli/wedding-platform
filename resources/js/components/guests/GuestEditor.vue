<script setup lang="ts">
import { store, update } from '@/actions/App/Http/Controllers/GuestController';
import IconButton from '@/components/IconButton.vue';
import { Input } from '@/components/ui/input';
import { Select, SelectItem } from '@/components/ui/select';
import { toastError } from '@/composables/admin/useAlert';
import { Guest } from '@/types/guests';
import { useForm, usePage } from '@inertiajs/vue3';
import { Check, X } from 'lucide-vue-next';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { computed } from 'vue';

const emit = defineEmits(['close']);
const props = defineProps<{
    guest?: Guest;
}>();

const languages = usePage().props.languages;

const form = useForm({
    first_name: props.guest?.first_name ?? '',
    last_name: props.guest?.last_name ?? '',
    lang: props.guest?.lang ?? '',
    mobile: props.guest?.mobile ?? '',
});

const save = () => {
    form.lang ??= '';
    form.submit(props.guest ? update(props.guest) : store(), {
        preserveScroll: true,
        onSuccess: () => emit('close'),
        onError: (errors: object) => toastError(Object.values(errors)[0]),
    });
};

const initials = computed(() => (form.first_name.at(0) ?? '') + (form.last_name.at(0) ?? ''));
</script>

<template>
    <tr class="bg-muted/20">
        <td class="px-4 py-3">
            <div class="flex items-center gap-3">
                <Avatar>
                    <AvatarFallback class="admin-type-action bg-secondary text-secondary-foreground">
                        {{ initials }}
                    </AvatarFallback>
                </Avatar>
                <div class="flex gap-2">
                    <Input
                        v-model="form.first_name"
                        @keydown.enter="save"
                        @keydown.esc="$emit('close')"
                        type="text"
                        name="first_name"
                        placeholder="name"
                        class="h-9 max-w-48"
                    />
                    <Input
                        v-model="form.last_name"
                        @keydown.enter="save"
                        @keydown.esc="$emit('close')"
                        type="text"
                        name="last_name"
                        placeholder="surname"
                        class="h-9 max-w-48"
                    />
                </div>
            </div>
        </td>
        <td class="admin-type-data px-4 py-3 text-center text-muted-foreground"></td>
        <td class="px-4 py-3">
            <Select v-model="form.lang" name="lang">
                <SelectItem v-for="(data, value) in languages" :value :key="value"
                    >{{ data.flag }} {{ data.label }}</SelectItem
                >
            </Select>
        </td>
        <td class="px-4 py-3">
            <Input
                v-model="form.mobile"
                @keydown.enter="save"
                @keydown.esc="$emit('close')"
                type="text"
                name="mobile"
                class="h-9 max-w-40"
            />
        </td>
        <td class="px-4 py-3">
            <div class="flex items-center justify-center gap-1">
                <IconButton
                    @click="save"
                    :data-test="guest ? 'guest-update-button-' + guest.id : 'guest-store-button'"
                    class="hover:bg-muted hover:text-foreground"
                >
                    <Check></Check>
                </IconButton>
                <IconButton @click="$emit('close')" class="hover:bg-destructive/10 hover:text-destructive">
                    <X></X>
                </IconButton>
            </div>
        </td>
    </tr>
</template>
