<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import localizedFormat from 'dayjs/plugin/localizedFormat';
import utc from 'dayjs/plugin/utc';
import timezone from 'dayjs/plugin/timezone';
dayjs.extend(relativeTime)
dayjs.extend(localizedFormat)
dayjs.extend(utc)
dayjs.extend(timezone)

const page = usePage()

const user = computed(() => page.props.auth.user)

const props = defineProps({
    session: {
        type: Object,
        required: true,
    },
});

</script>

<template>
    <Head title="Session" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Session</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">{{ session.name }}</div>
                </div>

                <div class="mt-6">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">{{ session.description }}</div>
                    </div>
                </div>

                <div class="mt-6">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex flex-col p-6">
                        <!-- Session in UTC -->
                        <span>UTC - {{ dayjs.utc(session.start_date_time).format('llll') }}</span>
                        <!-- Session in User's Timezone -->
                        <span>Auth User - {{ dayjs.utc(session.start_date_time).tz(user.timezone).format('llll') }} ({{ user.timezone }}) </span>
                        <!-- Session in Mentor's Timezone -->
                        <span>Mentor - {{ dayjs.utc(session.start_date_time).tz(session.mentor.user.timezone).format('llll') }} ({{ session.mentor.user.timezone }}) </span>
                        <!-- Session in Mentee's Timezone -->
                        <span>Mentee - {{ dayjs.utc(session.start_date_time).tz(session.mentee.user.timezone).format('llll') }} ({{ session.mentee.user.timezone }}) </span>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
