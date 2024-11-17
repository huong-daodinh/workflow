<template>
  <div v-if="taskData" class="bg-white dark:border-[#1b2e4b] dark:bg-[#191e3a] dark:shadow-none">
    <div class="grid grid-cols-3 gap-1">
      <div class="col-span-2">
        <div class="relative">
          <div class="">
            <div class="p-0 w-full text-black dark:text-white-dark">
              <button
                type="button"
                class="absolute ltr:right-4 rtl:left-4 text-gray-400 hover:text-gray-800 dark:hover:text-gray-600 outline-none"
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
                <div class="text-sm font-bold" v-if="taskData.started_at">
                  {{ taskData.started_at }}
                </div>
                <div v-else>
                  <button type="button" class="btn btn-primary" @click="startTask">
                    {{ $t('start') }}
                  </button>
                </div>
                <div class="col-span-1 flex items-center gap-x-2 text-sm font-bold">
                  <icon-clock />
                  {{ taskData.due_date }}
                </div>
              </div>
            </div>
            <div class="mb-5 flex gap-x-10">
              <div>
                <button
                  type="button"
                  v-tippy:hover
                  :disabled="taskData.started_at === null || canPerform(taskData.status)"
                  class="btn btn-primary"
                  @click="onMarkAsDone">
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
                  :disabled="canPerform(taskData.status)"
                  class="btn btn-outline-secondary"
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
                      :disabled="canPerform(taskData.status)"
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
                :disabled="canPerform(taskData.status)"
                v-if="!updateTaskResult">
                {{ $t('update_task_result') }}
              </button>
              <div v-if="updateTaskResult">
                <ckeditor
                  :config="options.editorConfig"
                  v-model="taskData.result"
                  :editor="options.editor" />
                <input-error :message="taskForm.errors.result" />
                <div class="flex justify-end mt-1 gap-x-3">
                  <button type="button" class="btn btn-primary" @click="updateResult">
                    {{ $t('save') }}
                  </button>
                  <button
                    type="button"
                    class="btn btn-outline-danger"
                    @click="cancelUpdatingResult">
                    {{ $t('cancel') }}
                  </button>
                </div>
              </div>
              <div class="pl-5 mt-2" v-else>
                <div v-html="taskData.result" />
              </div>
            </div>
            <hr />
            <div class="mb-5">
              <label for="" class="uppercase text-dark text-xl mb-5">{{ $t('sub_tasks') }}</label>
              <div v-if="taskData.sub_tasks">
                <subtask
                  v-for="subTask in taskData.sub_tasks"
                  :sub_task="subTask"
                  :key="subTask.id"
                  @update-sub-task="editSubtask(subTask.id)" />
              </div>
              <button
                class="btn btn-outline-success"
                @click="addSubtask()"
                :disabled="canPerform(taskData.status)">
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
        <task-sub-detail :task="taskData" />
      </div>
    </div>
    <!-- Subtask Modal -->
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
                  @click="closeModal">
                  <icon-x />
                </button>
                <div
                  class="text-lg font-semibold ltr:pl-5 rtl:pr-5 py-5 ltr:pr-[50px] rtl:pl-[50px]">
                  {{ 'Add Subtask' }}
                </div>
                <div class="p-5">
                  <form class="space-y-5" @submit.prevent="saveSubtask">
                    <div class="mb-4">
                      <label for="">{{ $t('title') }}</label>
                      <div class="relative">
                        <span
                          class="absolute ltr:left-3 rtl:right-3 top-1/2 -translate-y-1/2 dark:text-white-dark">
                          <icon-pencil-paper />
                        </span>
                        <input
                          type="text"
                          v-model="subtaskForm.title"
                          placeholder="Title"
                          class="form-input ltr:pl-10 rtl:pr-10" />
                      </div>
                      <input-error :message="subtaskForm.errors.title" />
                    </div>
                    <div class="mb-4">
                      <label for="">{{ $t('Deadline') }}</label>
                      <flat-pickr
                        class="form-input"
                        v-model="subtaskForm.due_date"
                        :config="datepickerOptions" />
                      <input-error :message="subtaskForm.errors.due_date" />
                    </div>
                    <div class="mb-4">
                      <label for="">{{ $t('assigner') }}</label>
                      <div class="relative">
                        <span
                          class="absolute ltr:left-3 rtl:right-3 top-1/2 -translate-y-1/2 dark:text-white-dark">
                          <icon-users />
                        </span>
                        <multiselect
                          v-model="subtaskForm.assigner"
                          :options="assigners"
                          label="name"
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
                    </div>
                    <div class="mb-4">
                      <label for="">{{ $t('assignee') }}</label>
                      <div class="relative">
                        <span
                          class="absolute ltr:left-3 rtl:right-3 top-1/2 -translate-y-1/2 dark:text-white-dark">
                          <icon-users />
                        </span>
                        <multiselect
                          v-model="subtaskForm.assignee"
                          :options="assignees"
                          label="name"
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
                    </div>
                    <div class="mb-4">
                      <label for="">{{ $t('task_description') }}</label>
                      <ckeditor
                        :config="options.editorConfig"
                        v-model="subtaskForm.description"
                        :editor="options.editor" />
                      <input-error :message="subtaskForm.errors.description" />
                    </div>
                    <div class="flex gap-x-4">
                      <button
                        type="button"
                        class="btn btn-outline-danger w-full"
                        @click="closeModal">
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
    <!-- subtask detail modal -->
    <TransitionRoot appear :show="isEditSubtask" as="template">
      <Dialog as="div" @close="isEditSubtask = false" class="relative z-50">
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
                <subtask-detail :subtask_id="subtaskId" @close="closeModal" />
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </div>
  <div v-else class="absolute inset-0 flex justify-center items-center">
    <span
      class="animate-spin border-4 border-primary border-l-transparent rounded-full w-10 h-10"></span>
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
import SubtaskDetail from '../Subtask/SubtaskDetail.vue';
import IconX from '@/Components/icon/icon-x.vue';
import IconPencilPaper from '@/Components/icon/icon-pencil-paper.vue';
import IconUsers from '@/Components/icon/icon-users.vue';
import IconClock from '@/Components/icon/icon-clock.vue';
import TaskMessage from './TaskMessage.vue';
import { getTaskDetail } from '@/services/task.service';
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
  CKFinderUploadAdapter,
  logError
} from 'ckeditor5';
import 'ckeditor5/ckeditor5.css';
import '@css/ckeditor.css';
import type { task, user } from '@/interfaces/index.interfaces';
import { useForm, usePage } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import FileIcon from '@/Components/FileIcon.vue';
import Subtask from '@/Components/subtask/SubtaskComponent.vue';
import Multiselect from '@suadelabs/vue3-multiselect';
import '@suadelabs/vue3-multiselect/dist/vue3-multiselect.css';

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

const props = defineProps({
  taskId: {
    type: Number,
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

const taskData = ref<task>();
const subtaskId = ref<number>(0);
const emits = defineEmits(['close', 'scrollToBottom']);

const attachmentForm = useForm({
  file: {} as any,
  task_id: props.taskId
});

const scrollToBottom = () => {
  emits('scrollToBottom');
};

const datepickerOptions = ref<any>({
  position: 'auto left',
  enableTime: true,
  dateFormat: 'Y-m-d H:i'
});

const page = usePage();
const isEditDescription = ref(false);
const updateTaskResult = ref(false);
const isAddSubtask = ref(false);
const isEditSubtask = ref(false);
const uploadedFiles = ref<Array<{ id: number; name: string }>>([]);

const taskForm = useForm({
  title: '',
  description: '',
  due_date: '',
  result: '',
  status: '',
  assign_to: null,
  started_at: '',
  assignee: {} as any,
  assigner: {} as any,
  assignee_id: 0,
  assigner_id: 0,
  attachments: [],
  created_by: 0
});

const subtaskForm = useForm({
  title: '',
  description: '',
  due_date: '',
  assign_to: null,
  created_by: page.props.auth.user.id,
  task_id: props.taskId,
  assignee: {} as any,
  assigner: {} as any,
  assignee_id: 0,
  assigner_id: 0
});

const clearSubtaskForm = () => {
  subtaskForm.title = '';
  subtaskForm.description = '';
  subtaskForm.due_date = '';
  subtaskForm.assignee = {};
  subtaskForm.assigner = {};
  subtaskForm.assignee_id = 0;
  subtaskForm.assigner_id = 0;
  subtaskForm.created_by = page.props.auth.user.id;
  subtaskForm.task_id = props.taskId;
};

const startTask = () => {
  const now = new Date();
  const formattedTimestamp = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(
    2,
    '0'
  )}-${String(now.getDate()).padStart(2, '0')} ${String(now.getHours()).padStart(2, '0')}:${String(
    now.getMinutes()
  ).padStart(2, '0')}:${String(now.getSeconds()).padStart(2, '0')}`;
  taskForm.started_at = formattedTimestamp;
  taskForm.status = 'doing';
  taskForm.post(route('task.start', props.taskId), {
    onSuccess: () => {
      if (taskData.value?.assignee.id === $page.props.auth.user.id) {
        taskData.value.started_at = formattedTimestamp;
      }
      taskForm.status = '';
    }
  });
};

const onMarkAsDone = () => {
  taskForm.status = 'done';
  taskForm.post(route('task.mark-as-done', props.taskId), {
    onSuccess: () => {
      taskForm.status = '';
    }
  });
};

const updateResult = async () => {
  if (taskData.value.result && taskData.value.result !== '') {
    taskForm.result = taskData.value.result;
    taskForm.patch(route('task.update-result', props.taskId), {
      onSuccess: () => {
        updateTaskResult.value = false;
      }
    });
  } else {
    cancelUpdatingResult();
  }
};

const cancelUpdatingResult = () => {
  updateTaskResult.value = false;
  taskForm.clearErrors();
};

const uploadAttachment = () => {
  attachmentForm.post(route('task-attachment.upload'), {
    onSuccess: () => {
      attachmentForm.clearErrors();
      const file = page.props.flash_data.file;
      uploadedFiles.value.push({
        id: file.id,
        name: file.slug
      });
    }
  });
};

const removeAttachment = (fileId: number) => {
  attachmentForm.delete(route('task-attachment.delete', fileId), {
    onSuccess: () => {
      uploadedFiles.value = uploadedFiles.value.filter((file) => file.id !== fileId);
      attachmentForm.file = {};
      attachmentForm.clearErrors();
    }
  });
};

const addSubtask = () => {
  isAddSubtask.value = true;
};

const editSubtask = (id: number) => {
  isEditSubtask.value = true;
  subtaskId.value = id;
};

const closeModal = () => {
  isAddSubtask.value = false;
  isEditSubtask.value = false;
  clearSubtaskForm();
};

const getTaskDetailData = async (taskId: number) => {
  return await getTaskDetail(taskId).then((response) => {
    taskData.value = response.data.task;
    response.data.task.attachments.forEach((file: any) => {
      uploadedFiles.value.push({
        id: file.id,
        name: file.slug
      });
    });
    taskForm.title = response.data.task.title;
    taskForm.description = response.data.task.description;
    taskForm.due_date = response.data.task.due_date;
    taskForm.result = response.data.task.result;
    taskForm.status = response.data.task.status;
    taskForm.started_at = response.data.task.started_at;
    taskForm.assignee_id = response.data.task.assignee_id;
    taskForm.assigner_id = response.data.task.assigner_id;
    taskForm.attachments = response.data.task.attachments;
    taskForm.created_by = response.data.task.assigner_id;
  });
};

const saveSubtask = () => {
  subtaskForm.assignee_id = subtaskForm.assignee.id;
  subtaskForm.assigner_id = subtaskForm.assigner.id;
  subtaskForm.post(route('subtask.store'), {
    onSuccess: () => {
      isAddSubtask.value = false;
      getTaskDetailData(props.taskId);
      clearSubtaskForm();
    }
  });
};

const canPerform = (status: string) => {
  return ['done', 'closed'].includes(status);
};

onMounted(async () => {
  await getTaskDetailData(props.taskId);
});
</script>
