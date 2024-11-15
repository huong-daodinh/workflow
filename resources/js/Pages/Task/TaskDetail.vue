<template>
  <div v-if="taskData" class="bg-white dark:border-[#1b2e4b] dark:bg-[#191e3a] dark:shadow-none">
    <div class="grid grid-cols-3 gap-1">
      <div class="col-span-2">
        <div class="relative">
          <div class="">
            <div class="p-0 w-full text-black dark:text-white-dark">
              <button
                type="button"
                class="absolute top-4 ltr:right-4 rtl:left-4 text-gray-400 hover:text-gray-800 dark:hover:text-gray-600 outline-none"
                @click="emits('close')">
                <icon-x />
              </button>
            </div>
            <div class="text-xl font-medium dark:bg-[#121c2c] py-1 pr-[50px]">
              {{ taskData.title }}
            </div>
            <div>
              <p class="text-[#888ea8] mb-5">
                <span>
                  {{ taskData.status }}
                </span>
                {{ $t('in_project') }}
              </p>
              <div class="flex mb-5 gap-x-8">
                <div v-if="taskData.started_at">{{ taskData.started_at }}</div>
                <flat-pickr
                  v-else
                  @on-change="onStartTask($event)"
                  class="form-input w-1/3 pl-5 py-1"
                  :config="datepickerOptions" />
                <div class="col-span-1 text-sm flex items-center gap-x-2 font-bold">
                  <icon-clock />
                  {{ taskData.due_date }}
                </div>
              </div>
            </div>
            <div class="mb-5 flex gap-x-10">
              <div>
                <button type="button" v-tippy:hover class="btn btn-primary" @click="onMarkAsDone">
                  {{ $t('mark_as_done') }}
                </button>
                <tippy target="hover" trigger="mouseenter">{{ $t('click_to_mark_done') }}</tippy>
              </div>
              <div class="flex-1">
                <div>{{ $t('assigner') }}:</div>
                <user-info :userData="taskData.assigner" />
              </div>
            </div>
            <div class="mb-5">
              <div class="flex justify-between">
                <label for="" class="uppercase text-primary">{{ $t('task_description') }}</label>
                <button
                  v-if="!isEditDescription"
                  class="p-1 text-blue-500 hover:underline"
                  @click="isEditDescription = true">
                  {{ $t('edit') }}
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
                      {{ $t('save') }}
                    </button>
                    <button
                      type="button"
                      class="btn btn-outline-danger"
                      @click="isEditDescription = false">
                      {{ $t('cancel') }}
                    </button>
                  </div>
                </div>
                <div class="pl-5" v-else>
                  <div v-html="taskData.description" />
                </div>
              </div>
            </div>
            <div class="mb-5">
              <label for="" class="uppercase text-primary">{{ $t('attachments') }}</label>
              <div>
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
                    <div class="w-full h-1 bg-[#ebedf2] dark:bg-dark/40 rounded-full flex">
                      <div
                        class="bg-success h-1 rounded-full rounded-bl-full text-center text-white text-xs"
                        :style="{
                          width: attachmentForm.progress.percentage + '%'
                        }"></div>
                    </div>
                  </div>
                  <input-error :message="attachmentForm.errors.file" />
                </form>
                <div class="grid lg:grid-cols-2 grid-cols-1 gap-x-5">
                  <div
                    v-for="(file, index) in uploadedFiles"
                    :key="index"
                    class="flex justify-between col-span-1 mt-2 p-1 border-b-2 hover:border-primary">
                    <a
                      :href="route('task-attachment.download', { id: file.id })"
                      class="text-sm italic block h-fit underline text-primary hover:font-bold">
                      <file-icon :extension="file.name.split('.').pop()" class="ml-1" />
                      {{ file.name }}
                    </a>
                    <span
                      class="text-sm p-1 cursor-pointer hover:text-primary block"
                      @click="removeAttachment(file.id)">
                      <icon-x />
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <div class="mb-5">
              <label class="uppercase text-dark text-xl">{{ $t('task_result') }}</label>
              <button
                class="btn btn-outline-secondary"
                @click="updateTaskResult = true"
                v-if="!updateTaskResult">
                {{ $t('update_task_result') }}
              </button>
              <div v-if="updateTaskResult">
                <ckeditor
                  :config="options.editorConfig"
                  v-model="taskData.result"
                  :editor="options.editor" />
                <div class="flex justify-end mt-1 gap-x-3">
                  <button type="button" class="btn btn-primary" @click="onUpdateResult">
                    {{ $t('save') }}
                  </button>
                  <button
                    type="button"
                    class="btn btn-outline-danger"
                    @click="onCancelUpdatingResult">
                    {{ $t('cancel') }}
                  </button>
                </div>
              </div>
              <div class="pl-5" v-else>
                <div v-html="taskData.result" />
              </div>
            </div>
            <hr />
            <div class="mb-5">
              <label for="" class="uppercase text-dark text-xl mb-5">{{ $t('sub_tasks') }}</label>
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
                    <strong class="ltr:mr-2 rtl:ml-2">{{ sub_task.status }}</strong>
                    Lorem Ipsum is simply dummy text of the printing.
                  </span>
                </div>
              </div>
              <button class="btn btn-outline-success" @click="isAddSubtask = true">
                {{ $t('add_subtask') }}
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
</template>

<script lang="ts" setup>
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogOverlay
} from '@headlessui/vue';
import { onMounted, ref, defineProps, defineEmits } from 'vue';
import FlatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import TaskSubDetail from './TaskSubDetail.vue';
import IconX from '@/Components/icon/icon-x.vue';
import IconPencilPaper from '@/Components/icon/icon-pencil-paper.vue';
import IconUsers from '@/Components/icon/icon-users.vue';
import IconClock from '@/Components/icon/icon-clock.vue';
import TaskMessage from './TaskMessage.vue';
import { getTaskDetail, markAsDone, createTaskResult } from '@/services/task.service';
import UserInfo from '@/Components/UserInfo.vue';
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
import '@css/ckeditor.css';
import type { task } from '@/interfaces/index.interfaces';
import { showMessage } from '@/functions/alert';
import { useForm, usePage } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import FileIcon from '@/Components/FileIcon.vue';

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
    ]
  }
});

const taskData = ref<task>();
const resultDraft = ref('');
const emits = defineEmits(['close', 'markAsDone', 'scrollToBottom']);
const props = defineProps({
  taskId: {
    type: Number,
    required: true
  }
});

const attachmentForm = useForm({
  file: {} as any,
  task_id: props.taskId
});

const scrollToBottom = () => {
  emits('scrollToBottom');
};

const datepickerOptions = ref<any>({
  dateFormat: 'Y-m-d',
  position: 'auto left'
});

const page = usePage();
const isEditDescription = ref(false);
const updateTaskResult = ref(false);
const isAddSubtask = ref(false);
const uploadedFiles = ref<Array<{ id: number; name: string }>>([]);

const onStartTask = (data: any) => {
  console.log('start task', data);
};

const onMarkAsDone = async () => {
  await markAsDone(props.taskId).then((response) => {
    showMessage(response.data.message, response.data.type);
  });
  emits('markAsDone', props.taskId);
};

const onUpdateResult = async () => {
  await createTaskResult(props.taskId, taskData.value.result).then((response) => {
    resultDraft.value = taskData.value.result;
    showMessage(response.data.message, response.data.type);
  });
  updateTaskResult.value = false;
};

const onCancelUpdatingResult = () => {
  taskData.value.result = resultDraft.value;
  updateTaskResult.value = false;
};

const uploadAttachment = () => {
  attachmentForm.post(route('task-attachment.upload'), {
    onSuccess: () => {
      const file = page.props.flash_data.file;
      uploadedFiles.value.push({
        id: file.id,
        name: file.slug
      });
      console.log('uploaded', file);
    }
  });
};

const removeAttachment = (fileId: number) => {
  attachmentForm.file = {};
  uploadedFiles.value = uploadedFiles.value.filter((file) => file.id !== fileId);
};

onMounted(async () => {
  await getTaskDetail(props.taskId)
    .then((response) => {
      taskData.value = response.data;
      resultDraft.value = taskData.value.result;
      taskData.value.attachments.forEach((file: any) => {
        uploadedFiles.value.push({
          id: file.id,
          name: file.slug
        });
      });
    })
    .catch(() => {
      console.log('error');
    });
});
</script>
