<template>
  <span
    class="rounded-full px-2"
    :class="{
      'bg-dark-light text-dark': color === '',
      [`text-white bg-${color}`]: color !== ''
    }">
    {{ $t(`${slug}`) }}
  </span>
</template>

<script lang="ts" setup>
import { defineProps } from 'vue';
defineProps({
  color: {
    type: String,
    default: ''
  },
  slug: {
    type: String,
    required: true
  }
});
</script>
