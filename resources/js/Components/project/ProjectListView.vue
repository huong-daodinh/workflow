<template>
  <div class="grid gap-1 grid-cols-12 bg-white shadow-[4px_6px_10px_-3px_#bfc9d4]">
    <div
      class="h-[calc(80vh-80px)] overflow-y-auto col-span-12"
      :class="{ 'md:col-span-3': selectedTask, 'md:col-span-9': !selectedTask }">
      <div
        class="space-y-2 font-semibold w-full"
        v-for="(task_list, index) in task_lists"
        :key="index">
        <div class="mb-2 pr-2">
          <div class="flex items-center justify-between">
            <button
              type="button"
              class="p-3 text-xl uppercase w-1/3 flex items-center text-white-dark dark:bg-[#1b2e4b]"
              :class="{ '!text-primary': inAccordion(task_list.id) }"
              @click="
                inAccordion(task_list.id)
                  ? removeAccordion(task_list.id)
                  : addAccordion(task_list.id)
              ">
              <div class="mr-3" :class="{ 'rotate-180': inAccordion(task_list.id) }">
                <icon-caret-down class="font-bold" />
              </div>
              {{ task_list.name }}
            </button>
            <button class="btn-no-border-primary h-1/2 text-base">
              <icon-plus class="mr-2" />
              <span :class="{ hidden: selectedTask, 'md:inline-block hidden': !selectedTask }">
                Create new task
              </span>
            </button>
          </div>
          <vue-collapsible :isOpen="inAccordion(task_list.id)">
            <div v-if="task_list.tasks.length > 0">
              <div class="flex pl-3" v-for="(task, index) in task_list.tasks" :key="index">
                <div class="ltr:mr-4 rtl:ml-4">
                  <input
                    type="checkbox"
                    class="form-checkbox mt-1.5 text-success"
                    :checked="task.status === 'done'" />
                </div>
                <div class="w-full border-b-2 pb-4 mb-5 relative">
                  <div class="flex justify-between items-start">
                    <div :class="{ 'overflow-hidden truncate w-60': selectedTask }">
                      <div
                        class="cursor-pointer font-semibold text-xl text-primary"
                        :class="{
                          'text-success': task.status === 'done'
                        }"
                        @click="onSelectTask(task.id)">
                        {{ task.title }}
                      </div>
                      <div class="flex gap-2 relative">
                        <tag
                          v-for="(tag, index) in task.tags"
                          :key="index"
                          :color="tag.color"
                          :slug="tag.slug" />
                      </div>
                    </div>
                    <div class="flex items-center gap-5">
                      <p :class="{ hidden: selectedTask }">{{ task.due_date }}</p>
                      <div class="flex items-center gap-3 h-fit" v-if="task.assignee !== null">
                        <img
                          v-if="task.assignee.avatar"
                          :src="task.assignee.avatar"
                          alt=""
                          class="h-6 w-6 rounded-full" />
                        <span
                          v-else
                          class="flex justify-center items-center w-6 h-6 text-center rounded-full object-cover bg-primary text-sm text-white font-bold">
                          {{
                            task.assignee.name.split(' ')[0][0] +
                            task.assignee.name.split(' ')[
                              task.assignee.name.split(' ').length - 1
                            ][0]
                          }}
                        </span>
                        <span class="w-24 overflow-clip" :class="{ hidden: selectedTask }">
                          {{
                            task.assignee.name.split(' ')[task.assignee.name.split(' ').length - 1]
                          }}
                        </span>
                      </div>
                    </div>
                  </div>

                  <!-- subtask -->
                  <div v-if="task.sub_tasks.length > 0">
                    <div class="flex mt-5" v-for="(sub_task, index) in task.sub_tasks" :key="index">
                      <div class="mr-4">
                        <input
                          type="checkbox"
                          :checked="sub_task.status === 'done'"
                          class="form-checkbox mt-1.5 text-success" />
                      </div>
                      <div class="w-full relative">
                        <div class="flex justify-between h-fit relative">
                          <div
                            :class="{
                              'text-success': sub_task.status === 'done',
                              'overflow-hidden truncate w-60': selectedTask
                            }">
                            <h4
                              class="font-semibold text-lg text-primary cursor-pointer"
                              @click="onSelectTask(task.id)">
                              {{ sub_task.title }}
                            </h4>
                            <div class="flex gap-2 relative">
                              <tag
                                v-for="(tag, index) in sub_task.tags"
                                :key="index"
                                :color="tag.color"
                                :slug="tag.slug" />
                            </div>
                          </div>
                          <div class="flex items-center gap-5 h-fit">
                            <p :class="{ hidden: selectedTask }">{{ sub_task.due_date }}</p>
                            <div
                              class="flex items-center gap-3 h-fit"
                              v-if="sub_task.assignee !== null">
                              <img
                                v-if="sub_task.assignee.avatar"
                                :src="sub_task.assignee.avatar"
                                alt=""
                                class="h-6 w-6 rounded-full" />
                              <span
                                v-else
                                class="flex justify-center items-center w-6 h-6 text-center rounded-full object-cover bg-primary text-sm text-white font-bold">
                                {{
                                  sub_task.assignee.name.split(' ')[0][0] +
                                  sub_task.assignee.name.split(' ')[
                                    sub_task.assignee.name.split(' ').length - 1
                                  ][0]
                                }}
                              </span>
                              <span class="w-16 overflow-clip" :class="{ hidden: selectedTask }">
                                {{
                                  sub_task.assignee.name.split(' ')[
                                    sub_task.assignee.name.split(' ').length - 1
                                  ]
                                }}
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </vue-collapsible>
        </div>
      </div>
    </div>
    <div class="md:block hidden col-span-3" :class="{ 'md:hidden': selectedTask }">
      <sub-detail />
    </div>
    <div v-if="selectedTask" class="md:col-span-9 col-span-12">
      <task-detail :key="selectedTask" :taskId="selectedTask" @close="selectedTask = 0" />
    </div>
  </div>
</template>

<script lang="ts" setup>
import type { task_list } from '@/interfaces/index.interfaces';
import { ref, defineProps } from 'vue';
import VueCollapsible from 'vue-height-collapsible/vue3';
import IconCaretDown from '@/Components/icon/icon-caret-down.vue';
import SubDetail from '@/Pages/Profile/SubDetail.vue';
import TaskDetail from '../task/TaskDetail.vue';
import IconPlus from '../icon/icon-plus.vue';
import Tag from '../TagComponent.vue';
const selectedTask = ref(0);
const accordions1 = ref<Array<number>>([]);

defineProps({
  task_lists: {
    type: Array as () => task_list[],
    required: true
  }
});

const removeAccordion = (number: number) => {
  const index = accordions1.value.indexOf(number);
  if (index > -1) accordions1.value.splice(index, 1);
};

const addAccordion = (number: number) => {
  if (accordions1.value.indexOf(number) === -1) {
    accordions1.value.push(number);
  }
};

const inAccordion = (number: number) => {
  return accordions1.value.indexOf(number) > -1;
};

const onSelectTask = (taskId: number) => {
  selectedTask.value = selectedTask.value == taskId ? 0 : taskId;
};
</script>
