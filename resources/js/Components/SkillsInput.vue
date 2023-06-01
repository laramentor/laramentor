<script setup>
import { ref, onMounted } from 'vue';
import { CheckIcon, PlusIcon } from '@heroicons/vue/20/solid'
import { Combobox, ComboboxButton, ComboboxOptions, ComboboxOption } from '@headlessui/vue'

const props = defineProps({
    modelValue: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits(['update:modelValue']);

const skills = ref([])

onMounted(() => {
    axios.get(route('skills.index')).then(response => {
        skills.value = response.data.skills ?? [];
    })
})

</script>

<template>
    <Combobox as="div"
        :modelValue="modelValue"
        @update:modelValue="value => emit('update:modelValue', value)"
        multiple
        by="id"
        >
        <div class="relative mt-2">
            <div class="flex items-center flex-wrap justify-start space-x-2 mt-3">
                <div v-for="skill in modelValue"
                    :key="skill.id"
                    class="bg-indigo-200 text-indigo-600 dark:bg-indigo-100 rounded-full py-1 px-3 text-sm font-bold md:inline-block hover:bg-indigo-200"
                    >
                    {{ skill.name }}
                </div>

                <ComboboxButton class="bg-indigo-600 text-indigo-100 rounded-full py-1 px-1 text-sm font-bold md:inline-block hover:bg-indigo-500">
                    <PlusIcon class="h-4 w-4" aria-hidden="true" />
                </ComboboxButton>
            </div>

            <ComboboxOptions v-show="skills.length > 0"
                class="absolute z-20 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white dark:bg-gray-800 py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                <ComboboxOption v-for="skill in skills" :key="skill.id" :value="skill" as="template"
                    v-slot="{ active, selected }">
                    <li
                        :class="['relative cursor-default select-none py-2 pl-3 pr-9', active ? 'bg-indigo-600 text-white' : 'text-gray-900 dark:text-gray-300']">
                        <span :class="['block truncate', selected && 'font-semibold']">
                            {{ skill.name }}
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
