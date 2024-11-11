<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue';
import { ref } from 'vue';
import iconCaretDown from '@/Components/icon/icon-caret-down.vue';
import VueCollapsible from 'vue-height-collapsible/vue3';
import TaskDetail from '@/Components/task/TaskDetail.vue';
import SubDetail from './Profile/SubDetail.vue';

const accordions1 = ref<Array<number>>([]);
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
const isShow = ref(false);
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Dashboard" />
    <div class="w-full mx-auto grid gap-3 grid-cols-12 h-[calc(93vh-80px)]">
      <div
        class="bg-white shadow-[4px_6px_10px_-3px_#bfc9d4] border border-[#e0e6ed] sm:rounded-lg col-span-12"
        :class="{ 'md:col-span-3': isShow, 'md:col-span-9': !isShow }">
        <div class="relative h-[calc(95vh-80px)] overflow-y-auto">
          <div class="p-3 flex">
            <TabGroup as="div" class="mb-5">
              <TabList class="flex flex-wrap mt-3 gap-1">
                <Tab as="template" v-slot="{ selected }">
                  <button
                    class="p-3.5 py-2 -mb-[1px] block rounded hover:bg-primary hover:text-white !outline-none transition duration-300"
                    :class="{ 'bg-primary text-white': selected }">
                    My jobs
                  </button>
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
              <TabPanels class="pt-5 flex-1 text-md">
                <TabPanel>
                  <!-- collapsible -->
                  <div class="space-y-2 font-semibold">
                    <div class="border border-[#d3d3d3] rounded dark:border-[#1b2e4b]">
                      <button
                        type="button"
                        class="p-4 w-full flex items-center text-white-dark dark:bg-[#1b2e4b]"
                        :class="{ '!text-primary': inAccordion(1) }"
                        @click="inAccordion(1) ? removeAccordion(1) : addAccordion(1)">
                        Collapsible Group Item #1
                        <div
                          class="ltr:ml-auto rtl:mr-auto"
                          :class="{ 'rotate-180': inAccordion(1) }">
                          <icon-caret-down />
                        </div>
                      </button>
                      <vue-collapsible :isOpen="inAccordion(1)">
                        <div class="flex mb-5 p-5">
                          <div class="ltr:mr-4 rtl:ml-4">
                            <input type="checkbox" class="form-checkbox mt-1.5" />
                          </div>
                          <div class="flex-1">
                            <h4
                              class="font-semibold text-lg text-primary"
                              @click="isShow = !isShow">
                              Heading
                            </h4>
                            <p class="media-text">
                              Fusce condimentum cursus mauris et ornare. Mauris fermentum mi id
                              sollicitudin viverra. Aenean dignissim sed ante eget dapibus. Sed
                              dapibus nulla elementum, rutrum neque eu, gravida neque.
                            </p>
                            <div class="flex mt-5">
                              <div class="mr-4">
                                <input type="checkbox" class="form-checkbox mt-1.5" />
                              </div>
                              <div class="flex-1">
                                <h4 class="font-semibold text-lg mb-2 text-primary">Heading</h4>
                                <p class="media-text">
                                  Fusce condimentum cursus mauris et ornare. Mauris fermentum mi id
                                  sollicitudin viverra. Aenean dignissim sed ante eget dapibus. Sed
                                  dapibus nulla elementum, rutrum neque eu, gravida neque.
                                </p>
                              </div>
                            </div>
                            <div class="flex mt-5">
                              <div class="mr-4">
                                <input type="checkbox" class="form-checkbox mt-1.5" />
                              </div>
                              <div class="flex-1">
                                <h4 class="font-semibold text-lg mb-2 text-primary">Heading</h4>
                                <p class="media-text">
                                  Fusce condimentum cursus mauris et ornare. Mauris fermentum mi id
                                  sollicitudin viverra. Aenean dignissim sed ante eget dapibus. Sed
                                  dapibus nulla elementum, rutrum neque eu, gravida neque.
                                </p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </vue-collapsible>
                    </div>
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
      <div
        v-if="isShow"
        class="bg-white shadow-[4px_6px_10px_-3px_#bfc9d4] md:col-span-9 col-span-12 rounded-md border border-[#e0e6ed] dark:border-[#1b2e4b] dark:bg-[#191e3a] dark:shadow-none">
        <task-detail @close="isShow = false" :taskId="1" />
      </div>
      <div class="md:block hidden col-span-3" :class="{ 'md:hidden': isShow }">
        <sub-detail />
      </div>
    </div>
  </AuthenticatedLayout>
</template>
