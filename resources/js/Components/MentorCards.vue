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
  <ul role="list" class="flex flex-col">
    <li v-for="mentor in mentors" :key="mentor.id">
      <MentorCard :mentor="mentor" />
    </li>
  </ul>
</template>
