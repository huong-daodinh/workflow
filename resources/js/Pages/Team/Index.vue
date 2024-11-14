<template>
  <AuthenticatedLayout>
    <Head title="Team" />
    <div>
      <div class="flex gap-5 relative sm:h-[calc(100vh_-_150px)] h-full">
        <div
          class="bg-black/60 z-10 w-full h-full rounded-md absolute hidden"
          :class="{ '!block xl:!hidden': isShowNoteMenu }"
          @click="isShowNoteMenu = !isShowNoteMenu"></div>
        <div
          class="panel p-4 flex-none w-[240px] absolute xl:relative z-10 space-y-4 h-full xl:h-auto hidden xl:block ltr:lg:rounded-r-md ltr:rounded-r-none rtl:lg:rounded-l-md rtl:rounded-l-none overflow-hidden"
          :class="{
            'hidden shadow': !isShowNoteMenu,
            '!block h-full ltr:left-0 rtl:right-0': isShowNoteMenu
          }">
          <div class="flex flex-col h-full pb-16">
            <div class="flex text-center items-center">
              <div class="shrink-0">
                <icon-notes />
              </div>
              <h3 class="text-lg font-semibold ltr:ml-3 rtl:mr-3">Teams</h3>
            </div>
            <div class="h-px w-full border-b border-[#e0e6ed] dark:border-[#1b2e4b] my-4"></div>
            <perfect-scrollbar
              :options="{
                swipeEasing: true,
                wheelPropagation: false
              }"
              class="relative ltr:pr-3.5 rtl:pl-3.5 ltr:-mr-3.5 rtl:-ml-3.5 h-full grow">
              <div class="space-y-1">
                <button
                  type="button"
                  class="w-full flex justify-between items-center p-2 hover:bg-white-dark/10 rounded-md dark:hover:text-primary hover:text-primary dark:hover:bg-[#181F32] font-medium h-10"
                  :class="{
                    'bg-gray-100 dark:text-primary text-primary dark:bg-[#181F32]':
                      selectedTab === 'all'
                  }">
                  <div class="flex items-center">
                    <icon-notes-edit class="shrink-0" />

                    <div class="ltr:ml-3 rtl:mr-3">All teams</div>
                  </div>
                </button>
                <button
                  type="button"
                  class="w-full flex justify-between items-center p-2 hover:bg-white-dark/10 rounded-md dark:hover:text-primary hover:text-primary dark:hover:bg-[#181F32] font-medium h-10"
                  :class="{
                    'bg-gray-100 dark:text-primary text-primary dark:bg-[#181F32]':
                      selectedTab === 'fav'
                  }">
                  <div class="flex items-center">
                    <icon-star class="shrink-0" />
                    <div class="ltr:ml-3 rtl:mr-3">Your teams</div>
                  </div>
                </button>
                <button
                  type="button"
                  class="w-full flex justify-between items-center p-2 hover:bg-white-dark/10 rounded-md dark:hover:text-primary hover:text-primary dark:hover:bg-[#181F32] font-medium h-10"
                  :class="{
                    'bg-gray-100 dark:text-primary text-primary dark:bg-[#181F32]':
                      selectedTab === 'team'
                  }">
                  <div class="flex items-center">
                    <icon-star class="shrink-0" />
                    <div class="ltr:ml-3 rtl:mr-3">Team you are in</div>
                  </div>
                </button>
              </div>
            </perfect-scrollbar>
          </div>
          <div class="ltr:left-0 rtl:right-0 absolute bottom-0 p-4 w-full">
            <button class="btn btn-primary w-full" type="button">
              <icon-plus class="w-5 h-5 ltr:mr-2 rtl:ml-2 shrink-0" />
              Add New Project
            </button>
          </div>
        </div>

        <div class="panel flex-1 overflow-auto h-full">
          <div class="pb-5">
            <button
              type="button"
              class="xl:hidden hover:text-primary"
              @click="isShowNoteMenu = !isShowNoteMenu">
              <icon-menu />
            </button>
          </div>
          <!-- <template v-if="!projects.length">
            <div
              class="flex justify-center items-center sm:min-h-[300px] min-h-[400px] font-semibold text-lg h-full">
              No data available
            </div>
          </template> -->
          <template v-if="!filteredNotesList.length">
            <div
              class="flex justify-center items-center sm:min-h-[300px] min-h-[400px] font-semibold text-lg h-full">
              No data available
            </div>
          </template>
          <template v-if="filteredNotesList.length">
            <div class="sm:min-h-[300px] min-h-[400px]">
              <div class="grid 2xl:grid-cols-4 lg:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-5">
                <template v-for="note in filteredNotesList" :key="note.id">
                  <div
                    class="panel pb-12"
                    :class="{
                      'bg-primary-light shadow-primary': note.tag === 'personal',
                      'bg-warning-light shadow-warning': note.tag === 'work',
                      'bg-info-light shadow-info': note.tag === 'social',
                      'bg-danger-light shadow-danger': note.tag === 'important'
                    }">
                    <div class="min-h-[142px]">
                      <div class="flex justify-between">
                        <div class="flex items-center w-max">
                          <div class="flex-none">
                            <div
                              v-if="note.thumb"
                              class="p-0.5 bg-gray-300 dark:bg-gray-700 rounded-full">
                              <img
                                class="h-8 w-8 rounded-full object-cover"
                                :src="`/assets/images/${note.thumb}`" />
                            </div>
                            <!-- avatar of creator -->
                            <div
                              v-if="!note.thumb && note.user"
                              class="grid place-content-center h-8 w-8 rounded-full bg-gray-300 dark:bg-gray-700 text-sm font-semibold">
                              {{
                                note.user.charAt(0) +
                                '' +
                                note.user.charAt(note.user.indexOf('') + 1)
                              }}
                            </div>
                            <div
                              v-if="!note.thumb && !note.user"
                              class="bg-gray-300 dark:bg-gray-700 rounded-full grid place-content-center h-8 w-8">
                              <icon-user class="w-4.5 h-4.5" />
                            </div>
                          </div>
                          <div class="ltr:ml-2 rtl:mr-2">
                            <div class="font-semibold">{{ note.user }}</div>
                            <div class="text-sx text-white-dark">{{ note.date }}</div>
                          </div>
                        </div>
                      </div>
                      <div>
                        <h4 class="font-semibold mt-4">{{ note.title }}</h4>
                        <p class="text-white-dark mt-2">{{ note.description }}</p>
                      </div>
                    </div>
                    <div class="absolute bottom-5 left-0 w-full px-5">
                      <div class="flex items-center justify-between mt-2">
                        <div class="flex items-center">
                          <button type="button" class="text-danger">
                            <icon-trash-lines />
                          </button>
                          <button type="button" class="text-warning group ltr:ml-2 rtl:mr-2">
                            <icon-star
                              class="w-4.5 h-4.5 group-hover:fill-warning"
                              :class="{ 'fill-warning': note.isFav }" />
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </template>
              </div>
            </div>
          </template>

          <TransitionRoot appear :show="isAddNoteModal" as="template">
            <Dialog as="div" @close="isAddNoteModal = false" class="relative z-[51]">
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
                        @click="isAddNoteModal = false">
                        <icon-x />
                      </button>
                      <div
                        class="text-lg font-medium bg-[#fbfbfb] dark:bg-[#121c2c] ltr:pl-5 rtl:pr-5 py-3 ltr:pr-[50px] rtl:pl-[50px]">
                        {{ params.id ? 'Edit Note' : 'Add Note' }}
                      </div>
                      <div class="p-5">
                        <form>
                          <div class="mb-5">
                            <label for="title">Title</label>
                            <input
                              id="title"
                              type="text"
                              placeholder="Enter Title"
                              class="form-input"
                              v-model="params.title" />
                          </div>
                          <div class="mb-5">
                            <label for="name">User Name</label>
                            <select id="name" class="form-select" v-model="params.user">
                              <option value="">Select User</option>
                              <option value="Max Smith">Max Smith</option>
                              <option value="John Doe">John Doe</option>
                              <option value="Kia Jain">Kia Jain</option>
                              <option value="Karena Courtliff">Karena Courtliff</option>
                              <option value="Vladamir Koschek">Vladamir Koschek</option>
                              <option value="Robert Garcia">Robert Garcia</option>
                              <option value="Marie Hamilton">Marie Hamilton</option>
                              <option value="Megan Meyers">Megan Meyers</option>
                              <option value="Angela Hull">Angela Hull</option>
                              <option value="Karen Wolf">Karen Wolf</option>
                              <option value="Jasmine Barnes">Jasmine Barnes</option>
                              <option value="Thomas Cox">Thomas Cox</option>
                              <option value="Marcus Jones">Marcus Jones</option>
                              <option value="Matthew Gray">Matthew Gray</option>
                              <option value="Chad Davis">Chad Davis</option>
                              <option value="Linda Drake">Linda Drake</option>
                              <option value="Kathleen Flores">Kathleen Flores</option>
                            </select>
                          </div>
                          <div class="mb-5">
                            <label for="tag">Tag</label>
                            <select id="tag" class="form-select" v-model="params.tag">
                              <option value="">None</option>
                              <option value="personal">Personal</option>
                              <option value="work">Work</option>
                              <option value="social">Social</option>
                              <option value="important">Important</option>
                            </select>
                          </div>
                          <div class="mb-5">
                            <label for="desc">Description</label>
                            <textarea
                              id="desc"
                              rows="3"
                              class="form-textarea resize-none min-h-[130px]"
                              placeholder="Enter Description"
                              v-model="params.description"></textarea>
                          </div>
                          <div class="flex justify-end items-center mt-8">
                            <button
                              type="button"
                              class="btn btn-outline-danger gap-2"
                              @click="isAddNoteModal = false">
                              Cancel
                            </button>
                            <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">
                              {{ params.id ? 'Update Note' : 'Add Note' }}
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

          <TransitionRoot appear :show="isDeleteNoteModal" as="template">
            <Dialog as="div" @close="isDeleteNoteModal = false" class="relative z-[51]">
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
                        @click="isDeleteNoteModal = false">
                        <icon-x />
                      </button>
                      <div
                        class="text-lg font-medium bg-[#fbfbfb] dark:bg-[#121c2c] ltr:pl-5 rtl:pr-5 py-3 ltr:pr-[50px] rtl:pl-[50px]">
                        Delete Notes
                      </div>
                      <div class="p-5 text-center">
                        <div
                          class="text-white bg-danger ring-4 ring-danger/30 p-4 rounded-full w-fit mx-auto">
                          <icon-trash-lines class="w-7 h-7 mx-auto" />
                        </div>
                        <div class="sm:w-3/4 mx-auto mt-5">
                          Are you sure you want to delete Notes?
                        </div>

                        <div class="flex justify-center items-center mt-8">
                          <button
                            type="button"
                            class="btn btn-outline-danger"
                            @click="isDeleteNoteModal = false">
                            Cancel
                          </button>
                          <button type="button" class="btn btn-primary ltr:ml-4 rtl:mr-4">
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

          <TransitionRoot appear :show="isViewNoteModal" as="template">
            <Dialog as="div" @close="isViewNoteModal = false" class="relative z-[51]">
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
                        @click="isViewNoteModal = false">
                        <icon-x />
                      </button>
                      <div
                        class="flex items-center flex-wrap gap-2 text-lg font-medium bg-[#fbfbfb] dark:bg-[#121c2c] ltr:pl-5 rtl:pr-5 py-3 ltr:pr-[50px] rtl:pl-[50px]">
                        <div class="ltr:mr-3 rtl:ml-3">{{ selectedNote.title }}</div>
                        <button
                          v-show="selectedNote.tag"
                          type="button"
                          class="badge badge-outline-primary rounded-3xl capitalize ltr:mr-3 rtl:ml-3"
                          :class="{
                            'shadow-primary': selectedNote.tag === 'personal',
                            'shadow-warning': selectedNote.tag === 'work',
                            'shadow-info': selectedNote.tag === 'social',
                            'shadow-danger': selectedNote.tag === 'important'
                          }">
                          {{ selectedNote.tag }}
                        </button>
                        <button v-show="selectedNote.isFav" type="button" class="text-warning">
                          <icon-star class="fill-warning" />
                        </button>
                      </div>
                      <div class="p-5">
                        <div class="text-base">{{ selectedNote.description }}</div>

                        <div class="ltr:text-right rtl:text-left mt-8">
                          <button
                            type="button"
                            class="btn btn-outline-danger"
                            @click="isViewNoteModal = false">
                            Close
                          </button>
                        </div>
                      </div>
                    </DialogPanel>
                  </TransitionChild>
                </div>
              </div>
            </Dialog>
          </TransitionRoot>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogOverlay
} from '@headlessui/vue';

import IconNotes from '@/Components/icon/icon-notes.vue';
import IconNotesEdit from '@/Components/icon/icon-notes-edit.vue';
import IconStar from '@/Components/icon/icon-star.vue';
import IconPlus from '@/Components/icon/icon-plus.vue';
import IconMenu from '@/Components/icon/icon-menu.vue';
import IconUser from '@/Components/icon/icon-user.vue';
import IconTrashLines from '@/Components/icon/icon-trash-lines.vue';
import IconX from '@/Components/icon/icon-x.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const defaultParams = ref({
  id: null,
  title: '',
  description: '',
  tag: '',
  user: '',
  thumb: ''
});
const isAddNoteModal = ref(false);
const isDeleteNoteModal = ref(false);
const isViewNoteModal = ref(false);
const params = ref(JSON.parse(JSON.stringify(defaultParams.value)));
const isShowNoteMenu = ref(false);
const notesList = ref([
  {
    id: 1,
    user: 'Max Smith',
    thumb: 'profile-16.jpeg',
    title: 'Meeting with Kelly',
    description: 'Curabitur facilisis vel elit sed dapibus sodales purus rhoncus.',
    date: '11/01/2020',
    isFav: false,
    tag: 'personal'
  }
]);
const filteredNotesList: any = ref('');
const selectedTab: any = ref('all');
const selectedNote: any = ref({
  id: null,
  title: '',
  description: '',
  tag: '',
  user: '',
  thumb: ''
});

onMounted(() => {
  searchNotes();
});

const searchNotes = () => {
  if (selectedTab.value != 'fav') {
    if (selectedTab.value != 'all' || selectedTab.value === 'delete') {
      filteredNotesList.value = notesList.value.filter((d) => d.tag === selectedTab.value);
    } else {
      filteredNotesList.value = notesList.value;
    }
  } else {
    filteredNotesList.value = notesList.value.filter((d) => d.isFav);
  }
};
</script>
