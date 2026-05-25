<script setup lang="ts">
import { store } from '@/actions/App/Http/Controllers/GuestController';
import IconButton from '@/components/IconButton.vue';
import { Input } from '@/components/ui/input';
import { toastError } from '@/composables/admin/useAlert';
import { useForm, usePage } from '@inertiajs/vue3';
import { Check, X } from 'lucide-vue-next';
import { Select, SelectItem } from '@/components/ui/select';

const emit = defineEmits(['close']);
const languages = usePage().props.languages;

const form = useForm({
    first_name: '',
    last_name: '',
    lang: '',
    mobile: '',
});

const save = () => {
    form.submit(store(), {
        preserveScroll: true,
        onSuccess: () => emit('close'),
        onError: (errors: object) => toastError(Object.values(errors)[0]),
    });
};
</script>

<template>
    <tr class="group hidden transition-colors hover:bg-muted/30 md:table-row">
        <td class="w-100 px-6 py-3 text-[15px] font-medium">
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
        </td>
        <td class="px-6 py-3 w-48">
            <Select v-model="form.lang" name="lang">
                <SelectItem v-for="(data, value) in languages" :value :key="value"
                    >{{ data.flag }} {{ data.label }}</SelectItem
                >
            </Select>
        </td>
        <td class="flex justify-center px-6 py-3">
            <Input
                v-model="form.mobile"
                @keydown.enter="save"
                @keydown.esc="$emit('close')"
                type="text"
                name="mobile"
                class="h-9 max-w-40"
            />
        </td>
        <td class="px-6 py-3">
            <div class="flex items-center gap-1.5">
                <IconButton @click="save" data-test="guest-save-button" class="hover:bg-muted hover:text-foreground">
                    <Check></Check>
                </IconButton>
                <IconButton @click="$emit('close')" class="hover:bg-destructive/10 hover:text-destructive">
                    <X></X>
                </IconButton>
            </div>
        </td>
    </tr>
</template>
