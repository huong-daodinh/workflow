<template>
  <AuthenticatedLayout>
    <Head :title="projectProp.title" />
    <TabGroup as="div" class="mb-5">
      <TabList class="flex flex-wrap mt-3">
        <Tab as="template" v-slot="{ selected }">
          <button
            class="p-3.5 py-2 -mb-[1px] ltr:mr-2 rtl:ml-2 block rounded hover:bg-primary hover:text-white !outline-none transition duration-300"
            :class="{ 'bg-primary text-white': selected }">
            Task Lists
          </button>
        </Tab>
        <Tab as="template" v-slot="{ selected }">
          <button
            class="p-3.5 py-2 -mb-[1px] ltr:mr-2 rtl:ml-2 block rounded hover:bg-primary hover:text-white !outline-none transition duration-300"
            :class="{ 'bg-primary text-white': selected }">
            Description
          </button>
        </Tab>
        <Tab as="template" v-slot="{ selected }">
          <button
            class="p-3.5 py-2 -mb-[1px] ltr:mr-2 rtl:ml-2 block rounded hover:bg-primary hover:text-white !outline-none transition duration-300"
            :class="{ 'bg-primary text-white': selected }">
            Conversation
          </button>
        </Tab>
      </TabList>
      <TabPanels class="pt-5 flex-1 text-sm">
        <!-- task list -->
        <TabPanel>
          <div>
            <div class="flex">
              <button type="button" class="btn btn-primary flex" @click="addEditProject()">
                <icon-plus class="w-5 h-5 ltr:mr-3 rtl:ml-3" />
                Add Task List
              </button>
            </div>

            <!-- task_list list -->
            <div class="relative mt-5">
              <div class="h-full -mx-2">
                <div class="overflow-x-auto flex items-start flex-nowrap gap-5 px-2 pb-2">
                  <div v-for="project in projectList" :key="project.id">
                    <div class="panel w-80 flex-none">
                      <div class="mb-3">
                        <button
                          type="button"
                          class="btn btn-primary mx-auto"
                          @click="addEditTask(project.id)">
                          <icon-plus />
                          Add Task
                        </button>
                      </div>
                      <div class="flex justify-between mb-5">
                        <h4 class="text-base font-semibold">{{ project.title }}</h4>

                        <div class="flex items-center">
                          <div class="dropdown">
                            <Popper placement="bottom-start" offsetDistance="0">
                              <button type="button" class="hover:text-primary">
                                <icon-horizontal-dots class="opacity-70 hover:opacity-100" />
                              </button>
                              <template #content="{ close }">
                                <ul
                                  @click="close()"
                                  class="text-black dark:text-white-dark whitespace-nowrap">
                                  <li>
                                    <a href="javascript:;" @click="addEditProject(project)"
                                      >Edit
                                    </a>
                                  </li>
                                  <li>
                                    <a href="javascript:;" @click="deleteProject(project)"
                                      >Delete
                                    </a>
                                  </li>
                                  <li>
                                    <a href="javascript:;" @click="clearProjects(project)"
                                      >Clear All
                                    </a>
                                  </li>
                                </ul>
                              </template>
                            </Popper>
                          </div>
                        </div>
                      </div>

                      <!-- task list -->
                      <draggable
                        class="connect-sorting-content min-h-[150px]"
                        group="default"
                        ghost-class="sortable-ghost"
                        drag-class="sortable-drag"
                        :animation="200">
                        <div
                          class="sortable-list"
                          v-for="task in project.tasks"
                          :key="project.id + '' + task.id">
                          <div
                            class="shadow bg-[#f4f4f4] dark:bg-white-dark/20 p-3 pb-5 rounded-md mb-5 space-y-3 cursor-move">
                            <template v-if="task.image">
                              <img
                                src="/assets/images/carousel1.jpeg"
                                alt="images"
                                class="h-32 w-full object-cover rounded-md" />
                            </template>
                            <div class="text-base font-medium">{{ task.title }}</div>
                            <p class="break-all">{{ task.description }}</p>
                            <div class="flex gap-2 items-center flex-wrap">
                              <template v-if="task.tags?.length">
                                <div v-for="(tag, i) in task.tags" :key="i">
                                  <div class="btn px-2 py-1 flex btn-outline-primary">
                                    <icon-tag class="shrink-0" />

                                    <span class="ltr:ml-2 rtl:mr-2">{{ tag }}</span>
                                  </div>
                                </div>
                              </template>
                              <template v-else>
                                <div
                                  class="btn px-2 py-1 flex text-white-dark dark:border-white-dark/50 shadow-none">
                                  <icon-tag class="shrink-0" />
                                  <span class="ltr:ml-2 rtl:mr-2">No Tags</span>
                                </div>
                              </template>
                            </div>
                            <div class="flex items-center justify-between">
                              <div class="font-medium flex items-center hover:text-primary">
                                <icon-calendar class="ltr:mr-3 rtl:ml-3 shrink-0" />
                                <span>{{ task.duelDate }}</span>
                              </div>
                              <div class="flex items-center">
                                <button
                                  type="button"
                                  class="hover:text-info"
                                  @click="addEditTask(project.id, task)">
                                  <icon-edit class="ltr:mr-3 rtl:ml-3" />
                                </button>
                                <button
                                  type="button"
                                  class="hover:text-danger"
                                  @click="deleteConfirmModal(project.id, task)">
                                  <icon-trash-lines />
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </draggable>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </TabPanel>
        <!-- attachments -->
        <TabPanel>
          <div class="flex flex-col gap-5">
            <div class="flex items-start panel rounded-lg shadow-md">
              <div class="w-32 ltr:mr-4 rtl:ml-4 flex-none text-lg font-bold">
                {{ $t('description') }}
              </div>
              <div class="flex-auto">
                <h5 class="text-lg font-medium mb-4">Media heading</h5>
                <p v-html="projectProp.description" />
              </div>
            </div>
            <div>
              <div class="flex items-start panel rounded-lg shadow-md">
                <div class="w-32 ltr:mr-4 rtl:ml-4 flex-none text-lg font-bold">
                  {{ $t('attachments') }}
                </div>
                <div class="flex-auto">
                  <h5 class="text-lg font-medium mb-4">Media heading</h5>
                  <div
                    v-for="(attachment, index) in projectProp.attachments"
                    :key="index"
                    class="mt-2">
                    <a
                      :href="route('project-attachment.download', attachment.id)"
                      class="text-primary italic underline hover:text-info">
                      {{ attachment.slug }}
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </TabPanel>

        <TabPanel>
          <div class="panel p-0 flex-1">
            <template v-if="!isShowUserChat">
              <div class="flex items-center justify-center h-full relative p-4">
                <div class="py-8 flex items-center justify-center flex-col">
                  <div
                    class="w-[280px] md:w-[430px] mb-8 h-[calc(100vh_-_320px)] min-h-[120px] text-white dark:text-[#0e1726]"></div>
                </div>
              </div>
            </template>
            <template v-if="isShowUserChat && selectedUser">
              <div class="relative h-full">
                <div class="flex justify-between items-center p-4">
                  <div class="flex items-center space-x-2 rtl:space-x-reverse">
                    <button
                      type="button"
                      class="xl:hidden hover:text-primary"
                      @click="isShowChatMenu = !isShowChatMenu">
                      <icon-menu />
                    </button>
                    <div class="relative flex-none">
                      <img
                        :src="`/assets/images/${selectedUser.path}`"
                        class="rounded-full w-10 h-10 sm:h-12 sm:w-12 object-cover" />
                      <div class="absolute bottom-0 ltr:right-0 rtl:left-0">
                        <div class="w-4 h-4 bg-success rounded-full"></div>
                      </div>
                    </div>
                    <div class="mx-3">
                      <p class="font-semibold">{{ selectedUser.name }}</p>
                      <p class="text-white-dark text-xs">
                        {{
                          selectedUser.active ? 'Active now' : 'Last seen at ' + selectedUser.time
                        }}
                      </p>
                    </div>
                  </div>
                  <div class="flex sm:gap-5 gap-3">
                    <button
                      type="button"
                      class="bg-[#f4f4f4] dark:bg-[#1b2e4b] hover:bg-primary-light w-8 h-8 rounded-full !flex justify-center items-center">
                      <icon-horizontal-dots class="hover:text-primary rotate-90 opacity-70" />
                    </button>
                  </div>
                </div>
                <div class="h-px w-full border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                <div class="p-4 absolute bottom-0 left-0 w-full">
                  <div class="sm:flex w-full space-x-3 rtl:space-x-reverse items-center">
                    <div class="relative flex-1">
                      <input
                        class="form-input rounded-full border-0 bg-[#f4f4f4] px-12 focus:outline-none py-2"
                        placeholder="Type a message"
                        v-model="textMessage"
                        @keyup.enter.exact="sendMessage()" />
                      <button
                        type="button"
                        class="absolute ltr:left-4 rtl:right-4 top-1/2 -translate-y-1/2 hover:text-primary">
                        <icon-mood-smile />
                      </button>
                      <button
                        type="button"
                        class="absolute ltr:right-4 rtl:left-4 top-1/2 -translate-y-1/2 hover:text-primary"
                        @click="sendMessage()">
                        <icon-send />
                      </button>
                    </div>
                    <div
                      class="items-center space-x-3 rtl:space-x-reverse sm:py-0 py-3 hidden sm:block">
                      <button
                        type="button"
                        class="bg-[#f4f4f4] dark:bg-[#1b2e4b] hover:bg-primary-light rounded-md p-2 hover:text-primary">
                        <icon-horizontal-dots class="opacity-70" />
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </template>
          </div>
        </TabPanel>
      </TabPanels>
    </TabGroup>
    <!-- add task list modal -->
    <TransitionRoot appear :show="isAddProjectModal" as="template">
      <Dialog as="div" @close="isAddProjectModal = false" class="relative z-[51]">
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
                class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg text-black dark:text-white-dark">
                <button
                  type="button"
                  class="absolute top-4 ltr:right-4 rtl:left-4 text-gray-400 hover:text-gray-800 dark:hover:text-gray-600 outline-none"
                  @click="isAddProjectModal = false">
                  <icon-x />
                </button>
                <div
                  class="text-lg font-medium bg-[#fbfbfb] dark:bg-[#121c2c] ltr:pl-5 rtl:pr-5 py-3 ltr:pr-[50px] rtl:pl-[50px]">
                  {{ params.id ? 'Edit Task List' : 'Add Task List' }}
                </div>
                <div class="p-5">
                  <form @submit.prevent="saveProject">
                    <div class="grid gap-5">
                      <div>
                        <label for="title">Name</label>
                        <input
                          id="title"
                          v-model="params.title"
                          type="text"
                          class="form-input mt-1"
                          placeholder="Enter Name" />
                      </div>
                    </div>

                    <div class="flex justify-end items-center mt-8">
                      <button
                        type="button"
                        class="btn btn-outline-danger"
                        @click="isAddProjectModal = false">
                        Cancel
                      </button>
                      <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">
                        {{ params.id ? 'Update' : 'Add' }}
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

    <!-- add task modal -->
    <TransitionRoot appear :show="isAddTaskModal" as="template">
      <Dialog as="div" @close="isAddTaskModal = false" class="relative z-[51]">
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
                class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg text-black dark:text-white-dark">
                <button
                  type="button"
                  class="absolute top-4 ltr:right-4 rtl:left-4 text-gray-400 hover:text-gray-800 dark:hover:text-gray-600 outline-none"
                  @click="isAddTaskModal = false">
                  <icon-x />
                </button>
                <div
                  class="text-lg font-medium bg-[#fbfbfb] dark:bg-[#121c2c] ltr:pl-5 rtl:pr-5 py-3 ltr:pr-[50px] rtl:pl-[50px]">
                  {{ paramsTask.id ? 'Edit Task' : 'Add Task' }}
                </div>
                <div class="p-5">
                  <form @submit.prevent="saveTask">
                    <div class="grid gap-5">
                      <div>
                        <label for="taskTitle">Name</label>
                        <input
                          id="taskTitle"
                          v-model="paramsTask.title"
                          type="text"
                          class="form-input"
                          placeholder="Enter Name" />
                      </div>

                      <div>
                        <label for="taskTag">Tag</label>
                        <input
                          id="taskTag"
                          v-model="paramsTask.tags"
                          type="text"
                          class="form-input"
                          placeholder="Enter Tag" />
                      </div>
                      <div>
                        <label for="duelDate">Duel date</label>
                        <flat-pickr
                          id="duelDate"
                          v-model="paramsTask.duelDate"
                          class="form-input"
                          :config="basic"></flat-pickr>
                      </div>
                      <div class="mt-2">
                        <label for="">Executor</label>
                        <!-- <multiselect
                          id="nguoithuchien"
                          v-model="paramsTask.attachedTo"
                          :options="options"
                          :searchable="true"
                          placeholder="Select user"
                          label="name" /> -->
                      </div>
                      <div>
                        <label for="taskdesc">Description</label>
                        <quillEditor
                          ref="editor1"
                          v-model="paramsTask.description"
                          :options="options1"
                          style="height: 100px"></quillEditor>
                      </div>
                    </div>

                    <div class="flex justify-end items-center mt-8">
                      <button
                        type="button"
                        class="btn btn-outline-danger"
                        @click="isAddTaskModal = false">
                        Cancel
                      </button>
                      <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">
                        {{ paramsTask.id ? 'Update' : 'Add' }}
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

    <!-- delete task modal -->
    <TransitionRoot appear :show="isDeleteModal" as="template">
      <Dialog as="div" @close="isDeleteModal = false" class="relative z-[51]">
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
                  @click="isDeleteModal = false">
                  <icon-x />
                </button>
                <div
                  class="text-lg font-medium bg-[#fbfbfb] dark:bg-[#121c2c] ltr:pl-5 rtl:pr-5 py-3 ltr:pr-[50px] rtl:pl-[50px]">
                  Delete Task
                </div>
                <div class="p-5 text-center">
                  <div
                    class="text-white bg-danger ring-4 ring-danger/30 p-4 rounded-full w-fit mx-auto">
                    <icon-trash-lines />
                  </div>
                  <div class="text-base sm:w-3/4 mx-auto mt-5">
                    Are you sure you want to delete Task?
                  </div>

                  <div class="flex justify-center items-center mt-8">
                    <button
                      type="button"
                      class="btn btn-outline-danger"
                      @click="isDeleteModal = false">
                      Cancel
                    </button>
                    <button
                      type="button"
                      class="btn btn-primary ltr:ml-4 rtl:mr-4"
                      @click="deleteTask()">
                      Delete
                    </button>
                  </div>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </AuthenticatedLayout>
</template>
<script lang="ts" setup>
import { onMounted, ref } from 'vue';
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogOverlay
} from '@headlessui/vue';
import '@suadelabs/vue3-multiselect/dist/vue3-multiselect.css';
import { VueDraggableNext as draggable } from 'vue-draggable-next';
import Swal from 'sweetalert2';
import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import { quillEditor } from 'vue3-quill';
import 'vue3-quill/lib/vue3-quill.css';
import IconPlus from '@/Components/icon/icon-plus.vue';
import IconHorizontalDots from '@/Components/icon/icon-horizontal-dots.vue';
import IconTag from '@/Components/icon/icon-tag.vue';
import IconCalendar from '@/Components/icon/icon-calendar.vue';
import IconEdit from '@/Components/icon/icon-edit.vue';
import IconTrashLines from '@/Components/icon/icon-trash-lines.vue';
import IconX from '@/Components/icon/icon-x.vue';
import { Head, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue';

defineProps({
  projectProp: Object
});

const options1 = ref({
  modules: {
    toolbar: [
      [{ header: [1, 2, false] }],
      ['bold', 'italic', 'underline', 'link'],
      [{ list: 'ordered' }, { list: 'bullet' }],
      ['clean']
    ]
  },
  placeholder: ''
});

const page = usePage();
const options = ref([]);
const basic: any = ref({
  dateFormat: 'Y-m-d',
  position: 'auto left'
});

const params = ref({
  id: null,
  title: ''
});

const paramsTask = ref({
  projectId: null,
  id: null,
  title: '',
  description: '',
  tags: '',
  duelDate: '',
  attachedTo: ''
});

const selectedTask: any = ref(null);
const isAddProjectModal = ref(false);
const isAddTaskModal = ref(false);
const isDeleteModal = ref(false);
const projectList: any = ref([]);

const addEditProject = (project: any = null) => {
  setTimeout(() => {
    params.value = {
      id: null,
      title: ''
    };
    if (project) {
      params.value = JSON.parse(JSON.stringify(project));
    }

    isAddProjectModal.value = true;
  });
};

const saveProject = () => {
  if (!params.value.title) {
    showMessage('Title is required.', 'error');
    return false;
  }

  if (params.value.id) {
    //update project
    const project = projectList.value.find((d: any) => d.id === params.value.id);
    project.title = params.value.title;
  } else {
    //add project
    const lastId = projectList.value.length
      ? projectList.value.reduce(
          (max: number, obj: any) => (obj.id > max ? obj.id : max),
          projectList.value[0].id
        )
      : 0;

    const project = {
      id: lastId + 1,
      title: params.value.title,
      tasks: []
    };
    projectList.value.push(project);
  }

  showMessage('Task has been saved successfully.');
  isAddProjectModal.value = false;
};

const deleteProject = (project: any) => {
  projectList.value = projectList.value.filter((d: any) => d.id != project.id);
  showMessage('Task has been deleted successfully.');
};

const clearProjects = (project: any) => {
  project.tasks = [];
};

// task
const addEditTask = (projectId: any, task: any = null) => {
  paramsTask.value = {
    projectId: null,
    id: null,
    title: '',
    description: '',
    tags: '',
    duelDate: '',
    attachedTo: ''
  };
  if (task) {
    paramsTask.value = JSON.parse(JSON.stringify(task));
    paramsTask.value.tags = paramsTask.value.tags ? paramsTask.value.tags.toString() : '';
  }
  paramsTask.value.projectId = projectId;
  isAddTaskModal.value = true;
};

const saveTask = () => {
  if (!paramsTask.value.title) {
    showMessage('Title is required.', 'error');
    return false;
  }

  const project = projectList.value.find((d: any) => d.id === paramsTask.value.projectId);
  if (paramsTask.value.id) {
    //update task
    const task = project.tasks.find((d: any) => d.id === paramsTask.value.id);
    task.title = paramsTask.value.title;
    task.description = paramsTask.value.description;
    task.tags = paramsTask.value.tags?.length > 0 ? paramsTask.value.tags.split(',') : [];
  } else {
    //add task
    let maxid = 0;
    if (project.tasks?.length) {
      maxid = project.tasks.reduce(
        (max: number, obj: any) => (obj.id > max ? obj.id : max),
        project.tasks[0].id
      );
    }

    const duelDate = new Date(paramsTask.value.duelDate);
    const dd = String(duelDate.getDate()).padStart(2, '0');
    const mm = String(duelDate.getMonth()); //January is 0!
    const yyyy = duelDate.getFullYear();
    const monthNames: any = [
      'Jan',
      'Feb',
      'Mar',
      'Apr',
      'May',
      'Jun',
      'Jul',
      'Aug',
      'Sep',
      'Oct',
      'Nov',
      'Dec'
    ];

    const task = {
      projectId: paramsTask.value.projectId,
      id: maxid + 1,
      title: paramsTask.value.title,
      description: paramsTask.value.description,
      duelDate: dd + ' ' + monthNames[mm] + ', ' + yyyy,
      tags: paramsTask.value.tags?.length > 0 ? paramsTask.value.tags.split(',') : []
    };

    project.tasks.push(task);
  }

  showMessage('Task has been saved successfully.');
  isAddTaskModal.value = false;
};

const deleteConfirmModal = (projectId: any, task: any = null) => {
  selectedTask.value = task;
  setTimeout(() => {
    isDeleteModal.value = true;
  }, 10);
};

const deleteTask = () => {
  let project = projectList.value.find((d: any) => d.id === selectedTask.value.projectId);
  project.tasks = project.tasks.filter((d: any) => d.id != selectedTask.value.id);

  showMessage('Task has been deleted successfully.');
  isDeleteModal.value = false;
};

const showMessage = (msg = '', type = 'success') => {
  const toast: any = Swal.mixin({
    toast: true,
    position: 'top',
    showConfirmButton: false,
    timer: 3000,
    customClass: { container: 'toast' }
  });
  toast.fire({
    icon: type,
    title: msg,
    padding: '10px 20px'
  });
};

onMounted(() => {
  options.value = page.props.availableUsers;
});
</script>
