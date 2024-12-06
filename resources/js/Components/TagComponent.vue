<template>
  <span
    class="rounded-full px-2 text-sm font-bold py-1"
    :class="{
      'bg-dark-light text-dark': color === '',
      [`text-white bg-warning`]: color === 'warning',
      [`text-white bg-danger`]: color === 'danger',
      [`text-white bg-info`]: color === 'info',
      [`text-white bg-success`]: color === 'success',
      [`text-white bg-primary`]: color === 'primary',
      [`text-white bg-secondary`]: color === 'secondary',
      [`text-white bg-light`]: color === 'light',
      [`text-white bg-dark`]: color === 'dark',
      [`text-white bg-white`]: color === 'white'
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
