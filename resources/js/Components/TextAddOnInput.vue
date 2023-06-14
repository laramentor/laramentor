<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: {
        type: String,
        required: true,
    },
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <div class="flex group focus-within:ring-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus-within:border-indigo-500 dark:focus-within:border-indigo-600 focus-within:ring-indigo-500 dark:focus-within:ring-indigo-600 rounded-md shadow-sm">
        <span class="flex select-none items-center text-gray-500">@</span>
        <input
            class="flex-1 border-0 bg-transparent pl-1 outline-none"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            ref="input"
        />
    </div>
</template>
