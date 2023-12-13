<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Bitbucket from "@/Components/Icons/Bitbucket.vue";
import Github from "@/Components/Icons/Github.vue";
import Google from "@/Components/Icons/Google.vue";
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

const open = (provider) => {
    window.location.href = route('social.redirect', { provider: provider });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Remember me</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                >
                    Forgot your password?
                </Link>

                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in
                </PrimaryButton>
            </div>

            <div class="flex items-center justify-end mt-4 mb-4">
                <div class="mt-3 block">
                    <p class="mb-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Login with:
                    </p>
                    <div class="flex flex-wrap">
                        <SecondaryButton @click.prevent="open('github')" class="inline w-32 mr-1">
                            <Github />
                            <span class="align-middle ms-2">Github</span>
                        </SecondaryButton>
                        <SecondaryButton @click.prevent="open('google')" class="inline w-32 mr-1">
                            <Google />
                            <span class="align-middle ms-2">Google</span>
                        </SecondaryButton>
                        <SecondaryButton @click.prevent="open('bitbucket')" class="inline w-32">
                            <Bitbucket />
                            <span class="align-middle ms-2">Bitbucket</span>
                        </SecondaryButton>
                    </div>
                </div>
            </div>
        </form>
    </GuestLayout>
</template>
