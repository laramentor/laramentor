<script setup>
import MentorCard from '@/Components/MentorCard.vue'

const props = defineProps({
  mentors: {
    type: Array,
    required: true,
  },
})

</script>
<template>
  <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
    <li v-for="mentor in mentors" :key="mentor.id">
      <MentorCard :mentor="mentor" />
    </li>
  </ul>
</template>
