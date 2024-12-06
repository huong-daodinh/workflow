<template>
  <AuthenticatedLayout>
    <div>
      <div
        class="flex gap-5 relative sm:h-[calc(100vh_-_150px)] h-full sm:min-h-0"
        :class="{ 'min-h-[999px]': isShowChatMenu }">
        <div
          class="panel p-4 flex-none max-w-xs w-full absolute xl:relative z-10 space-y-4 h-full hidden xl:block overflow-hidden"
          :class="isShowChatMenu && '!block !overflow-y-auto'">
          <div class="flex justify-between items-center">
            <div class="flex items-center">
              <div class="flex-none">
                <span
                  v-if="!authUser.avatar"
                  class="flex justify-center items-center w-14 h-14 text-center rounded-full object-cover bg-info text-lg">
                  AG
                </span>
                <img
                  :src="authUser.avatar"
                  alt="avatar"
                  class="inline-block h-14 w-14 rounded-full object-cover" />
              </div>
              <div class="mx-3">
                <p class="mb-1 font-semibold">{{ authUser?.name }}</p>
                <p class="text-xs text-white-dark">{{ authUser?.role }}</p>
              </div>
            </div>
          </div>
          <div class="relative">
            <input
              type="text"
              class="form-input peer ltr:pr-9 rtl:pl-9"
              v-model="search"
              @input="debounceGetRooms"
              placeholder="Search conversations" />
            <div
              class="absolute ltr:right-2 rtl:left-2 top-1/2 -translate-y-1/2 peer-focus:text-primary">
              <icon-search />
            </div>
          </div>
          <div class="h-px w-full border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
          <div class="!mt-0">
            <div
              class="chat-users relative h-full min-h-[100px] overflow-y-auto sm:h-[calc(100vh_-_357px)] space-y-0.5 ltr:pr-3.5 rtl:pl-3.5 ltr:-mr-3.5 rtl:-ml-3.5">
              <div v-for="room in searchRooms" :key="room.id">
                <button
                  type="button"
                  class="w-full flex justify-between items-center p-2 hover:bg-gray-100 dark:hover:bg-[#050b14] rounded-md dark:hover:text-primary hover:text-primary"
                  :class="{
                    'bg-gray-100 dark:bg-[#050b14] dark:text-primary text-primary':
                      selectedRoom && selectedRoom.id === room.id
                  }"
                  @click="selectRoom(room)">
                  <div class="flex-1">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 relative">
                        <img
                          v-if="room.avatar"
                          :src="room.avatar"
                          class="rounded-full h-12 w-12 object-cover" />
                        <template v-if="room.active">
                          <div class="absolute bottom-0 ltr:right-0 rtl:left-0">
                            <div class="w-4 h-4 bg-success rounded-full"></div>
                          </div>
                        </template>
                      </div>
                      <div class="mx-3 ltr:text-left rtl:text-right">
                        <p class="mb-1 font-semibold">{{ room.name }}</p>
                        <p
                          v-if="!room.has_room"
                          class="text-xs text-white-dark truncate max-w-[185px]">
                          Bắt đầu trò chuyện
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="font-semibold whitespace-nowrap text-xs">
                    <p>{{ room.time }}</p>
                  </div>
                  <span
                    class="badge bg-danger"
                    v-if="selectedRoom && selectedRoom.id !== room.id && room.unseen_messages > 0">
                    {{ room.unseen_messages }}
                  </span>
                </button>
              </div>
            </div>
            <div class="ltr:left-0 rtl:right-0 absolute bottom-0 p-4 w-full">
              <button class="btn btn-primary w-full" type="button" @click="addEditTask()">
                <icon-plus class="ltr:mr-2 rtl:ml-2 shrink-0" />

                Tạo nhóm trò chuyện
              </button>
            </div>
          </div>
        </div>
        <div
          class="bg-black/60 z-[5] w-full h-full absolute rounded-md hidden"
          :class="isShowChatMenu && '!block xl:!hidden'"
          @click="isShowChatMenu = !isShowChatMenu"></div>
        <div class="panel p-0 flex-1">
          <template v-if="!isShowUserChat">
            <div class="flex items-center justify-center h-full relative p-4">
              <button
                type="button"
                class="xl:hidden absolute top-4 ltr:left-4 rtl:right-4 hover:text-primary"
                @click="isShowChatMenu = !isShowChatMenu">
                <icon-menu />
              </button>

              <div class="py-8 flex items-center justify-center flex-col">
                <div
                  class="w-[280px] md:w-[430px] mb-8 h-[calc(100vh_-_320px)] min-h-[120px] text-white dark:text-[#0e1726]"></div>
                <p
                  class="flex justify-center bg-white-dark/20 p-2 font-semibold rounded-md max-w-[190px] mx-auto">
                  <icon-message class="ltr:mr-2 rtl:ml-2" />

                  Click User To Chat
                </p>
              </div>
            </div>
          </template>
          <template v-if="isShowUserChat && selectedRoom">
            <div class="relative h-full">
              <div class="flex justify-between items-center p-4">
                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                  <button
                    type="button"
                    class="xl:hidden hover:text-primary"
                    @click="isShowChatMenu = !isShowChatMenu">
                    <icon-menu />
                  </button>
                  <div class="relative flex-none">
                    <img
                      :src="selectedRoom.avatar"
                      class="rounded-full w-10 h-10 sm:h-12 sm:w-12 object-cover" />
                    <div
                      class="absolute bottom-0 ltr:right-0 rtl:left-0"
                      v-if="selectedRoom.active">
                      <div class="w-4 h-4 bg-success rounded-full"></div>
                    </div>
                  </div>
                  <div class="mx-3">
                    <p class="font-semibold">{{ selectedRoom.name }}</p>
                    <p class="text-white-dark text-xs">
                      {{
                        selectedRoom.active
                          ? 'Active now'
                          : 'Last seen at ' + selectedRoom.last_seen
                      }}
                    </p>
                  </div>
                </div>
                <div class="flex sm:gap-5 gap-3">
                  <button
                    type="button"
                    class="bg-[#f4f4f4] dark:bg-[#1b2e4b] hover:bg-primary-light w-8 h-8 rounded-full !flex justify-center items-center">
                    <icon-horizontal-dots class="hover:text-primary rotate-90 opacity-70" />
                  </button>
                </div>
              </div>
              <div class="h-px w-full border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
              <div
                class="relative h-full sm:h-[calc(100vh_-_300px)] chat-conversation-box overflow-y-auto">
                <div class="space-y-5 p-4 sm:pb-0 pb-[68px] sm:min-h-[300px] min-h-[400px]">
                  <div class="block m-6 mt-0">
                    <h4
                      class="text-xs text-center border-b border-[#f4f4f4] dark:border-gray-800 relative">
                      <span class="relative top-2 px-3 bg-white dark:bg-[#0e1726]">
                        {{ 'Today, ' + selectedRoom.created_at }}
                      </span>
                    </h4>
                  </div>
                  <template v-if="selectedRoom.messages && selectedRoom.messages.length">
                    <div v-for="(message, index) in selectedRoom.messages" :key="index">
                      <div
                        class="flex items-start gap-3"
                        :class="{ 'justify-end': message.sent_by.id === authUser.id }">
                        <div
                          class="flex-none"
                          :class="{ 'order-2': authUser.id === message.sent_by.id }">
                          <template v-if="authUser.Id === message.sent_by.id">
                            <img
                              :src="authUser.avatar"
                              class="rounded-full h-10 w-10 object-cover" />
                          </template>
                          <template v-if="authUser.Id !== message.sent_by.id">
                            <img
                              :src="message.sent_by.avatar"
                              class="rounded-full h-10 w-10 object-cover" />
                          </template>
                        </div>
                        <div class="space-y-2">
                          <div class="flex items-center gap-3">
                            <div
                              class="dark:bg-gray-800 p-4 py-2 rounded-md bg-black/10"
                              :class="
                                message.sent_by.id === authUser.id
                                  ? 'ltr:rounded-br-none rtl:rounded-bl-none !bg-primary text-white'
                                  : 'ltr:rounded-bl-none rtl:rounded-br-none'
                              ">
                              {{ message.content }}
                            </div>
                          </div>
                          <div
                            class="text-xs text-white-dark"
                            :class="{
                              'ltr:text-right rtl:text-left': selectedRoom.id === message.sent_by.id
                            }">
                            {{ message.created_at }}
                          </div>
                        </div>
                      </div>
                    </div>
                  </template>
                </div>
              </div>
              <div class="p-4 absolute bottom-0 left-0 w-full">
                <div class="sm:flex w-full space-x-3 rtl:space-x-reverse items-center">
                  <div class="relative flex-1">
                    <input
                      class="form-input rounded-full border-0 bg-[#f4f4f4] pl-4 pr-12 focus:outline-none py-2"
                      placeholder="Nhấn enter để gửi"
                      v-model="textMessage"
                      @keyup.enter.exact="sendMessage(selectedRoom)" />
                    <button
                      type="button"
                      class="absolute ltr:right-4 rtl:left-4 top-1/2 -translate-y-1/2 hover:text-primary"
                      @click="sendMessage(selectedRoom)">
                      <icon-send />
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
<script lang="ts" setup>
import { ref, computed, defineProps, watch, onMounted, onBeforeUnmount } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import IconHorizontalDots from '@/Components/icon/icon-horizontal-dots.vue';
import IconSearch from '@/Components/icon/icon-search.vue';
import IconMenu from '@/Components/icon/icon-menu.vue';
import IconSend from '@/Components/icon/icon-send.vue';
import IconMessage from '@/Components/icon/icon-message.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import _ from 'lodash';
import { showMessage } from '@/functions/alert';

const page = usePage();
const axios = window.axios;
const props = defineProps({
  rooms: Array<any>
});

const activeRooms = ref(props.rooms);
const searchRooms = ref(props.rooms);
const authUser = computed(() => {
  return page.props.auth.user;
});
const isShowUserChat = ref(false);
const isShowChatMenu = ref(false);
const search = ref('');
const textMessage = ref('');
const selectedRoom = ref<any>(null);

const selectRoom = async (room: any) => {
  // if private chat is not created
  if (!room.has_room) {
    await axios
      .post(route('private-chat.create'), {
        id: room.id,
        name: `${authUser.value.id}__${room.id}`
      })
      .then((response) => {
        room = response.data.chat;
        activeRooms.value?.push(room);
      })
      .catch((error) => {
        console.log(error);
      });
    Echo.join(`room.${room.id}`).listen('MessageSent', (data: any) => {
      if (room.id === selectedRoom.value.id) {
        selectedRoom.value.messages.push(data.message);
        scrollToBottom();
      } else {
        console.log(room.unseen_messages++);
      }
    });
  }
  selectedRoom.value = room;
  room.unseen_messages = 0;
  await getMessages(room);
  scrollToBottom();
  isShowUserChat.value = true;
  isShowChatMenu.value = false;
};

const sendMessage = async (room: any, replyTo = null) => {
  if (textMessage.value.trim()) {
    await axios
      .post(route('chat.send-message'), {
        content: textMessage.value,
        chat_id: room.id,
        replied_to: replyTo,
        sent_by: authUser.value.id
      })
      .then((response) => {
        selectedRoom.value.messages.push(response.data.message);
        console.log(response.data.message);
      })
      .catch((error) => {
        showMessage('Có lỗi xảy ra, không gửi được tin nhắn', 'error');
        console.log(error);
      });
    textMessage.value = '';
    scrollToBottom();
  }
};

const getMessages = async (room: any) => {
  await axios
    .get(route('chat.get-messages', { chat: room.id }))
    .then((response) => {
      selectedRoom.value.messages = response.data.messages;
    })
    .catch((error) => {
      showMessage('Có lỗi khi tải tin nhắn', 'error');
      console.log(error);
    });
};

const scrollToBottom = () => {
  if (isShowUserChat.value) {
    setTimeout(() => {
      const element: any = document.querySelector('.chat-conversation-box');
      element.behavior = 'smooth';
      element.scrollTop = element.scrollHeight;
    });
  }
};

const debounceGetRooms = _.debounce(async () => {
  if (search.value.length > 0) {
    await axios
      .get(route('chat.search'), { params: { search: search.value } })
      .then((response) => {
        searchRooms.value = response.data.chats;
      })
      .catch((error) => {
        console.log(error);
      });
  } else {
    searchRooms.value = activeRooms.value;
  }
}, 500);

onMounted(() => {
  searchRooms.value?.forEach((room) => {
    if (room.unseen_messages === undefined) {
      room.unseen_messages = 0;
    }
    Echo.join(`room.${room.id}`).listen('MessageSent', (data: any) => {
      if (selectedRoom.value !== null && room.id === selectedRoom.value.id) {
        room.unseen_messages = 0;
        selectedRoom.value.messages.push(data.message);
        scrollToBottom();
      } else {
        room.unseen_messages++;
      }
    });
  });

  // check new room created
  Echo.join(`member.${authUser.value.id}`).listen('ChatCreated', (data: any) => {
    console.log(data.chat);
    activeRooms.value?.push(data.chat);
  });
});

onBeforeUnmount(() => {
  activeRooms.value?.forEach((room) => {
    Echo.leave(`room.${room.id}`);
  });
  Echo.leave(`member.${authUser.value.id}`);
});
</script>
