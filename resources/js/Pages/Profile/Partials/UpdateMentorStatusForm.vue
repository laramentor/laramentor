<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';

const emit = defineEmits(['update-mentor-status']);

const form = useForm({
    is_mentor: true,
});

const submit = () => {
    form.patch(route('profile.update.mentor.status'), {
        onSuccess: () => {
            emit('update-mentor-status', true);
        },
    });
};

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-indigo-900 dark:text-indigo-100">Become a Mentor</h2>

            <p class="mt-1 text-sm text-indigo-600 dark:text-indigo-400">
                If you would like become a mentor, please apply below.
            </p>
        </header>

        <form @submit.prevent="submit" class="mt-6 space-y-6">
            <PrimaryButton :disabled="form.processing">Become a Mentor</PrimaryButton>
        </form>
    </section>
</template>
