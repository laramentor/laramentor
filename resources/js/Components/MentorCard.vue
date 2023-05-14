<script setup>
import { computed } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
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
  mentor: {
    type: Object,
    required: true,
  },
})

const form = useForm({
  mentor_id: props.mentor.id,
  start_date_time: dayjs().add(1, 'hour').format('YYYY-MM-DD HH:mm'),
})

</script>
<template>
  <div class="col-span-1 flex flex-col divide-y divide-gray-200 rounded-lg bg-white text-center shadow">
    <div class="flex flex-1 flex-col p-8">
      <!-- <img class="mx-auto h-32 w-32 flex-shrink-0 rounded-full" :src="mentor.imageUrl" alt="" /> -->
      <h3 class="mt-6 text-sm font-medium text-gray-900">{{ mentor.user.name }}</h3>
      <h3 class="mt-1 text-xs font-medium text-gray-900">{{ mentor.user.timezone }}</h3>
      <dl class="mt-1 flex flex-grow flex-col justify-between">
        <!-- <dd class="text-sm text-gray-500">{{ mentor.user.title }}</dd> -->
        <dd class="mt-3 space-x-2">
          <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">Laravel</span>
          <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Vue</span>
          <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20">Tailwind</span>
        </dd>
      </dl>
    </div>
    <div v-show="mentor.sessions">
      <!-- upcoming sessions -->
      <div class="-mt-px flex divide-x divide-gray-200">
        <div class="flex w-0 flex-1">
          <a href="#" class="relative -mr-px inline-flex w-0 flex-1 items-center justify-center gap-x-3 rounded-bl-lg border border-transparent py-4 text-sm font-semibold text-gray-900 hover:text-gray-800">
            <span class="ml-3">Upcoming Sessions</span>
          </a>
        </div>
      </div>

      <div class="flex flex-col" v-for="session in mentor.sessions">
        <Link :href="route('session.show', session.uuid)" class="relative flex px-4 py-4 text-sm font-medium text-gray-700 hover:text-gray-500 space-2">
          <span>{{ session.mentee.user.name }}</span>
          <span>{{ dayjs(session.start_date_time).tz(user.timezone).format('lll') }}</span>
         </Link>
      </div>
    </div>
    <div v-show="user.is_mentee && user.id !=  mentor.user.id">
      <form @submit.prevent="form.post(route('session.store'))" class="flex w-full justify-between">
        <input type="datetime-local" v-model="form.start_date_time" class="flex-1 form-input bg-i border-transparent active:border-transparent text-sm font-semibold hover:bg-indigo-100 pr-0">
        <button type="submit" class="bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-600 hover:bg-indigo-100">Request</button>
      </form>
    </div>
  </div>
</template>
