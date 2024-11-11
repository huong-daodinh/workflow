<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue';
import { ref, defineProps, onBeforeMount, watch } from 'vue';
import type { project } from '@/interfaces/index.interfaces';
import ProjectListView from '@/Components/project/ProjectListView.vue';
import ProjectBoardView from '@/Components/project/ProjectBoardView.vue';
import { useAppStore } from '@/stores';

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
      <div :class="{ 'md:col-span-3': selectedTask, 'md:col-span-9': !selectedTask }">
        <div class="relative">
          <div>
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
                    class="p-3.5 py-2 -mb-[1px] block rounded hover:bg-primary hover:text-white !outline-none transition duration-300"
                    :class="{ 'bg-primary text-white': selected }">
                    My employees
                  </button>
                </Tab>
                <Tab as="template" v-slot="{ selected }">
                  <button
                    class="p-3.5 py-2 -mb-[1px] block rounded hover:bg-primary hover:text-white !outline-none transition duration-300"
                    :class="{ 'bg-primary text-white': selected }">
                    Followings
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
                      :task_lists="project.task_lists"
                      @update:selected-task="onUpdateSelectedTask" />
                  </div>
                  <div v-else-if="projectViewStyle === 'board'">
                    <project-board-view />
                  </div>
                </TabPanel>
                <TabPanel>
                  <div class="flex items-start">
                    <div class="w-20 h-20 ltr:mr-4 rtl:ml-4 flex-none">
                      <img
                        src="/assets/images/profile-34.jpeg"
                        alt=""
                        class="w-20 h-20 m-0 rounded-full ring-2 ring-[#ebedf2] dark:ring-white-dark object-cover" />
                    </div>
                    <div class="flex-auto">
                      <h5 class="text-xl font-medium mb-4">Media heading</h5>
                      <p class="text-white-dark">
                        Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque
                        ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus
                        viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec
                        lacinia congue felis in faucibus.
                      </p>
                    </div>
                  </div>
                </TabPanel>
                <TabPanel>
                  <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                    incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
                    nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                    Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
                    culpa qui officia deserunt mollit anim id est laborum.
                  </p>
                </TabPanel>
              </TabPanels>
            </TabGroup>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
