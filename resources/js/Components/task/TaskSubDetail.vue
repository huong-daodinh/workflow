<template>
  <div
    class="w-[19rem] w-full h-full bg-primary-light dark:border-[#1b2e4b] dark:bg-dark-light dark:shadow-none">
    <div class="p-3 flex flex-col gap-3">
      <div class="w-full bg-white">
        <div class="border-b border-[#e0e6ed] dark:border-[#1b2e4b] p-4">Assigned By</div>
        <div class="flex gap-x-5 p-3">
          <img
            src="/assets/images/profile-28.jpeg"
            v-if="page.props.auth.user.avatar"
            class="w-12 h-12 object-cover rounded-full" />
          <span
            v-else
            class="flex justify-center items-center w-12 h-12 text-center rounded-full object-cover bg-info text-lg dark:text-secondary">
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
      <div class="px-5 w-full bg-white">
        <p class="p-2">Created at</p>
        <p class="p-2">Edited at</p>
        <p class="p-2">Deadline</p>
      </div>
      <div class="w-full bg-white">
        <div class="border-b border-[#e0e6ed] dark:border-[#1b2e4b] p-4">Followers</div>
        <div class="flex gap-x-5 p-3">
          <img
            src="/assets/images/profile-28.jpeg"
            v-if="page.props.auth.user.avatar"
            class="w-12 h-12 object-cover rounded-full" />
          <span
            v-else
            class="flex justify-center items-center w-12 h-12 text-center rounded-full object-cover bg-info text-lg dark:text-secondary">
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
  </div>
</template>

<script lang="ts" setup>
import { usePage } from '@inertiajs/vue3';
const page = usePage();
</script>
