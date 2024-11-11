<template>
  <div
    class="w-[19rem] w-full bg-white shadow-[4px_6px_10px_-3px_#bfc9d4] rounded-md border border-[#e0e6ed] dark:border-[#1b2e4b] dark:bg-[#191e3a] dark:shadow-none">
    <div class="py-7 px-6">
      <div class="flex gap-x-5">
        <img
          src="/assets/images/profile-28.jpeg"
          v-if="page.props.auth.user.avatar"
          class="w-12 h-12 object-cover rounded-full" />
        <span
          v-else
          class="flex justify-center items-center w-12 h-12 text-center rounded-full object-cover bg-info text-lg">
          {{ page.props.auth.user.name.charAt(0) + page.props.auth.user.name.charAt(1) }}
        </span>
        <div>
          <h5 class="text-[#3b3f5c] text-xl font-semibold dark:text-white-light">
            {{ page.props.auth.user.name }}
          </h5>
          <p class="text-white-dark">
            {{ page.props.auth.user.role }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { usePage } from '@inertiajs/vue3';
const page = usePage();
</script>
