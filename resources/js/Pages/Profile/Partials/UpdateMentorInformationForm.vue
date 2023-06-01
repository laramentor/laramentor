<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import NumberInput from '@/Components/NumberInput.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import axios from 'axios';
import SkillsInput from '@/Components/SkillsInput.vue';

onMounted(() => {
    axios.get(route('profile.show.mentor.information')).then(response => {
        form.job_title = response.data.mentor.job_title ?? '';
        form.company = response.data.mentor.company ?? '';
        form.hourly_rate = response.data.mentor.hourly_rate ?? 0;
        form.skills = response.data.mentor.skills ?? [];
    })
})

const form = useForm({
    job_title: '',
    company: '',
    hourly_rate: 0,
    skills: [],
});

const submit = () => {
    form.patch(route('profile.update.mentor.information'));
};

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Mentor Information</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Update your mentorship information.
            </p>
        </header>

        <form @submit.prevent="submit" class="mt-6 space-y-6">
            <div>
                <InputLabel for="job_title" value="Job Title" />

                <TextInput
                    id="job_title"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.job_title"
                    required
                    autofocus
                    autocomplete="job_title"
                />

                <InputError class="mt-2" :message="form.errors.job_title" />
            </div>

            <div>
                <InputLabel for="company" value="Company" />

                <TextInput
                    id="company"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.company"
                    required
                />

                <InputError class="mt-2" :message="form.errors.company" />
            </div>

            <div>
                <InputLabel for="hourly_rate" value="Hourly Rate (in USD)" />

                <NumberInput
                    id="hourly_rate"
                    type="number"
                    class="mt-1 block w-full"
                    v-model="form.hourly_rate"
                    required
                />

                <InputError class="mt-2" :message="form.errors.hourly_rate" />
            </div>

            <div>
                <InputLabel for="skills" value="Skills" />

                <SkillsInput
                    id="skills"
                    class="mt-1 block"
                    v-model="form.skills"
                />

                <InputError class="mt-2" :message="form.errors.skills" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
