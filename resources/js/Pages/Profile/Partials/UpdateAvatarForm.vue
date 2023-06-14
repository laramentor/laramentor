<script setup>
import Uploader from '@/Components/Uploader.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { watch } from 'vue';

const user = usePage().props.auth.user;

const form = useForm({
    avatar_url: user.avatar ?? '',
});

const files = ref([]);

const submit = () => {
    form.patch(route('profile.update.avatar'));
};

// when files changes, update the form
watch(files, (files) => {
    form.avatar_url = files[0].cdnUrl;
});

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Avatar</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Update your account's avatar.
            </p>

            <div v-if="form.avatar_url" class="mt-4 rounded-xl w-32 p-1 border-2 border-indigo-100 dark:border-indigo-900">
                <img :src="form.avatar_url" alt="Avatar" class="rounded-lg ">
            </div>
        </header>

        <form @submit.prevent="submit" class="mt-6 space-y-6">
            <Uploader v-model="files"/>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.avatar_url == ''">Save</PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
                </Transition>
            </div>

        </form>
    </section>
</template>
