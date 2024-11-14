<template>
  <div>
    <label for="" class="uppercase text-dark text-lg"
      >{{ $t('comments') }} ({{ messages.length }})</label
    >
    <div v-if="messages.length > 0" class="max-h-[calc(50vh-80px)] overflow-y-auto mb-5 chat-box">
      <div class="flex mb-5 relative" v-for="(message, index) in messages" :key="index">
        <!-- loading -->
        <div
          v-if="isSendingMessage.includes(Number(message.id))"
          class="absolute inset-0 bg-primary-light flex justify-center items-center bg-opacity-50">
          <span
            class="animate-spin border-4 border-primary border-l-transparent rounded-full w-10 h-10 inline-block align-middle m-auto mb-10"></span>
        </div>
        <!-- message -->
        <div class="mr-4">
          <img src="/assets/images/profile-5.jpeg" alt="" class="w-10 h-10 rounded" />
        </div>
        <div class="flex-1">
          <span class="text-dark font-bold">{{ message.sent_by.name }}</span>
          <p class="media-text mb-4">
            {{ message.content }}
          </p>
          <ul class="flex space-x-4 rtl:space-x-reverse font-bold">
            <li>
              <a href="javascript:;" class="flex items-center hover:text-primary">
                <icon-message-dots />
                {{ $t('response') }}
              </a>
            </li>
            <li>
              <a href="javascript:;" class="flex items-center hover:text-primary">
                <icon-edit />
                {{ $t('reply') }}
              </a>
            </li>
            <li>
              <a href="javascript:;" class="flex items-center hover:text-primary">
                <icon-trash />
                {{ $t('delete') }}
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="bottom-0 left-0 w-full">
      <div class="sm:flex w-full space-x-3 rtl:space-x-reverse items-center">
        <div class="relative flex-1">
          <Mentionable
            placement="top"
            :keys="['@']"
            :items="users"
            offset="6"
            insert-space
            @open="canSendMessage = false"
            @close="onCloseMention">
            <input
              @keyup.enter="handleEnterKey"
              v-model="messageInput"
              class="form-input border-0 bg-[#f4f4f4] pr-12 focus:outline-none py-2"
              placeholder="Type a message" />

            <template #no-result>
              <div class="dim">No result</div>
            </template>

            <template #item-@="{ item }" class="cursor-pointer">
              <div class="user">
                {{ item.value }}
                <span class="dim"> ({{ item.firstName }}) </span>
              </div>
            </template>
          </Mentionable>
          <button
            type="button"
            @click="sendMessage()"
            class="absolute ltr:right-4 rtl:left-4 top-1/2 -translate-y-1/2 hover:text-primary">
            <icon-send />
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import IconSend from '@/Components/icon/icon-send.vue';
import IconMessageDots from '@/Components/icon/icon-message-dots.vue';
import IconEdit from '@/Components/icon/icon-edit.vue';
import IconTrash from '@/Components/icon/icon-trash.vue';
import { defineProps, onMounted, ref, defineEmits } from 'vue';
import type { task_message } from '@/interfaces/index.interfaces';
import { getTaskMessages } from '@/services/task.service';
import { usePage } from '@inertiajs/vue3';
import { sendTaskMessage } from '@/services/task.service';
import { Mentionable } from 'vue-mention';
// import Echo from 'laravel-echo';

const isSendingMessage = ref<Array<number>>([]);
const page = usePage();
const authUser = page.props.auth.user;
const canSendMessage = ref(true);

const emits = defineEmits(['messageSent']);
const props = defineProps({
  taskId: {
    type: Number,
    required: true
  }
});

const users = [
  {
    value: 'akryum',
    firstName: 'Guillaume'
  },
  {
    value: 'posva',
    firstName: 'Eduardo'
  },
  {
    value: 'atinux',
    firstName: 'Sébastien'
  },
  {
    value: 'atinux2',
    firstName: 'Sébastien2'
  },
  {
    value: 'atinux3',
    firstName: 'Sébastien3'
  },
  {
    value: 'atinux4',
    firstName: 'Sébastien4'
  },
  {
    value: 'atinux5',
    firstName: 'Sébastien5'
  }
];

const onCloseMention = () => {
  setTimeout(() => {
    canSendMessage.value = true;
  }, 100);
};

const messageInput = ref('');
const messages = ref<Array<task_message>>([]);

const handleEnterKey = () => {
  if (canSendMessage.value) {
    sendMessage();
  }
};

const sendMessage = async () => {
  if (messageInput.value.trim() === '') {
    return;
  }
  const message = {
    id: messages.value.length + 1,
    content: messageInput.value,
    sent_by: authUser
  };
  messages.value.push(message);
  isSendingMessage.value.push(message.id);
  await sendTaskMessage(props.taskId, messageInput.value).finally(() => {
    isSendingMessage.value = isSendingMessage.value.filter((id) => id !== message.id);
    messageInput.value = '';
    emits('messageSent');
    scrollToBottom();
  });
};

const scrollToBottom = () => {
  setTimeout(() => {
    const element: any = document.querySelector('.chat-box');
    element.behavior = 'smooth';
    element.scrollTop = element.scrollHeight;
  });
};

onMounted(async () => {
  await getTaskMessages(props.taskId).then((res) => {
    messages.value = res.data;
    scrollToBottom();
  });
  // Echo.channel(`TaskConversation.${props.taskId}`).listen('message-posted', (data: any) => {
  //   messages.value.push(data.message);
  // });
});
</script>
