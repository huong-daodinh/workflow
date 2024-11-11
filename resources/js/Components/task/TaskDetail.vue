<template>
  <div class="bg-white dark:border-[#1b2e4b] dark:bg-[#191e3a] dark:shadow-none">
    <div class="grid grid-cols-3 gap-1 h-[calc(80vh-80px)] overflow-y-auto">
      <div class="col-span-2">
        <div class="detail-box relative">
          <div class="">
            <div class="p-0 w-full text-black dark:text-white-dark">
              <button
                type="button"
                class="absolute top-4 ltr:right-4 rtl:left-4 text-gray-400 hover:text-gray-800 dark:hover:text-gray-600 outline-none"
                @click="$emit('close')">
                <icon-x />
              </button>
            </div>
            <div class="text-lg font-medium dark:bg-[#121c2c] py-3 pr-[50px]">
              {{ taskData.title }}
            </div>
            <div>
              <p class="text-[#888ea8] mb-5">
                <span>
                  {{ taskData.status }}
                </span>
                in project
              </p>
              <div class="flex mb-5 gap-x-8">
                <flat-pickr
                  class="form-input w-1/3 pl-5 py-1"
                  :config="datepickerOptions"></flat-pickr>
                <div class="col-span-1 text-sm flex items-center gap-x-2 font-bold">
                  <icon-clock />
                  {{ taskData.due_date }}
                </div>
              </div>
            </div>
            <div class="mb-5 flex gap-x-10">
              <div>
                <button type="button" v-tippy:hover class="btn btn-primary">Mark complete</button>
                <tippy target="hover" trigger="mouseenter">Click to mark this task completed</tippy>
              </div>
              <div>
                <img src="" alt="avatar" class="inline-block h-10 w-10 rounded-full" />
                <span class="ml-2">nguoi giao viec</span>
              </div>
            </div>
            <div class="mb-5">
              <div class="flex justify-between">
                <label for="" class="uppercase text-primary">Mo ta cong viec</label>
                <button
                  v-if="!isEditDescription"
                  class="p-1 text-blue-500 hover:underline"
                  @click="isEditDescription = true">
                  Chinh sua
                </button>
              </div>
              <div>
                <div v-if="isEditDescription">
                  <ckeditor
                    :config="options.editorConfig"
                    :editor="options.editor"
                    v-model="taskData.description" />
                  <div class="flex justify-end mt-1 gap-x-5">
                    <button
                      type="button"
                      class="btn btn-primary"
                      @click="isEditDescription = false">
                      Save
                    </button>
                    <button
                      type="button"
                      class="btn btn-outline-danger"
                      @click="isEditDescription = false">
                      Cancel
                    </button>
                  </div>
                </div>
                <div class="pl-5" v-else>
                  <div v-html="taskData.description" />
                </div>
              </div>
            </div>
            <div class="mb-5">
              <div class="flex justify-between">
                <label for="" class="uppercase text-primary">Tai lieu dinh kem</label>
                <button class="p-1 text-blue-500 hover:underline">Them tai lieu</button>
              </div>
            </div>
            <div class="mb-5">
              <label for="" class="uppercase text-dark text-xl">Ket qua cong viec</label>
              <button
                class="btn btn-outline-secondary"
                @click="updateTaskResult = true"
                v-if="!updateTaskResult">
                Cap nhat ket qua cong viec
              </button>
              <div v-if="updateTaskResult">
                <ckeditor :config="options.editorConfig" :editor="options.editor" />
                <div class="flex justify-end mt-1 gap-x-3">
                  <button type="button" class="btn btn-primary" @click="updateTaskResult = false">
                    Save
                  </button>
                  <button
                    type="button"
                    class="btn btn-outline-danger"
                    @click="updateTaskResult = false">
                    Cancel
                  </button>
                </div>
              </div>
            </div>
            <hr />
            <div class="mb-5">
              <label for="" class="uppercase text-dark text-xl mb-5">Cong viec con</label>
              <div v-if="taskData.sub_tasks">
                <div
                  v-for="(sub_task, index) in taskData.sub_tasks"
                  :key="index"
                  class="flex items-center p-3 mb-3 rounded border"
                  :class="{
                    'text-success bg-success-light border-success': sub_task.status === 'done',
                    ' text-secondary bg-secondary-light border-secondary':
                      sub_task.status !== 'done'
                  }">
                  <span class="ltr:pr-2 rtl:pl-2">
                    <strong class="ltr:mr-2 rtl:ml-2">{{ sub_task.status }}</strong
                    >Lorem Ipsum is simply dummy text of the printing.
                  </span>
                </div>
              </div>
              <button class="btn btn-outline-success" @click="isAddSubtask = true">
                + Them cong viec con
              </button>
            </div>
            <hr />
            <div class="mb-5">
              <!-- show messages -->
              <task-message
                :taskId="taskData.id"
                v-if="taskData.id"
                @message-sent="scrollToBottom()" />
            </div>
          </div>
        </div>
      </div>
      <div class="col-span-1">
        <task-sub-detail />
      </div>
      <!-- Modal -->
      <TransitionRoot appear :show="isAddSubtask" as="template">
        <Dialog as="div" @close="isAddSubtask = false" class="relative z-50">
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
                  class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-xl text-black dark:text-white-dark">
                  <button
                    type="button"
                    class="absolute top-5 ltr:right-5 rtl:left-5 text-white-dark hover:text-dark outline-none"
                    @click="isAddSubtask = false">
                    <icon-x />
                  </button>
                  <div
                    class="text-lg font-semibold ltr:pl-5 rtl:pr-5 py-5 ltr:pr-[50px] rtl:pl-[50px]">
                    Them cong viec con
                  </div>
                  <div class="p-5">
                    <form class="space-y-5">
                      <div class="mb-4">
                        <label for="">Ten cong viec</label>
                        <div class="relative">
                          <span
                            class="absolute ltr:left-3 rtl:right-3 top-1/2 -translate-y-1/2 dark:text-white-dark">
                            <icon-pencil-paper />
                          </span>
                          <input
                            type="text"
                            placeholder="Title"
                            class="form-input ltr:pl-10 rtl:pr-10" />
                        </div>
                      </div>
                      <div class="mb-4">
                        <label for="">Giao cho</label>
                        <div class="relative">
                          <span
                            class="absolute ltr:left-3 rtl:right-3 top-1/2 -translate-y-1/2 dark:text-white-dark">
                            <icon-users />
                          </span>
                          <input
                            type="text"
                            placeholder="Assign to"
                            class="form-input ltr:pl-10 rtl:pr-10" />
                        </div>
                      </div>
                      <div class="mb-4">
                        <label for="">Mo ta cong viec</label>
                        <textarea class="form-input" placeholder="..."></textarea>
                      </div>
                      <div class="mb-4">
                        <label for="">Thoi han</label>
                        <flat-pickr class="form-input" :config="datepickerOptions" />
                      </div>
                      <div class="flex gap-x-4">
                        <button
                          type="button"
                          class="btn btn-outline-danger w-full"
                          @click="isAddSubtask = false">
                          Cancel
                        </button>
                        <button
                          type="button"
                          class="btn btn-primary w-full"
                          @click="isAddSubtask = false">
                          Create
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
  </div>
</template>

<script lang="ts" setup>
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogOverlay
} from '@headlessui/vue';
import { onMounted, ref, defineProps } from 'vue';
import FlatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import TaskSubDetail from './TaskSubDetail.vue';
import IconX from '@/Components/icon/icon-x.vue';
import IconPencilPaper from '../icon/icon-pencil-paper.vue';
import IconUsers from '../icon/icon-users.vue';
import IconClock from '../icon/icon-clock.vue';
import TaskMessage from './TaskMessage.vue';
import { getTaskDetail } from '@/services/task.service';
import {
  ClassicEditor,
  AccessibilityHelp,
  Autoformat,
  AutoImage,
  AutoLink,
  Autosave,
  BalloonToolbar,
  BlockQuote,
  Bold,
  CloudServices,
  Code,
  CodeBlock,
  Essentials,
  FindAndReplace,
  Heading,
  Highlight,
  HorizontalLine,
  HtmlEmbed,
  ImageBlock,
  ImageCaption,
  ImageInline,
  ImageInsert,
  ImageInsertViaUrl,
  ImageResize,
  ImageStyle,
  ImageTextAlternative,
  ImageUpload,
  Indent,
  IndentBlock,
  Italic,
  Link,
  LinkImage,
  List,
  ListProperties,
  MediaEmbed,
  Mention,
  Paragraph,
  PasteFromOffice,
  PictureEditing,
  SelectAll,
  SpecialCharacters,
  SpecialCharactersArrows,
  SpecialCharactersCurrency,
  SpecialCharactersEssentials,
  SpecialCharactersLatin,
  SpecialCharactersMathematical,
  SpecialCharactersText,
  Strikethrough,
  Table,
  TableCellProperties,
  TableProperties,
  TableToolbar,
  TextTransformation,
  TodoList,
  Underline,
  Undo,
  CKFinderUploadAdapter
} from 'ckeditor5';
import 'ckeditor5/ckeditor5.css';
import './../../../css/ckeditor.css';
import type { subTask, task, user } from '@/interfaces/index.interfaces';

const options = ref({
  editor: ClassicEditor,
  editorConfig: {
    plugins: [
      AccessibilityHelp,
      Autoformat,
      AutoImage,
      AutoLink,
      Autosave,
      BalloonToolbar,
      BlockQuote,
      Bold,
      CloudServices,
      Code,
      CodeBlock,
      Essentials,
      FindAndReplace,
      Heading,
      Highlight,
      HorizontalLine,
      HtmlEmbed,
      ImageBlock,
      ImageCaption,
      ImageInline,
      ImageInsert,
      ImageInsertViaUrl,
      ImageResize,
      ImageStyle,
      ImageTextAlternative,
      ImageUpload,
      Indent,
      IndentBlock,
      Italic,
      Link,
      LinkImage,
      List,
      ListProperties,
      MediaEmbed,
      Mention,
      Paragraph,
      PasteFromOffice,
      PictureEditing,
      SelectAll,
      SpecialCharacters,
      SpecialCharactersArrows,
      SpecialCharactersCurrency,
      SpecialCharactersEssentials,
      SpecialCharactersLatin,
      SpecialCharactersMathematical,
      SpecialCharactersText,
      Strikethrough,
      Table,
      TableCellProperties,
      TableProperties,
      TableToolbar,
      TextTransformation,
      TodoList,
      Underline,
      Undo,
      CKFinderUploadAdapter
    ],
    toolbar: [
      'undo',
      'redo',
      '|',
      'bold',
      'italic',
      'underline',
      '|',
      'link',
      'highlight',
      'blockQuote',
      'codeBlock',
      '|',
      'numberedList',
      'todoList',
      'outdent',
      'indent'
    ],
    height: 300
  }
});

const props = defineProps({
  taskId: {
    type: Number,
    required: true
  }
});
// const taskData = ref<any>();
const taskData = ref<task>({
  id: 0,
  title: '',
  description: '',
  due_date: '',
  priority: 0,
  status: '',
  assignee: {} as user,
  assigner: {} as user,
  created_by: {} as user,
  created_at: '',
  updated_at: '',
  sub_tasks: [] as subTask[],
  tags: []
});

const scrollToBottom = () => {
  setTimeout(() => {
    const element: any = document.querySelector('.detail-box');
    element.behavior = 'smooth';
    element.scrollTop = element.scrollHeight;
  });
};

const datepickerOptions = ref<any>({
  dateFormat: 'Y-m-d',
  position: 'auto left'
});

const isEditDescription = ref(false);
const updateTaskResult = ref(false);
const isAddSubtask = ref(false);

onMounted(async () => {
  await getTaskDetail(props.taskId)
    .then((response) => {
      taskData.value = response.data;
    })
    .catch((error) => {
      console.log(error);
    });
});
</script>
