<template>
  <div>
    <label for="" class="uppercase text-dark text-lg">
      {{ $t('comments') }} ({{ messages.length }})
    </label>
    <div
      v-if="messages.length > 0"
      class="relative max-h-[calc(50vh-80px)] overflow-y-auto mb-5 chat-box">
      <!-- <div class="absolute bottom-0 left-1/2 -translate-x-1/2">
        <span class="badge bg-primary z-10"> New message</span>
      </div> -->
      <div class="flex mb-5 relative" v-for="(message, index) in messages" :key="index">
        <!-- message -->
        <div class="mr-4">
          <img
            :src="message.sent_by.avatar"
            v-if="message.sent_by.avatar"
            alt=""
            class="w-10 h-10 rounded-full" />
          <span
            v-else
            class="flex justify-center items-center w-9 h-9 text-center rounded-full object-cover bg-info text-base">
            {{ message.sent_by.name.slice(0, 2).toUpperCase() }}
          </span>
        </div>
        <div class="flex-1">
          <span class="text-dark font-bold">{{ message.sent_by.name }}</span>
          <p class="media-text mb-4" v-html="message.content"></p>
          <ul class="flex space-x-4 rtl:space-x-reverse font-bold">
            <li>
              <a href="javascript:;" class="flex items-center hover:text-primary">
                <icon-edit class="mr-2" />
                {{ $t('edit') }}
              </a>
            </li>
            <li>
              <a href="javascript:;" class="flex items-center hover:text-primary">
                <icon-message-dots class="mr-1" />
                {{ $t('response') }}
              </a>
            </li>
            <li>
              <a href="javascript:;" class="flex items-center hover:text-primary">
                <icon-trash class="mr-1" />
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
            :items="mentionData"
            offset="6"
            insert-space
            @open="canSendMessage = false"
            @close="onCloseMention">
            <input
              @keyup.enter="handleEnterKey"
              v-model="messageInput"
              class="form-input border-0 bg-[#f4f4f4] pr-12 focus:outline-none py-2"
              placeholder="Type a message (@ to mention)" />

            <template #no-result>
              <div class="dim">No result</div>
            </template>

            <template #item-@="{ item }" class="cursor-pointer">
              <div class="user">
                #{{ item.id }}
                <span class="dim"> {{ item.value }} </span>
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
import { defineProps, onMounted, ref, defineEmits, onBeforeUnmount, computed } from 'vue';
import type { task_message, user } from '@/interfaces/index.interfaces';
import { getTaskMessages } from '@/services/task.service';
import { sendTaskMessage } from '@/services/task.service';
import { Mentionable } from 'vue-mention';
// import Echo from 'laravel-echo';
const canSendMessage = ref(true);

const emits = defineEmits(['messageSent']);
const props = defineProps({
  taskId: {
    type: Number,
    required: true
  },
  users: {
    type: [] as Array<user>,
    required: false
  }
});

const mentionData = computed(() => {
  return props.users.map((user: user) => {
    return {
      id: user.id,
      value: `(${user.name})`,
      // value: user.id,
      name: user.name
    };
  });
});

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
  await sendTaskMessage(props.taskId, messageInput.value)
    .then((response) => {
      messages.value.push(response.data.message);
    })
    .finally(() => {
      messageInput.value = '';
      emits('messageSent');
      scrollToBottom();
    });
};

const scrollToBottom = () => {
  setTimeout(() => {
    const element: any = document.querySelector('.chat-box');
    element.scrollTop = element.scrollHeight;
  });
};

onMounted(async () => {
  await getTaskMessages(props.taskId).then((res) => {
    messages.value = res.data;
    scrollToBottom();
  });
  Echo.channel(`presence-task.${props.taskId}`).listen('TaskMessagePosted', (data: any) => {
    messages.value.push(data.message);
  });
});

onBeforeUnmount(() => {
  Echo.leave(`presence-task.${props.taskId}`);
});
</script>
