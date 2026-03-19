import { onMounted, ref, type Ref, type ComponentPublicInstance } from 'vue';

/**
 * Composable for automatically focusing an input element after component mount
 * Useful for forms where you want the first input to be focused immediately
 */
export function useInputFocus<T extends ComponentPublicInstance>(
    delay: number = 350
) {
    const inputRef = ref<T | null>(null);

    onMounted(() => {
        setTimeout(() => {
            const inputElement = inputRef.value?.$el as HTMLInputElement;
            if (inputElement) {
                inputElement.focus();
            }
        }, delay);
    });

    return {
        inputRef,
    };
}
