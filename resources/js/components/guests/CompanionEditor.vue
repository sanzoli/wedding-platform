<script setup lang="ts">
import IconButton from '@/components/IconButton.vue';
import { Input } from '@/components/ui/input';
import { Select, SelectItem } from '@/components/ui/select';
import { Guest } from '@/types/guests';
import { useForm, usePage } from '@inertiajs/vue3';
import { Check, X } from 'lucide-vue-next';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { computed } from 'vue';
import { getInitials } from '@/composables/useInitials';

defineEmits(['close', 'save']);
const props = defineProps<{
    companion?: Guest;
    groupId?: number;
}>();

const languages = usePage().props.languages;
const initials = computed(() => getInitials(form.first_name, form.last_name));

const form = useForm({
    id: props.companion?.id,
    first_name: props.companion?.first_name ?? '',
    last_name: props.companion?.last_name ?? '',
    lang: props.companion?.lang ?? '',
    mobile: props.companion?.mobile ?? '',
    group_id : props.groupId
});
</script>

<template>
    <tr class="bg-muted/20">
        <td class="py-2 pr-4 pl-12">
            <div class="flex items-center gap-3 border-l border-border/40 pl-4">
                <Avatar>
                    <AvatarFallback class="admin-type-action bg-muted text-muted-foreground">
                        {{ initials }}
                    </AvatarFallback>
                </Avatar>
                <div class="flex gap-2">
                    <Input
                        v-model="form.first_name"
                        @keydown.enter="$emit('save', form)"
                        @keydown.esc="$emit('close')"
                        type="text"
                        name="first_name"
                        placeholder="name"
                        class="h-9 max-w-40"
                    />
                    <Input
                        v-model="form.last_name"
                        @keydown.enter="$emit('save', form)"
                        @keydown.esc="$emit('close')"
                        type="text"
                        name="last_name"
                        placeholder="surname"
                        class="h-9 max-w-40"
                    />
                </div>
            </div>
        </td>
        <td class="px-4 py-2"></td>
        <td class="px-4 py-2">
            <Select v-model="form.lang" name="lang">
                <SelectItem v-for="(data, value) in languages" :value :key="value"
                    >{{ data.flag }} {{ data.label }}</SelectItem
                >
            </Select>
        </td>
        <td class="px-4 py-2">
            <Input
                v-model="form.mobile"
                @keydown.enter="$emit('save', form)"
                @keydown.esc="$emit('close')"
                type="text"
                name="mobile"
                class="h-9 max-w-40"
            />
        </td>
        <td class="px-4 py-2">
            <div class="flex items-center justify-center gap-1">
                <IconButton
                    @click="$emit('save', form)"
                    :data-test="companion?.id ? 'companion-update-button-' + companion.id : 'companion-store-button'"
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
