<template>
  <AuthenticatedLayout>
    <Head title="Project" />
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
              <h3 class="text-lg font-semibold ltr:ml-3 rtl:mr-3">Dự án</h3>
            </div>
            <div class="h-px w-full border-b border-[#e0e6ed] dark:border-[#1b2e4b] my-4"></div>
            <div
              class="relative overflow-y-auto ltr:pr-3.5 rtl:pl-3.5 ltr:-mr-3.5 rtl:-ml-3.5 h-full grow">
              <div class="space-y-1">
                <button
                  type="button"
                  class="w-full flex justify-between items-center p-2 hover:bg-white-dark/10 rounded-md dark:hover:text-primary hover:text-primary dark:hover:bg-[#181F32] font-medium h-10"
                  :class="{
                    'bg-gray-100 dark:text-primary text-primary dark:bg-[#181F32]':
                      selectedTab === 'all'
                  }"
                  @click="tabChanged('all')">
                  <div class="flex items-center">
                    <icon-notes-edit class="shrink-0" />

                    <div class="ltr:ml-3 rtl:mr-3">Tất cả</div>
                  </div>
                </button>
                <!-- <button
                  type="button"
                  class="w-full flex justify-between items-center p-2 hover:bg-white-dark/10 rounded-md dark:hover:text-primary hover:text-primary dark:hover:bg-[#181F32] font-medium h-10"
                  :class="{
                    'bg-gray-100 dark:text-primary text-primary dark:bg-[#181F32]':
                      selectedTab === 'fav'
                  }"
                  @click="tabChanged('fav')">
                  <div class="flex items-center">
                    <icon-star class="shrink-0" />
                    <div class="ltr:ml-3 rtl:mr-3">Personal Projects</div>
                  </div>
                </button>
                <button
                  type="button"
                  class="w-full flex justify-between items-center p-2 hover:bg-white-dark/10 rounded-md dark:hover:text-primary hover:text-primary dark:hover:bg-[#181F32] font-medium h-10"
                  :class="{
                    'bg-gray-100 dark:text-primary text-primary dark:bg-[#181F32]':
                      selectedTab === 'team'
                  }"
                  @click="tabChanged('team')">
                  <div class="flex items-center">
                    <icon-star class="shrink-0" />
                    <div class="ltr:ml-3 rtl:mr-3">Team Projects</div>
                  </div>
                </button> -->
              </div>
            </div>
          </div>
          <div class="ltr:left-0 rtl:right-0 absolute bottom-0 p-4 w-full">
            <button class="btn btn-primary w-full" type="button" @click="addEditProject()">
              <icon-plus class="w-5 h-5 ltr:mr-2 rtl:ml-2 shrink-0" />
              Thêm dự án mới
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
          <template v-if="!projects.length">
            <div
              class="flex justify-center items-center sm:min-h-[500px] min-h-[600px] font-semibold text-lg h-full">
              No data available
            </div>
          </template>
          <template v-if="projects.length">
            <div class="sm:min-h-[300px] min-h-[400px]">
              <div class="grid 2xl:grid-cols-4 lg:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-5">
                <div v-for="project in projects" :key="project.id">
                  <div class="panel pb-12 bg-secondary-light shadow-secondary">
                    <div class="h-[160px]">
                      <div class="flex justify-between">
                        <div class="flex items-center w-max">
                          <div class="flex-none">
                            <div
                              v-if="project.created_by.avatar"
                              class="p-0.5 bg-gray-300 dark:bg-gray-700 rounded-full">
                              <img
                                class="h-8 w-8 rounded-full object-cover"
                                :src="project.created_by.avatar" />
                            </div>
                            <div
                              v-if="!project.created_by.avatar"
                              class="bg-gray-300 dark:bg-gray-700 rounded-full grid place-content-center h-8 w-8">
                              <icon-user class="w-4.5 h-4.5" />
                            </div>
                          </div>
                          <div class="ltr:ml-2 rtl:mr-2">
                            <div class="font-semibold">{{ project.created_by.name }}</div>
                            <div class="text-sx text-white-dark">
                              {{ new Date(project.created_at).toLocaleString() }}
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="mt-2">
                        <Link
                          class="font-semibold mt-4 overflow-hidden"
                          :href="route('project.show', { id: project.id })">
                          {{ project.title }}
                        </Link>
                      </div>
                    </div>
                    <div class="absolute bottom-5 left-0 w-full px-5">
                      <div class="flex items-center justify-between mt-2">
                        <div class="flex items-center">
                          <button type="button" class="text-danger" @click="deleteProject(project)">
                            <icon-trash-lines />
                          </button>
                          <button
                            type="button"
                            class="text-warning group ltr:ml-2 rtl:mr-2"
                            @click="addEditProject(project)">
                            <icon-edit class="w-4.5 h-4.5 group-hover:fill-warning" />
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>
    </div>
    <!-- project modal -->
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
                class="panel border-0 p-0 rounded-lg w-full max-w-3xl text-black dark:text-white-dark">
                <button
                  type="button"
                  class="absolute top-4 ltr:right-4 rtl:left-4 text-gray-400 hover:text-gray-800 dark:hover:text-gray-600 outline-none"
                  @click="clearForm">
                  <icon-x />
                </button>
                <div
                  class="text-lg font-medium bg-[#fbfbfb] dark:bg-[#121c2c] ltr:pl-5 rtl:pr-5 py-3 ltr:pr-[50px] rtl:pl-[50px]">
                  {{ form.id ? 'Cập nhật dự án' : 'Thêm dự án' }}
                </div>
                <div class="p-5">
                  <form @submit.prevent="saveProject">
                    <div class="grid gap-5">
                      <div>
                        <div>
                          <label for="title">Title</label>
                          <input
                            id="title"
                            v-model="form.title"
                            type="text"
                            class="form-input mt-1"
                            placeholder="Enter Title" />
                        </div>
                        <input-error :message="form.errors.title" />
                      </div>
                      <div>
                        <div>
                          <label for="title">Description</label>
                          <quillEditor
                            ref="editor1"
                            v-model:value="form.description"
                            :options="editorOptions"
                            style="height: 200px" />
                        </div>
                        <input-error :message="form.errors.description" />
                      </div>
                      <div>
                        <div>
                          <label for="">Upload documents</label>
                          <form @change="uploadAttachment">
                            <div class="flex">
                              <div
                                class="bg-[#eee] gap-x-4 p-1 flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                                <icon-file />
                                Browse
                              </div>
                              <input
                                type="file"
                                ref="uploadFile"
                                @input="attachmentForm.file = $event.target?.files[0]"
                                class="form-input rounded-l-none" />
                            </div>
                            <div class="space-y-2 my-2" v-if="attachmentForm.progress">
                              <div
                                class="w-full h-1 bg-[#ebedf2] dark:bg-dark/40 rounded-full flex">
                                <div
                                  class="bg-success h-1 rounded-full rounded-bl-full text-center text-white text-xs"
                                  :style="{
                                    width: attachmentForm.progress.percentage + '%'
                                  }"></div>
                              </div>
                            </div>
                            <input-error :message="attachmentForm.errors.file" />
                            <input-error :message="form.errors.files" />
                          </form>
                          <div v-if="uploadedFiles">
                            <div
                              v-for="(file, index) in uploadedFiles"
                              :key="index"
                              class="flex justify-between mt-2 border-b-2 p-1">
                              <span class="text-sm italic block h-fit underline text-primary">
                                <file-icon :extension="file.name.split('.').pop()" />
                                {{ file.name }}
                              </span>
                              <span
                                class="text-sm p-1 cursor-pointer hover:text-primary block"
                                @click="removeAttachment(file.id)">
                                x
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="flex justify-end items-center mt-8">
                      <a class="btn btn-outline-danger cursor-pointer" @click="clearForm"> Huỷ </a>
                      <button
                        type="submit"
                        class="btn btn-primary ltr:ml-4 rtl:mr-4"
                        :disabled="form.processing">
                        <icon-loader
                          v-if="form.processing"
                          class="animate-[spin_2s_linear_infinite] inline-block align-middle ltr:mr-2 rtl:ml-2 shrink-0" />
                        {{ form.id ? 'Cập nhật' : 'Thêm' }}
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

    <!-- delete project modal -->
    <TransitionRoot appear :show="isDeleteProjectModal" as="template">
      <Dialog as="div" @close="isDeleteProjectModal = false" class="relative z-[51]">
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
                  @click="isDeleteProjectModal = false">
                  <icon-x />
                </button>
                <div
                  class="text-lg font-medium bg-[#fbfbfb] dark:bg-[#121c2c] ltr:pl-5 rtl:pr-5 py-3 ltr:pr-[50px] rtl:pl-[50px]">
                  Xoá dự án
                </div>
                <div class="p-5 text-center">
                  <div
                    class="text-white bg-danger ring-4 ring-danger/30 p-4 rounded-full w-fit mx-auto">
                    <icon-trash-lines />
                  </div>
                  <div class="text-base sm:w-3/4 mx-auto mt-5">
                    Bạn chắc chắn muốn xoá dự án này
                  </div>

                  <div class="flex justify-center items-center mt-8">
                    <button
                      type="button"
                      class="btn btn-outline-danger"
                      @click="isDeleteProjectModal = false">
                      {{ $t('cancel') }}
                    </button>
                    <Link
                      :href="route('project.destroy', form.id)"
                      method="delete"
                      as="button"
                      class="btn btn-primary ltr:ml-4 rtl:mr-4"
                      @click="closeProjectModal">
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
  </AuthenticatedLayout>
</template>
<script lang="ts" setup>
import { ref, defineProps } from 'vue';
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogOverlay
} from '@headlessui/vue';
import IconNotes from '@/Components/icon/icon-notes.vue';
import IconNotesEdit from '@/Components/icon/icon-notes-edit.vue';
import IconPlus from '@/Components/icon/icon-plus.vue';
import IconMenu from '@/Components/icon/icon-menu.vue';
import IconTrashLines from '@/Components/icon/icon-trash-lines.vue';
import IconX from '@/Components/icon/icon-x.vue';
import IconLoader from '@/Components/icon/icon-loader.vue';
import InputError from '@/Components/InputError.vue';
import IconFile from '@/Components/icon/icon-file.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { quillEditor } from 'vue3-quill';
import 'vue3-quill/lib/vue3-quill.css';
import { useForm, usePage, Link, Head } from '@inertiajs/vue3';
import type { project } from '@/interfaces/index.interfaces';
import FileIcon from '@/Components/FileIcon.vue';
import IconEdit from '@/Components/icon/icon-edit.vue';

const page = usePage();
const attachmentForm = useForm({
  file: {} as any
});

defineProps({
  projects: Array<project>
});

const isDeleteProjectModal = ref(false);
const uploadedFiles = ref<Array<{ name: string; id: number }>>([]);
const isAddProjectModal = ref(false);
const form = useForm({
  id: 0,
  title: '',
  description: '',
  files: [] as Array<number>,
  type: '',
  team_id: null,
  attachments: [] as Array<{ slug: string; id: number }>
});

const editorOptions = ref({
  modules: {
    toolbar: [
      [{ header: [1, 2, 3, false] }],
      ['bold', 'italic', 'underline', 'link'],
      [{ list: 'ordered' }, { list: 'bullet' }],
      ['clean']
    ]
  },
  placeholder: ''
});

const saveProject = () => {
  if (!form.id) {
    form.post(route('project.store'), {
      onSuccess: () => {
        clearForm();
      }
    });
  } else {
    form.patch(route('project.update', form.id), {
      onSuccess: () => {
        clearForm();
      }
    });
  }
};

const clearForm = () => {
  isAddProjectModal.value = false;
  form.reset();
  form.clearErrors();
  attachmentForm.clearErrors();
  uploadedFiles.value = [];
};

const uploadAttachment = () => {
  attachmentForm.post(route('project-attachment.upload'), {
    onSuccess: () => {
      const file = page.props.flash_data.file;
      form.files.push(file.id);
      const uploadedData = {
        name: file.slug,
        id: file.id
      };
      uploadedFiles.value.push(uploadedData);
    }
  });
};

const removeAttachment = (id: number) => {
  form.files = form.files.filter((file) => file != id);
  attachmentForm.file = {};
  uploadedFiles.value = uploadedFiles.value.filter((file) => file.id != id);
};

const isShowNoteMenu = ref(false);
const selectedTab: any = ref('all');

const addEditProject = (project: any = null) => {
  form.reset();
  if (project) {
    form.id = project.id;
    form.title = project.title;
    form.description = project.description;
    form.team_id = project.team_id;
    project.attachments.forEach((file: any) => {
      uploadedFiles.value.push({
        name: file.slug,
        id: file.id
      });
    });
  }
  isAddProjectModal.value = true;
};

const deleteProject = (project: any) => {
  form.id = project ? project.id : null;
  isDeleteProjectModal.value = true;
};

const closeProjectModal = () => {
  isAddProjectModal.value = false;
  isDeleteProjectModal.value = false;
  form.clearErrors();
};

const tabChanged = (type: string) => {
  selectedTab.value = type;
  isShowNoteMenu.value = false;
};
</script>
