<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue';
import { ref, defineProps, onBeforeMount, watch } from 'vue';
import type { project, user } from '@/interfaces/index.interfaces';
import ProjectListView from '@/Components/project/ProjectListView.vue';
import ProjectBoardView from '@/Components/project/ProjectBoardView.vue';
import { useAppStore } from '@/stores';
import SubDetail from '../Profile/SubDetail.vue';
import ProjectAnalyze from '@/Components/project/ProjectAnalyze.vue';

const appStore = useAppStore();
const accordions1 = ref<Array<number>>([]);
const selectedTask = ref(0);
const projectViewStyleList = ref<Array<string>>([]);
const projectViewStyle = ref<string>(localStorage.getItem('project_view_style') || 'list');

watch(projectViewStyle, (value) => {
  appStore.toggleProjectViewStyle(value);
});

const props = defineProps({
  project: {
    type: Object as () => project,
    required: true
  },
  assignees: {
    type: Array as () => user[],
    required: true
  },
  assigners: {
    type: Array as () => user[],
    required: true
  }
});

onBeforeMount(() => {
  if (props.project.task_lists.length > 0) {
    props.project.task_lists.forEach((taskList) => {
      accordions1.value.push(taskList.id);
    });
  }
  projectViewStyleList.value = appStore.projectViewStyleList;
});

const onUpdateSelectedTask = (taskId: number) => {
  selectedTask.value = taskId;
};
</script>

<template>
  <AuthenticatedLayout>
    <Head :title="project.title" />
    <div class="w-full mx-auto">
      <div>
        <div class="relative">
          <div :class="{ 'lg:grid lg:grid-cols-12': !selectedTask }">
            <div :class="{ 'col-span-9': !selectedTask }">
              <TabGroup as="div">
                <TabList class="flex flex-wrap mt-3 gap-1">
                  <Tab as="template" v-slot="{ selected }">
                    <div>
                      <button
                        class="p-3.5 py-2 -mb-[1px] block rounded hover:bg-primary hover:text-white !outline-none transition duration-300"
                        :class="{ 'bg-primary text-white': selected }">
                        My jobs
                      </button>
                    </div>
                  </Tab>
                  <Tab as="template" v-slot="{ selected }">
                    <button
                      class="p-3.5 py-2 -mb-[1px] ltr:mr-2 rtl:ml-2 block rounded hover:bg-primary hover:text-white !outline-none transition duration-300"
                      :class="{ 'bg-primary text-white': selected }">
                      Detail
                    </button>
                  </Tab>
                  <Tab as="template" v-slot="{ selected }">
                    <button
                      class="p-3.5 py-2 -mb-[1px] block rounded hover:bg-primary hover:text-white !outline-none transition duration-300"
                      :class="{ 'bg-primary text-white': selected }">
                      Analyze
                    </button>
                  </Tab>
                </TabList>
                <TabPanels class="pt-5 text-md">
                  <TabPanel>
                    <div class="mb-2 w-36">
                      <select
                        name="view-style"
                        class="form-select text-white-dark"
                        v-model="projectViewStyle">
                        <option
                          :value="style"
                          v-for="(style, index) in projectViewStyleList"
                          :key="index">
                          {{ style }}
                        </option>
                      </select>
                    </div>
                    <!-- collapsible -->
                    <div v-if="projectViewStyle === 'list'">
                      <project-list-view
                        :project_id="project.id"
                        :assignees="assignees"
                        :assigners="assigners"
                        :task_lists="project.task_lists"
                        @update:selected-task="onUpdateSelectedTask" />
                    </div>
                    <div v-else-if="projectViewStyle === 'board'">
                      <project-board-view />
                    </div>
                  </TabPanel>
                  <TabPanel>
                    <div class="flex flex-col gap-5">
                      <div class="flex items-start panel rounded-lg shadow-md">
                        <div class="w-32 ltr:mr-4 rtl:ml-4 flex-none text-lg font-bold">
                          {{ $t('Description') }}
                        </div>
                        <div class="flex-auto">
                          <h5 class="text-lg font-medium mb-4">Description</h5>
                          <p v-html="project.description" />
                        </div>
                      </div>
                      <div>
                        <div class="flex items-start panel rounded-lg shadow-md">
                          <div class="w-32 ltr:mr-4 rtl:ml-4 flex-none text-lg font-bold">
                            {{ $t('attachments') }}
                          </div>
                          <div class="flex-auto">
                            <h5 class="text-lg font-medium mb-4">Click to down load</h5>
                            <div
                              v-for="(attachment, index) in project.attachments"
                              :key="index"
                              class="mt-2">
                              <Link
                                method="get"
                                as="button"
                                :href="route('project-attachment.download', attachment.id)"
                                class="text-primary italic underline hover:text-info">
                                {{ attachment.slug }}
                              </Link>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div></TabPanel
                  >
                  <TabPanel>
                    <div class="panel flex-1">
                      <project-analyze :project="project.id" />
                    </div>
                  </TabPanel>
                </TabPanels>
              </TabGroup>
            </div>
            <div
              v-if="!selectedTask"
              class="row-span-12 p-3 bg-primary-light"
              :class="{ 'lg:col-span-3': !selectedTask }">
              <sub-detail />
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
