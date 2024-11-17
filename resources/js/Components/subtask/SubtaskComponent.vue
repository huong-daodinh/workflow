<template>
  <div
    class="p-3 mb-3 rounded border"
    :class="{
      'text-success bg-success-light border-success': sub_task.status === 'done',
      ' text-secondary bg-secondary-light border-secondary': sub_task.status !== 'done'
    }">
    <div class="ltr:pr-2 rtl:pl-2 cursor-pointer" @click="emits('updateSubTask', sub_task)">
      <strong class="ltr:mr-2 rtl:ml-2">{{ $t(sub_task.status) }}</strong>
      {{ sub_task.title }}
    </div>
    <user-info :userData="sub_task.assignee" />
    <div class="flex gap-2 font-bold italic mt-2 text-sm items-center text-dark">
      <icon-clock />
      {{ sub_task.due_date }}
    </div>
    <div class="flex flex-wrap mt-1 gap-1">
      <tag v-for="(tag, index) in sub_task.tags" :key="index" :slug="tag.slug" :color="tag.color" />
    </div>
  </div>
</template>

<script lang="ts" setup>
import type { subTask } from '@/interfaces/index.interfaces';
import { defineProps, defineEmits } from 'vue';
import UserInfo from '../UserInfo.vue';
import IconClock from '../icon/icon-clock.vue';
import Tag from '../TagComponent.vue';
defineProps({
  sub_task: {
    type: Object as () => subTask,
    required: true
  }
});

const emits = defineEmits(['updateSubTask']);
</script>
