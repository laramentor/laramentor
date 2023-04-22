<script setup>
import { Link } from '@inertiajs/vue3'
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime'
import localizedFormat from 'dayjs/plugin/localizedFormat'
dayjs.extend(relativeTime)
dayjs.extend(localizedFormat)

const props = defineProps({
  mentors: {
    type: Array,
    required: true,
  },
})

</script>
<template>
  <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
    <li v-for="mentor_user in mentors" :key="mentor_user.id"
      class="col-span-1 flex flex-col divide-y divide-gray-200 rounded-lg bg-white text-center shadow">
      <div class="flex flex-1 flex-col p-8">
        <!-- <img class="mx-auto h-32 w-32 flex-shrink-0 rounded-full" :src="mentor_user.imageUrl" alt="" /> -->
        <h3 class="mt-6 text-sm font-medium text-gray-900">{{ mentor_user.name }}</h3>
        <dl class="mt-1 flex flex-grow flex-col justify-between">
          <dd class="text-sm text-gray-500">{{ mentor_user.title }}</dd>
          <dd class="mt-3 space-x-2">
            <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Vue</span>
            <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Laravel</span>
            <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Tailwind</span>
          </dd>
        </dl>
      </div>
      <div v-show="mentor_user.mentor.sessions">
        <!-- upcoming sessions -->

        <div class="-mt-px flex divide-x divide-gray-200">
          <div class="flex w-0 flex-1">
            <a href="#" class="relative -mr-px inline-flex w-0 flex-1 items-center justify-center gap-x-3 rounded-bl-lg border border-transparent py-4 text-sm font-semibold text-gray-900 hover:text-gray-800">
              <span class="ml-3">Upcoming Sessions</span>
            </a>
          </div>
        </div>

        <div class="flex flex-col" v-for="session in mentor_user.mentor.sessions">
          <Link :href="route('session.show', session.uuid)" class="relative inline-flex items-center px-4 py-4 text-sm font-medium text-gray-700 hover:text-gray-500">
            <span :title="dayjs(session.start_date_time).format('llll')">{{ dayjs(session.start_date_time).fromNow() }} - {{ session.mentee.user.name }}</span>
          </Link>
        </div>
      </div>
      <div>
        <div class="-mt-px flex divide-x divide-gray-200">
          <div class="flex w-0 flex-1">
            <form :action="route('session.store')" method="POST" class="flex flex-1">
              <input type="hidden" name="mentor_id" :value="mentor_user.mentor.id" />
              <button type="submit" class="relative -mr-px inline-flex w-0 flex-1 items-center justify-center gap-x-3 rounded-bl-lg border border-transparent py-4 text-sm font-semibold text-gray-900 hover:text-gray-800">
                <span class="ml-3">Request Mentorship</span>
              </button>
            </form>
          </div>
        </div>
      </div>
    </li>
  </ul>
</template>
