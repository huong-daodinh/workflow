<template>
  <div>
    <div class="flex gap-5 relative sm:h-[calc(100vh_-_150px)] h-full">
      <div
        class="bg-black/60 z-10 w-full h-full rounded-md absolute hidden"
        :class="{ '!block xl:!hidden': isShowMenu }"
        @click="isShowMenu = !isShowMenu"></div>
      <div
        class="panel lg:w-[480px] w-[400px] flex-none absolute xl:relative z-10 space-y-4 h-full xl:h-auto hidden xl:block ltr:lg:rounded-r-md ltr:rounded-r-none rtl:lg:rounded-l-md rtl:rounded-l-none overflow-hidden"
        :class="{
          'hidden shadow': !isShowMenu,
          '!block h-full ltr:left-0 rtl:right-0': isShowMenu
        }">
        <div class="flex flex-col h-full">
          <div class="flex items-center justify-between">
            <div class="flex text-center items-center">
              <div class="shrink-0">
                <icon-notes />
              </div>
              <h3 class="text-lg font-semibold ltr:ml-3 rtl:mr-3">Project task lists</h3>
            </div>
            <button type="button" class="btn btn-primary flex" @click="addEditTaskList()">
              <icon-plus class="w-5 h-5 ltr:mr-3 rtl:ml-3" />
              Tạo danh sách công việc
            </button>
          </div>
          <div class="h-px w-full border-b border-[#e0e6ed] dark:border-[#1b2e4b] my-4"></div>
          <div class="overflow-y-auto relative h-full grow">
            <div class="space-y-1">
              <div
                class="space-y-2 font-semibold w-full"
                v-for="(task_list, index) in task_lists"
                :key="index">
                <div class="mb-2 pr-2">
                  <div class="flex items-center bg-dark-light justify-between px-2">
                    <button
                      type="button"
                      class="py-3 text-lg uppercase w-2/3 flex items-center text-white-dark dark:bg-[#1b2e4b]"
                      :class="{ '!text-primary': isInAccordion(task_list.id) }"
                      @click="
                        isInAccordion(task_list.id)
                          ? removeAccordion(task_list.id)
                          : addAccordion(task_list.id)
                      ">
                      <div class="mr-3" :class="{ 'rotate-180': isInAccordion(task_list.id) }">
                        <icon-caret-down class="font-bold" />
                      </div>
                      {{ task_list.name }}
                    </button>
                    <div class="dropdown shrink-0">
                      <Popper :placement="'bottom-start'" offsetDistance="8">
                        <button type="button" class="relative group block rotate-90">
                          <icon-horizontal-dots />
                        </button>
                        <template #content="{ close }">
                          <ul
                            @click="close"
                            class="text-dark dark:text-white-dark !py-0 w-[230px] font-semibold dark:text-white-light/90">
                            <li>
                              <button @click="addTask(task_list.id)">
                                <icon-plus /> {{ $t('create_new_task') }}
                              </button>
                            </li>
                            <li>
                              <button @click="addTask(task_list.id)">
                                <icon-plus /> {{ $t('create_new_task') }}
                              </button>
                            </li>
                            <li>
                              <button @click="onTienDo()">Tiến độ</button>
                            </li>
                            <li
                              class="border-t border-white-light text-red-500 dark:border-white-light/10">
                              <button @click="delTaskList(task_list)">
                                {{ $t('del_task_list') }}
                              </button>
                            </li>
                          </ul>
                        </template>
                      </Popper>
                    </div>
                  </div>
                  <vue-collapsible :isOpen="isInAccordion(task_list.id)">
                    <div v-if="task_list.tasks.length > 0">
                      <div class="flex pl-3" v-for="(task, index) in task_list.tasks" :key="index">
                        <div class="ltr:mr-4 rtl:ml-4">
                          <input
                            onclick="return false;"
                            type="checkbox"
                            class="form-checkbox mt-1.5 text-success"
                            :id="`task_${task.id}_checkbox`"
                            :checked="task.status === 'done' || task.status === 'closed'" />
                        </div>
                        <div class="w-full border-b-2 pb-4 mb-5 relative">
                          <div class="flex justify-between items-start">
                            <div class="overflow-hidden">
                              <div
                                :id="`task_${task.id}`"
                                class="cursor-pointer font-semibold text-lg text-primary"
                                :class="{
                                  'text-success line-through':
                                    task.status === 'done' || task.status === 'closed'
                                }"
                                @click="onSelectTask(task.id)">
                                {{ task.title }}
                              </div>
                              <div
                                class="flex items-center gap-3 h-fit mb-2"
                                v-if="task.assignee !== null">
                                <icon-user />
                                <img
                                  v-if="task.assignee.avatar"
                                  :src="task.assignee.avatar"
                                  alt=""
                                  class="h-6 w-6 rounded-full" />
                                <span
                                  v-else
                                  class="flex justify-center items-center w-6 h-6 text-center rounded-full object-cover bg-primary text-white font-bold">
                                  {{
                                    task.assignee.name.split(' ')[0][0] +
                                    task.assignee.name.split(' ')[
                                      task.assignee.name.split(' ').length - 1
                                    ][0]
                                  }}
                                </span>
                                <span class="w-24 overflow-clip">
                                  {{
                                    task.assignee.name.split(' ')[
                                      task.assignee.name.split(' ').length - 1
                                    ]
                                  }}
                                </span>
                              </div>
                              <div class="flex gap-x-2 mb-2">
                                <icon-clock /> {{ task.due_date }}
                              </div>
                              <div class="flex gap-2 relative flex-wrap">
                                <tag
                                  v-for="(tag, index) in task.tags"
                                  :key="index"
                                  :color="tag.color"
                                  :slug="tag.slug" />
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
          </div>
        </div>
      </div>

      <div class="panel flex-1 overflow-auto h-full detail-box relative">
        <div>
          <button
            type="button"
            class="xl:hidden hover:text-primary"
            @click="isShowMenu = !isShowMenu">
            <icon-menu />
          </button>
        </div>
        <div
          v-if="selectedTask === 0"
          class="flex justify-center items-center sm:min-h-[300px] min-h-[400px] font-semibold text-lg h-full">
          Chọn nhiệm vụ để xem chi tiết
        </div>
        <div v-if="selectedTask">
          <div class="sm:min-h-[300px] min-h-[400px]">
            <task-detail
              :key="selectedTask"
              :taskId="selectedTask"
              :assigners="assigners"
              :assignees="assignees"
              @close="onClose"
              @scroll-to-bottom="scrollToBottom" />
          </div>
        </div>
      </div>
    </div>
    <!-- task list modal -->
    <TransitionRoot appear :show="isAddTaskListModal" as="template">
      <Dialog as="div" @close="isAddTaskListModal = false" class="relative z-[51]">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0">
          <DialogOverlay class="fixed inset-0 bg-[black]/60" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto" @click.prevent="closeTaskListModal">
          <div class="flex min-h-full items-center justify-center px-4 py-8">
            <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95">
              <DialogPanel
                class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg text-black dark:text-white-dark">
                <button
                  type="button"
                  class="absolute top-4 ltr:right-4 rtl:left-4 text-gray-400 hover:text-gray-800 dark:hover:text-gray-600 outline-none"
                  @click="closeTaskListModal">
                  <icon-x />
                </button>
                <div
                  class="text-lg font-medium bg-[#fbfbfb] dark:bg-[#121c2c] ltr:pl-5 rtl:pr-5 py-3 ltr:pr-[50px] rtl:pl-[50px]">
                  {{ taskListForm.id ? 'Edit Task List' : 'Add Task List' }}
                </div>
                <div class="p-5">
                  <form @submit.prevent="saveTaskList">
                    <div class="grid gap-5">
                      <div>
                        <label for="title">Tên</label>
                        <input
                          id="title"
                          v-model="taskListForm.name"
                          type="text"
                          class="form-input mt-1"
                          placeholder="Enter Name" />
                      </div>
                      <input-error :message="taskListForm.errors.name" />
                    </div>

                    <div class="flex justify-end items-center mt-8">
                      <button
                        type="button"
                        class="btn btn-outline-danger"
                        @click="closeTaskListModal">
                        Huỷ
                      </button>
                      <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">
                        {{ taskListForm.id ? 'Cập nhật' : 'Tạo mới' }}
                      </button>
                    </div>
                  </form>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>

    <!-- delete task list modal -->
    <TransitionRoot appear :show="isDeleteTaskListModal" as="template">
      <Dialog as="div" @close="isDeleteTaskListModal = false" class="relative z-[51]">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0">
          <DialogOverlay class="fixed inset-0 bg-[black]/60" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
          <div class="flex min-h-full items-center justify-center px-4 py-8">
            <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95">
              <DialogPanel
                class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-3xl text-black dark:text-white-dark">
                <button
                  type="button"
                  class="absolute top-4 ltr:right-4 rtl:left-4 text-gray-400 hover:text-gray-800 dark:hover:text-gray-600 outline-none"
                  @click="isDeleteTaskListModal = false">
                  <icon-x />
                </button>
                <div
                  class="text-lg font-medium bg-[#fbfbfb] dark:bg-[#121c2c] ltr:pl-5 rtl:pr-5 py-3 ltr:pr-[50px] rtl:pl-[50px]">
                  {{ $t('del_task_list') }}
                </div>
                <div class="p-5 text-center">
                  <div
                    class="text-white bg-danger ring-4 ring-danger/30 p-4 rounded-full w-fit mx-auto">
                    <icon-trash-lines />
                  </div>
                  <div class="text-base sm:w-3/4 mx-auto mt-5">
                    {{ $t('sure_del_task_list') }}
                  </div>

                  <div class="flex justify-center items-center mt-8">
                    <button
                      type="button"
                      class="btn btn-outline-danger"
                      @click="isDeleteTaskListModal = false">
                      {{ $t('cancel') }}
                    </button>
                    <Link
                      :href="route('task-list.destroy', taskListForm.id)"
                      method="delete"
                      as="button"
                      class="btn btn-primary ltr:ml-4 rtl:mr-4"
                      @click="closeTaskListModal">
                      {{ $t('delete') }}
                    </Link>
                  </div>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>

    <!-- create task modal -->
    <TransitionRoot appear :show="isAddTask" as="template">
      <Dialog as="div" @close="isAddTask = false" class="relative z-50">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0">
          <DialogOverlay class="fixed inset-0 bg-[black]/60" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
          <div class="flex min-h-full items-center justify-center">
            <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95">
              <DialogPanel
                class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-2xl text-black dark:text-white-dark">
                <button
                  type="button"
                  class="absolute top-5 ltr:right-5 rtl:left-5 text-white-dark hover:text-dark outline-none"
                  @click="closeTaskModal">
                  <icon-x />
                </button>
                <div
                  class="text-lg font-semibold ltr:pl-5 rtl:pr-5 py-5 ltr:pr-[50px] rtl:pl-[50px]">
                  Thêm nhiệm vụ
                </div>
                <div class="p-5">
                  <form class="space-y-5" @submit.prevent="saveTask">
                    <div class="mb-4">
                      <label for="">{{ $t('title') }}</label>
                      <div class="relative">
                        <span
                          class="absolute ltr:left-3 rtl:right-3 top-1/2 -translate-y-1/2 dark:text-white-dark">
                          <icon-pencil-paper />
                        </span>
                        <input
                          type="text"
                          v-model="taskForm.title"
                          placeholder="Title"
                          class="form-input ltr:pl-10 rtl:pr-10" />
                      </div>
                      <input-error :message="taskForm.errors.title" />
                    </div>
                    <div class="mb-4">
                      <label for="">{{ $t('add_tags') }}</label>
                      <div class="relative">
                        <span
                          class="absolute ltr:left-3 rtl:right-3 top-1/2 -translate-y-1/2 dark:text-white-dark">
                          <icon-tag />
                        </span>
                        <multiselect
                          v-model="taskForm.selectedTags"
                          :options="tags"
                          class="custom-multiselect"
                          label="slug"
                          :multiple="true"
                          track-by="id">
                          <template #tag="props">
                            <span
                              class="option__desc px-2 py-1 rounded text-white font-bold"
                              :class="`bg-${props.option.color} `">
                              <span class="option__title">{{ $t(props.option.slug) }}</span>
                            </span>
                          </template>
                          <template #option="props">
                            <span
                              class="option__desc px-2 py-1 rounded text-white font-bold"
                              :class="`bg-${props.option.color} `">
                              <span class="option__title">{{ $t(props.option.slug) }}</span>
                            </span>
                          </template>
                        </multiselect>
                      </div>
                      <input-error :message="taskListForm.errors.tags" />
                    </div>
                    <div class="mb-4">
                      <label for="">{{ $t('Deadline') }}</label>
                      <flat-pickr
                        class="form-input"
                        v-model="taskForm.due_date"
                        :config="datepickerOptions" />
                      <input-error :message="taskForm.errors.due_date" />
                    </div>
                    <div class="mb-4">
                      <label for="">{{ $t('assigner') }} | {{ $t('suppervisor') }}</label>
                      <div class="relative">
                        <span
                          class="absolute ltr:left-3 rtl:right-3 top-1/2 -translate-y-1/2 dark:text-white-dark">
                          <icon-users />
                        </span>
                        <multiselect
                          v-model="taskForm.assigner"
                          :options="assigners"
                          class="custom-multiselect"
                          label="name"
                          :disabled="page.props.auth.user.role !== 'member'"
                          track-by="id"
                          :option-height="104"
                          :searchable="true"
                          placeholder="Select an user to assign">
                          <template #singleLabel="props">
                            <div class="flex items-center gap-2">
                              <img
                                class="h-8 w-8 rounded-full mr-2"
                                :src="props.option.avatar"
                                alt="avatar" />
                              <span class="option__desc">
                                <span class="option__title">{{ props.option.name }}</span>
                              </span>
                            </div>
                          </template>
                          <template #option="props">
                            <div class="flex gap-2 items-center">
                              <img
                                class="h-8 w-8 rounded-full"
                                :src="props.option.avatar"
                                alt="avatar" />
                              <div class="option__desc">
                                <span class="option__title">{{ props.option.name }}</span>
                              </div>
                            </div>
                          </template>
                        </multiselect>
                      </div>
                      <input-error :message="taskForm.errors.assigner_id" />
                    </div>
                    <div class="mb-4">
                      <label for="">{{ $t('assignee') }} | {{ $t('doer') }}</label>
                      <div class="relative">
                        <span
                          class="absolute ltr:left-3 rtl:right-3 top-1/2 -translate-y-1/2 dark:text-white-dark">
                          <icon-users />
                        </span>
                        <multiselect
                          v-model="taskForm.assignee"
                          :options="assignees"
                          label="name"
                          class="custom-multiselect"
                          track-by="id"
                          :disabled="page.props.auth.user.role === 'member'"
                          :option-height="104"
                          :searchable="true"
                          placeholder="Select an user to assign">
                          <template #singleLabel="props">
                            <div class="flex items-center gap-2">
                              <img
                                class="h-8 w-8 rounded-full mr-2"
                                :src="props.option.avatar"
                                alt="avatar" />
                              <span class="option__desc">
                                <span class="option__title">{{ props.option.name }}</span>
                              </span>
                            </div>
                          </template>
                          <template #option="props">
                            <div class="flex gap-2 items-center">
                              <img
                                class="h-8 w-8 rounded-full"
                                :src="props.option.avatar"
                                alt="avatar" />
                              <div class="option__desc">
                                <span class="option__title">{{ props.option.name }}</span>
                              </div>
                            </div>
                          </template>
                        </multiselect>
                      </div>
                      <input-error :message="taskForm.errors.assignee_id" />
                    </div>
                    <div class="mb-4">
                      <label for="">{{ $t('task_description') }}</label>
                      <ckeditor
                        :config="options.editorConfig"
                        v-model="taskForm.description"
                        :editor="options.editor" />
                      <input-error :message="taskForm.errors.description" />
                    </div>
                    <div class="flex gap-x-4">
                      <button
                        type="button"
                        class="btn btn-outline-danger w-full"
                        @click="closeTaskModal">
                        {{ $t('cancel') }}
                      </button>
                      <button type="submit" class="btn btn-primary w-full">
                        {{ $t('save') }}
                      </button>
                    </div>
                  </form>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </div>
</template>

<script lang="ts" setup>
import type { task_list, user } from '@/interfaces/index.interfaces';
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogOverlay
} from '@headlessui/vue';
import { ref, defineProps, defineEmits } from 'vue';
import VueCollapsible from 'vue-height-collapsible/vue3';
import IconCaretDown from '@/Components/icon/icon-caret-down.vue';
import TaskDetail from '@/Pages/Task/TaskDetail.vue';
import IconPlus from '@/Components/icon/icon-plus.vue';
import Tag from '@/Components/TagComponent.vue';
import IconNotes from '@/Components/icon/icon-notes.vue';
import IconMenu from '@/Components/icon/icon-menu.vue';
import IconClock from '@/Components/icon/icon-clock.vue';
import IconUser from '@/Components/icon/icon-user.vue';
import IconX from '@/Components/icon/icon-x.vue';
import IconHorizontalDots from '@/Components/icon/icon-horizontal-dots.vue';
import IconTrashLines from '@/Components/icon/icon-trash-lines.vue';
import { useForm, Link, usePage } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import { editorConfig } from '@/Components/plugins/ckeditor';
import FlatPickr from 'vue-flatpickr-component';
import Multiselect from '@suadelabs/vue3-multiselect';
import IconPencilPaper from '@/Components/icon/icon-pencil-paper.vue';
import IconTag from '@/Components/icon/icon-tag.vue';

const options = ref(editorConfig);
const taskListForm = useForm({
  id: null,
  name: '',
  project_id: 0
});

const page = usePage();
const selectedTask = ref(0);
const isAddTaskListModal = ref(false);
const isDeleteTaskListModal = ref(false);
const accordions1 = ref<Array<number>>([]);
const isShowMenu = ref(false);
const emits = defineEmits(['update:selectedTask']);
const isAddTask = ref(false);

const props = defineProps({
  task_lists: {
    type: Array as () => task_list[],
    required: true
  },
  assigners: {
    type: Array as () => user[],
    required: true
  },
  assignees: {
    type: Array as () => user[],
    required: true
  },
  project_id: {
    type: Number,
    required: true
  },
  tags: {
    type: Array as () => Array<{ id: number; name: string; color: string; slug: string }>,
    required: true
  }
});

const taskForm = useForm({
  title: '',
  description: '',
  due_date: '',
  result: '',
  status: 'pending',
  assignee: page.props.auth.user.role === 'member' ? page.props.auth.user : null,
  assigner: page.props.auth.user.role !== 'member' ? page.props.auth.user : null,
  assignee_id: 0,
  assigner_id: 0,
  attachments: [],
  created_by: page.props.auth.user.id,
  task_list_id: 0,
  project_id: props.project_id,
  tags: [] as Array<number>,
  selectedTags: [] as Array<{ id: number; slug: string; color: string }>
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

const isInAccordion = (number: number) => {
  return accordions1.value.indexOf(number) > -1;
};

const onSelectTask = (taskId: number) => {
  selectedTask.value = selectedTask.value == taskId ? 0 : taskId;
  isShowMenu.value = false;
  emits('update:selectedTask', selectedTask.value);
};

const onClose = () => {
  selectedTask.value = 0;
  emits('update:selectedTask', 0);
};

const scrollToBottom = () => {
  setTimeout(() => {
    const element: any = document.querySelector('.detail-box');
    element.behavior = 'smooth';
    element.scrollTop = element.scrollHeight;
  });
};

const addEditTaskList = (taskList: any = null) => {
  taskListForm.id = null;
  taskListForm.name = '';
  taskListForm.project_id = props.project_id;
  if (taskList) {
    taskListForm.id = taskList.id;
    taskListForm.name = taskList.name;
  }
  isAddTaskListModal.value = true;
};

const delTaskList = (taskList: any) => {
  taskListForm.id = taskList ? taskList.id : null;
  isDeleteTaskListModal.value = true;
};

const saveTaskList = () => {
  if (!taskListForm.id) {
    taskListForm.post(route('task-list.store'), {
      onSuccess: () => {
        closeTaskListModal();
      }
    });
  } else {
    taskListForm.patch(route('task-list.update', taskListForm.id), {
      onSuccess: () => {
        closeTaskListModal();
      }
    });
  }
};

const onTienDo = () => {
  alert('Nut duoc bam');
};

const clearTaskForm = () => {
  taskForm.reset();
  taskForm.created_by = page.props.auth.user.id;
};

const addTask = (task_list_id: number) => {
  taskForm.task_list_id = task_list_id;
  isAddTask.value = true;
};

const closeTaskModal = () => {
  clearTaskForm();
  isAddTask.value = false;
};

const closeTaskListModal = () => {
  isAddTaskListModal.value = false;
  isDeleteTaskListModal.value = false;
  taskListForm.clearErrors();
};

const saveTask = () => {
  taskForm.assignee_id = taskForm.assignee ? taskForm.assignee.id : null;
  taskForm.assigner_id = taskForm.assigner ? taskForm.assigner.id : null;
  taskForm.tags = taskForm.selectedTags ? taskForm.selectedTags.map((tag: any) => tag.id) : [];
  console.log(taskForm);
  taskForm.post(route('task.store'), {
    onSuccess: () => {
      closeTaskModal();
    }
  });
};
</script>
