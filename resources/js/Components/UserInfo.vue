<template>
  <div class="flex gap-1 items-center">
    <img
      v-if="userData.avatar"
      :src="userData.avatar"
      alt="avatar"
      class="inline-block h-6 w-6 rounded-full" />
    <span
      v-else
      class="flex justify-center items-center w-6 h-6 text-center rounded-full text-sm bg-primary text-white font-bold">
      {{
        userData.name.split(' ')[0][0] +
        userData.name.split(' ')[userData.name.split(' ').length - 1][0]
      }}
    </span>
    <span class="ml-2 text-base text-black font-bold">{{ userData.name }}</span>
  </div>
</template>

<script lang="ts" setup>
import { user } from '@/interfaces/index.interfaces';
import { defineProps, PropType } from 'vue';

defineProps({
  userData: {
    type: Object as PropType<user>,
    required: true
  }
});
</script>
