<template>
  <div
    class="w-[19rem] w-full h-full bg-primary-light dark:border-[#1b2e4b] dark:bg-dark-light dark:shadow-none">
    <div class="p-3 flex flex-col gap-3">
      <div class="w-full bg-white">
        <div class="border-b border-[#e0e6ed] dark:border-[#1b2e4b] p-4">{{ $t('assigner') }}</div>
        <div class="flex gap-x-5 p-3">
          <img
            :src="task.assigner.avatar"
            v-if="task.assigner.avatar"
            class="w-12 h-12 object-cover rounded-full" />
          <span
            v-else
            class="flex justify-center items-center w-12 h-12 text-center rounded-full object-cover bg-info text-lg dark:text-secondary">
            {{ takeSummaryName(task.assigner.name) }}
          </span>
          <div>
            <h5 class="text-[#3b3f5c] lg:text-xl text-sm font-semibold dark:text-white-light">
              {{ task.assigner.name }}
            </h5>
            <p class="text-white-dark lg:text-base text-xs">
              {{ task.assigner.role }}
            </p>
          </div>
        </div>
      </div>
      <div class="px-5 w-full bg-white">
        <p class="p-2"><span class="font-bold">Created at:</span> {{ task.created_at }}</p>
        <p class="p-2"><span class="font-bold">Updated at:</span> {{ task.updated_at }}</p>
        <p class="p-2"><span class="font-bold">Deadline: </span> {{ task.due_date }}</p>
      </div>
      <div class="w-full bg-white">
        <div class="border-b border-[#e0e6ed] dark:border-[#1b2e4b] p-4">Followers</div>
        <div class="flex gap-x-5 p-3" v-for="(user, index) in task?.followers" :key="index">
          <img :src="user.avatar" v-if="user.avatar" class="w-12 h-12 object-cover rounded-full" />
          <span
            v-else
            class="flex justify-center items-center w-12 h-12 text-center rounded-full object-cover bg-info text-lg dark:text-secondary">
            {{ takeSummaryName(user.name) }}
          </span>
          <div>
            <h5 class="text-[#3b3f5c] lg:text-xl text-sm font-semibold dark:text-white-light">
              {{ user.name }}
            </h5>
            <p class="text-white-dark lg:text-base text-xs">
              {{ user.role }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { usePage } from '@inertiajs/vue3';
import type { task } from '@/interfaces/index.interfaces';
import { PropType, defineProps } from 'vue';
const page = usePage();
defineProps({
  task: Object as PropType<task>
});

const takeSummaryName = (name: string) => {
  return name.charAt(0) + name.charAt(1);
};
</script>
