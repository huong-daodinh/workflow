<template>
  <div
    class="w-[19rem] w-full h-full bg-primary-light dark:border-[#1b2e4b] dark:bg-dark-light dark:shadow-none">
    <div class="p-3 flex flex-col gap-3">
      <div class="w-full bg-white">
        <div class="border-b border-[#e0e6ed] dark:border-[#1b2e4b] p-4">{{ $t('assigner') }}</div>
        <div class="flex gap-x-5 p-3">
          <img
            :src="task.assigner.avatar"
            v-if="task.assigner.avatar"
            class="w-12 h-12 object-cover rounded-full" />
          <span
            v-else
            class="flex justify-center items-center w-12 h-12 text-center rounded-full object-cover bg-info text-lg dark:text-secondary">
            {{ takeSummaryName(task.assigner.name) }}
          </span>
          <div>
            <h5 class="text-[#3b3f5c] lg:text-xl text-sm font-semibold dark:text-white-light">
              {{ task.assigner.name }}
            </h5>
            <p class="text-white-dark lg:text-base text-xs">
              {{ task.assigner.role }}
            </p>
          </div>
        </div>
      </div>
      <div class="px-5 w-full bg-white">
        <p class="p-2"><span class="font-bold">Created at:</span> {{ created_at }}</p>
        <p class="p-2"><span class="font-bold">Updated at:</span> {{ updated_at }}</p>
        <p class="p-2"><span class="font-bold">Deadline: </span> {{ Deadline }}</p>
      </div>
      <div class="w-full bg-white">
        <div class="border-b border-[#e0e6ed] dark:border-[#1b2e4b] p-4">Followers</div>
        <div class="flex gap-x-5 p-3" v-for="(user, index) in task?.followers" :key="index">
          <img :src="user.avatar" v-if="user.avatar" class="w-12 h-12 object-cover rounded-full" />
          <span
            v-else
            class="flex justify-center items-center w-12 h-12 text-center rounded-full object-cover bg-info text-lg dark:text-secondary">
            {{ takeSummaryName(user.name) }}
          </span>
          <div>
            <h5 class="text-[#3b3f5c] lg:text-xl text-sm font-semibold dark:text-white-light">
              {{ user.name }}
            </h5>
            <p class="text-white-dark lg:text-base text-xs">
              {{ user.role }}
            </p>
          </div>
        </div>
        <div class="p-3">
          <button
            class="btn btn-primary w-full"
            @click="isAddFollowers = true"
            :disabled="task.status === 'closed'">
            + Thêm người theo dõi
          </button>
        </div>
      </div>
    </div>
    <TransitionRoot appear :show="isAddFollowers" as="template">
      <Dialog as="div" @close="closeTaskModal" class="relative z-50">
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
                class="panel border-0 p-0 rounded-lg w-full max-w-2xl text-black dark:text-white-dark">
                <button
                  type="button"
                  class="absolute top-5 ltr:right-5 rtl:left-5 text-white-dark hover:text-dark outline-none"
                  @click="closeTaskModal">
                  <icon-x />
                </button>
                <div
                  class="text-lg font-semibold ltr:pl-5 rtl:pr-5 py-5 ltr:pr-[50px] rtl:pl-[50px]">
                  Thêm người theo dõi
                </div>
                <div class="p-5">
                  <form class="space-y-5" @submit.prevent="saveTask">
                    <div class="mb-4">
                      <div class="relative">
                        <span
                          class="absolute ltr:left-3 rtl:right-3 top-1/2 -translate-y-1/2 dark:text-white-dark">
                          <icon-users />
                        </span>
                        <multiselect
                          v-model="selectedFollowers"
                          :options="nonFollowers"
                          label="name"
                          :multiple="true"
                          class="custom-multiselect"
                          track-by="id"
                          :option-height="104"
                          :searchable="true"
                          placeholder="Select an user to assign">
                          <template #tag="props">
                            <div class="flex items-center gap-1 w-1/4 rounded bg-success-light">
                              <img
                                class="h-6 w-6 rounded-full"
                                :src="props.option.avatar"
                                alt="avatar" />
                              <span class="option__desc">
                                <span class="option__title font-bold">
                                  {{ props.option.name }}
                                </span>
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
                    <div class="flex gap-x-4">
                      <button
                        type="button"
                        class="btn btn-outline-danger w-full"
                        @click="closeTaskModal">
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
  </div>
</template>

<script lang="ts" setup>
import type { task, user } from '@/interfaces/index.interfaces';
import { PropType, computed, defineProps, ref } from 'vue';
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogOverlay
} from '@headlessui/vue';
import { useForm } from '@inertiajs/vue3';
import Multiselect from '@suadelabs/vue3-multiselect';

const props = defineProps({
  task: {
    type: Object as PropType<task>,
    required: true
  },
  nonFollowers: {
    type: Array as PropType<Array<user>>,
    required: true
  }
});
const selectedFollowers = ref<Array<user>>([]);
const isAddFollowers = ref(false);
const created_at = computed(() => convertToCustomFormat(props.task.created_at));
const updated_at = computed(() => convertToCustomFormat(props.task.updated_at));
const Deadline = computed(() => convertToCustomFormat(props.task.due_date));
const taskForm = useForm({
  followers: Array<number>()
});

const closeTaskModal = () => {
  taskForm.reset();
  isAddFollowers.value = false;
};

const saveTask = () => {
  taskForm.followers = selectedFollowers.value.map((user) => user.id);
  taskForm.post(route('task.add-followers', { task: props.task.id }), {
    preserveScroll: true,
    onSuccess: () => {
      closeTaskModal();
    }
  });
};

function convertToCustomFormat(isoDate) {
  // Create a new Date object from the ISO string
  const date = new Date(isoDate);

  // Format each component with padding if necessary
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
  const day = String(date.getDate()).padStart(2, '0');
  const hours = String(date.getHours()).padStart(2, '0');
  const minutes = String(date.getMinutes()).padStart(2, '0');
  const seconds = String(date.getSeconds()).padStart(2, '0');

  // Combine the components into the desired format
  return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}

const takeSummaryName = (name: string) => {
  return name.charAt(0) + name.charAt(1);
};
console.log(props.nonFollowers);
</script>
