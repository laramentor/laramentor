<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import UpdateMentorInformationForm from './Partials/UpdateMentorInformationForm.vue';
import UpdateTimezoneForm from './Partials/UpdateTimezoneForm.vue';
import UpdateMentorStatusForm from './Partials/UpdateMentorStatusForm.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage()

const user = computed(() => page.props.auth.user)

const updateMentorStatus = (mentor_status) => {
    user.value.is_mentor = mentor_status
}

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Profile</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div v-if="! user.is_mentor" class="px-8 py-6 bg-indigo-50 dark:bg-indigo-950 text-gray-800 dark:text-gray-200 shadow sm:rounded-lg">
                    <UpdateMentorStatusForm :user="user" @update-mentor-status="updateMentorStatus" />
                </div>

                <div v-if="user.is_mentor" class="p-4 sm:p-8 bg-indigo-50 dark:bg-indigo-950 shadow sm:rounded-lg">
                    <UpdateMentorInformationForm
                        class="max-w-xl"
                    />
                </div>

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <UpdateTimezoneForm class="max-w-xl" />
                </div>

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
