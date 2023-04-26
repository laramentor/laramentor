<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TimezoneInput from '@/Components/TimezoneInput.vue';
import { useForm, usePage } from '@inertiajs/vue3';

const user = usePage().props.auth.user;

const form = useForm({
    timezone: user.timezone,
});

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Timezone</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Update your account's timezone.
            </p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update.timezone'))" class="mt-6 space-y-6">
            <div>
                <InputLabel for="timezone" value="Timezone" />

                <TimezoneInput id="timezone" class="mt-1 block w-full" v-model="form.timezone" required
                    autocomplete="timezone" />

                <InputError class="mt-2" :message="form.errors.timezone" />
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
