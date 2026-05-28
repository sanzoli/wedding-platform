<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { usePage } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';

defineEmits<{
    addItem: [];
}>();

defineProps<{
    search?: string;
    columns?: number;
    emptyAddLabel?: string;
}>();

const trans = usePage().props.trans.table;
trans.no_items ??= 'No items yet';
trans.add_button ??= 'Add your first item';
trans.empty_search ??= 'No items match your search';
trans.empty_search_desc ??= 'Try adjusting your search terms';
</script>

<template>
    <section class="w-full">
        <section class="py-3 sm:py-4">
            <slot name="toolbar" />
        </section>

        <Card class="mt-4 w-full gap-0 py-0 contain-paint">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-border/50">
                            <slot name="header" />
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border/50">
                        <slot name="body">
                            <tr>
                                <td
                                    :colspan="columns ?? 4"
                                    class="px-6 py-12 text-center"
                                >
                                    <div
                                        v-if="search"
                                        class="flex flex-col items-center gap-3"
                                    >
                                        <p
                                            class="text-sm font-medium text-foreground/80"
                                        >
                                            {{ trans.empty_search }}
                                        </p>
                                        <p class="text-xs text-muted-foreground">
                                            {{ trans.empty_search_desc }}
                                        </p>
                                    </div>

                                    <div
                                        v-else
                                        class="flex flex-col items-center gap-4"
                                    >
                                        <Button @click="$emit('addItem')">
                                            <Plus
                                                :size="16"
                                                :stroke-width="2"
                                            />
                                            {{
                                                emptyAddLabel ??
                                                trans.add_button
                                            }}
                                        </Button>
                                        <div class="space-y-2">
                                            <p
                                                class="text-sm font-medium text-foreground/80"
                                            >
                                                {{ trans.no_items }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </slot>
                    </tbody>
                </table>
            </div>
        </Card>
    </section>
</template>
