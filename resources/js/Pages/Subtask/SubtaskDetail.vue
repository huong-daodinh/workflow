<template>
  <div v-if="taskData" class="bg-white dark:border-[#1b2e4b] dark:bg-[#191e3a] dark:shadow-none">
    <div class="">
      <div class="p-4">
        <div class="relative">
          <div class="">
            <div class="p-0 w-full text-black dark:text-white-dark">
              <button
                type="button"
                class="absolute right-0 text-gray-400 hover:text-gray-800 dark:hover:text-gray-600 outline-none"
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
                  <button type="button" class="btn btn-primary" @click="startTask(taskData.id)">
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
                  :disabled="taskData.started_at === null || taskData.status === 'done'"
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
                <label class="uppercase text-primary">{{ $t('task_description') }}</label>
                <button
                  v-if="!isEditDescription"
                  :disabled="taskData.status === 'done'"
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
                    v-model="subtaskForm.description" />
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
              <label class="uppercase text-dark text-xl">{{ $t('task_result') }}</label>
              <button
                class="btn btn-outline-secondary"
                :disabled="taskData.status === 'done'"
                @click="isUpdateTaskResult = true"
                v-if="!isUpdateTaskResult">
                {{ $t('update_task_result') }}
              </button>
              <div v-if="isUpdateTaskResult">
                <ckeditor
                  :config="options.editorConfig"
                  v-model="subtaskForm.result"
                  :editor="options.editor" />
                <input-error :message="subtaskForm.errors.result" />
                <div class="flex justify-end mt-1 gap-x-3">
                  <button type="button" class="btn btn-primary" @click="updateResult">
                    {{ $t('save') }}
                  </button>
                  <button
                    type="button"
                    class="btn btn-outline-danger"
                    @click="isUpdateTaskResult = false">
                    {{ $t('cancel') }}
                  </button>
                </div>
              </div>
              <div class="pl-5 mt-2" v-else>
                <div v-html="taskData.result" />
              </div>
            </div>
            <hr />
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- <div v-else class="flex justify-center items-center absolute inset-0"></div> -->
</template>

<script lang="ts" setup>
import { subTask } from '@/interfaces/index.interfaces';
import { defineProps, ref, defineEmits, onMounted } from 'vue';
import 'ckeditor5/ckeditor5.css';
import '@css/ckeditor.css';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import IconX from '@/Components/icon/icon-x.vue';
import UserInfo from '@/Components/UserInfo.vue';
import { editorConfig } from '@/Components/plugins/ckeditor';

const options = ref(editorConfig);
const isEditDescription = ref(false);
const isUpdateTaskResult = ref(false);
const taskData = ref<subTask>();

const props = defineProps({
  subtask_id: {
    type: Number,
    required: true
  }
});

const emits = defineEmits(['close']);

const subtaskForm = useForm({
  title: '',
  description: '',
  due_date: '',
  result: '',
  assign_to: null,
  task_id: 0,
  status: '',
  started_at: '',
  assignee: {} as any,
  assigner: {} as any,
  assignee_id: 0,
  assigner_id: 0,
  created_by: 0
});

const startTask = (taskId: number) => {
  const now = new Date();
  const formattedTimestamp = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(
    2,
    '0'
  )}-${String(now.getDate()).padStart(2, '0')} ${String(now.getHours()).padStart(2, '0')}:${String(
    now.getMinutes()
  ).padStart(2, '0')}:${String(now.getSeconds()).padStart(2, '0')}`;
  subtaskForm.started_at = formattedTimestamp;
  subtaskForm.status = 'doing';
  subtaskForm.post(route('subtask.start', taskId), {
    onSuccess: () => {
      subtaskForm.status = '';
      getSubtaskData();
    }
  });
};

const updateResult = () => {
  subtaskForm.patch(route('subtask.update', props.subtask_id));
  isUpdateTaskResult.value = false;
  getSubtaskData();
};

const onMarkAsDone = () => {
  subtaskForm.status = 'done';
  subtaskForm.post(route('subtask.mark-as-done', props.subtask_id), {
    onSuccess: () => {
      subtaskForm.status = '';
      getSubtaskData();
    }
  });
};

const getSubtaskData = async () => {
  await axios.get(route('subtask.show', props.subtask_id)).then((response) => {
    taskData.value = response.data;
    subtaskForm.title = taskData.value.title;
    subtaskForm.description = taskData.value.description;
    subtaskForm.due_date = taskData.value.due_date;
    subtaskForm.result = taskData.value.result;
    subtaskForm.status = taskData.value.status;
    subtaskForm.started_at = taskData.value.started_at;
    subtaskForm.assignee = taskData.value.assignee;
    subtaskForm.assigner = taskData.value.assigner;
    subtaskForm.assigner_id = taskData.value.assigner.id;
    subtaskForm.assignee_id = taskData.value.assignee.id;
    subtaskForm.task_id = taskData.value.task_id;
    subtaskForm.created_by = taskData.value.assigner.id;
  });
};

onMounted(() => {
  getSubtaskData();
});
</script>
