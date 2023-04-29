<script setup>
import { ref, computed } from 'vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid'
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOptions, ComboboxOption } from '@headlessui/vue'

defineProps({
    modelValue: {
        type: String,
        required: true,
    },
});

const emit = defineEmits(['update:modelValue']);

const timezones = Intl.supportedValuesOf('timeZone')

const query = ref('')

const filteredTimezones = computed(() =>
    query.value === ''
        ? timezones
        : timezones.filter((timezone) => {
            return timezone.toLowerCase().includes(query.value.toLowerCase())
        })
)

</script>

<template>
    <Combobox as="div" :modelValue="modelValue" @update:modelValue="value => emit('update:modelValue', value)">
        <div class="relative mt-2">
            <ComboboxInput
                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full"
                @change="query = $event.target.value" :display-value="(timezone) => timezone" />
            <ComboboxButton class="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none">
                <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
            </ComboboxButton>

            <ComboboxOptions v-show="filteredTimezones.length > 0"
                class="absolute z-20 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white dark:bg-gray-800 py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                <ComboboxOption v-for="timezone in filteredTimezones" :key="timezone" :value="timezone" as="template"
                    v-slot="{ active, selected }">
                    <li
                        :class="['relative cursor-default select-none py-2 pl-3 pr-9', active ? 'bg-indigo-600 text-white' : 'text-gray-900 dark:text-gray-300']">
                        <span :class="['block truncate', selected && 'font-semibold']">
                            {{ timezone }}
                        </span>

                        <span v-if="selected"
                            :class="['absolute inset-y-0 right-0 flex items-center pr-4', active ? 'text-white' : 'text-indigo-600']">
                            <CheckIcon class="h-5 w-5" aria-hidden="true" />
                        </span>
                    </li>
                </ComboboxOption>
            </ComboboxOptions>
        </div>
    </Combobox>
</template>
