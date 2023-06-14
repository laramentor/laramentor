<script setup>
import Uploader from '@/Components/Uploader.vue';
import { useForm, usePage } from '@inertiajs/vue3';

const user = usePage().props.auth.user;

const form = useForm({
    image: user.image ?? '',
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Profile Picture</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Update your account's profile picture.
            </p>
        </header>

        <form @submit.prevent="form.patch(route(''))" class="mt-6 space-y-6">
            <div class="text-white">
                <Uploader />
            </div>
        </form>
    </section>
</template>
