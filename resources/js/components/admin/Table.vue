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
}>();

const trans = usePage().props.trans.table;
trans.no_items ??= 'No items yet';
trans.add_button ??= 'Add your first item';
trans.empty_search ??= 'No items match your search';
trans.empty_search_desc ??= 'Try adjusting your search terms';
</script>

<template>
    <section class="w-full">
        <section
            class="sticky top-16 z-30 -mx-6 bg-background/95 px-6 py-3 backdrop-blur-sm transition-shadow duration-200 group-has-data-[collapsible=icon]/sidebar-wrapper:top-12 sm:py-4"
        >
            <slot name="toolbar" />
        </section>

        <!-- ─── Desktop table (sm and up) ─── -->
        <!--
            contain-paint clips children to the Card's border-radius (so
            row hovers don't bleed past the corners) without creating a
            scroll container — overflow-hidden would do the same job but
            would also make any future sticky descendants resolve relative
            to the Card instead of the viewport.
        -->
        <Card class="mt-4 hidden w-full gap-0 py-0 contain-paint sm:block">
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
                                        <Plus :size="16" :stroke-width="2" />
                                        {{ trans.add_button }}
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
        </Card>

        <!-- ─── Mobile card list (below sm) ─── -->
        <div class="admin-fab-safe-area flex flex-col gap-2.5 sm:hidden">
            <slot name="body">
                <Card class="items-center gap-2 px-4 py-10 text-center">
                    <p class="text-sm font-medium text-foreground/80">
                        {{ search ? trans.empty_search : trans.no_items }}
                    </p>
                </Card>
            </slot>
        </div>
    </section>
</template>
