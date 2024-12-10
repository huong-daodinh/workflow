<template>
  <AuthenticatedLayout>
    <div class="relative">
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
          <div class="!mt-0 relative">
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
                        <template v-if="room.active && room.is_private_chat">
                          <div class="absolute bottom-0 ltr:right-0 rtl:left-0">
                            <div class="w-4 h-4 bg-success rounded-full"></div>
                          </div>
                        </template>
                      </div>
                      <div class="mx-3 ltr:text-left rtl:text-right">
                        <p class="mb-1 font-semibold">{{ room.name }}</p>
                        <p
                          v-if="!room.has_room && room.is_private_chat"
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
          </div>
          <div class="ltr:left-0 rtl:right-0 absolute bottom-0 p-4 w-full">
            <button class="btn btn-primary w-full" type="button" @click="createNewChat()">
              <icon-plus class="ltr:mr-2 rtl:ml-2 shrink-0" />
              Tạo nhóm trò chuyện
            </button>
          </div>
          <div
            v-if="isGettingMessages"
            class="absolute inset-0 flex justify-center items-center bg-primary-light bg-opacity-20"></div>
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
                  Bắt đầu trò chuyện
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
                  <div class="relative flex-none" v-if="selectRoom.is_private_chat">
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
                    <p class="text-white-dark text-xs" v-if="selectRoom.is_private_chat">
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
      <div
        v-if="isGettingMessages"
        class="absolute inset-0 flex justify-center items-center bg-primary-light bg-opacity-20">
        <span
          class="animate-spin border-4 border-primary border-l-transparent rounded-full w-10 h-10"></span>
      </div>
    </div>
    <!-- create chat modal -->
    <TransitionRoot appear :show="isCreateNewChat" as="template">
      <Dialog as="div" @close="isCreateNewChat = false" class="relative z-50">
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
                  @click="closeModal">
                  <icon-x />
                </button>
                <div
                  class="text-lg font-semibold ltr:pl-5 rtl:pr-5 py-5 ltr:pr-[50px] rtl:pl-[50px]">
                  Tạo nhóm trò chuyện
                </div>
                <div class="p-5">
                  <form class="space-y-5" @submit.prevent="saveChat">
                    <div class="mb-4">
                      <label for="">Hình đại diện</label>

                      <div class="flex">
                        <div
                          class="bg-[#eee] gap-x-4 p-1 flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                          <icon-file />
                          Browse
                        </div>
                        <input
                          type="file"
                          ref="uploadFile"
                          @input="chatForm.avatar = $event.target?.files[0]"
                          class="form-input rounded-l-none" />
                      </div>
                      <input-error :message="chatForm.errors.avatar" />
                    </div>
                    <div class="mb-4">
                      <label for="">Tên</label>
                      <div class="relative">
                        <span
                          class="absolute ltr:left-3 rtl:right-3 top-1/2 -translate-y-1/2 dark:text-white-dark">
                          <icon-pencil-paper />
                        </span>
                        <input
                          type="text"
                          v-model="chatForm.name"
                          placeholder="Name"
                          class="form-input ltr:pl-10 rtl:pr-10" />
                      </div>
                      <input-error :message="chatForm.errors.name" />
                    </div>
                    <div class="mb-4">
                      <label for="">{{ $t('members') }}</label>
                      <div class="relative">
                        <span
                          class="absolute ltr:left-3 rtl:right-3 top-1/2 -translate-y-1/2 dark:text-white-dark">
                          <icon-users />
                        </span>
                        <multiselect
                          v-model="memberSelected"
                          :options="members"
                          label="name"
                          class="custom-multiselect"
                          :multiple="true"
                          track-by="id"
                          :option-height="104"
                          :searchable="true"
                          placeholder="Chọn thành viên"
                          selected-label=""
                          select-label=""
                          deselect-label="">
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
                      <input-error :message="chatForm.errors.members" />
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
  </AuthenticatedLayout>
</template>
<script lang="ts" setup>
import { ref, computed, defineProps, onMounted, onBeforeUnmount } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import IconHorizontalDots from '@/Components/icon/icon-horizontal-dots.vue';
import IconSearch from '@/Components/icon/icon-search.vue';
import IconMenu from '@/Components/icon/icon-menu.vue';
import IconSend from '@/Components/icon/icon-send.vue';
import IconMessage from '@/Components/icon/icon-message.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import Multiselect from '@suadelabs/vue3-multiselect';
import '@suadelabs/vue3-multiselect/dist/vue3-multiselect.css';
import _ from 'lodash';
import { showMessage } from '@/functions/alert';
import { user } from '@/interfaces/index.interfaces';
import InputError from '@/Components/InputError.vue';
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogOverlay
} from '@headlessui/vue';
import IconPencilPaper from '@/Components/icon/icon-pencil-paper.vue';
import IconUsers from '@/Components/icon/icon-users.vue';
import IconFile from '@/Components/icon/icon-file.vue';

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
const isCreateNewChat = ref(false);
const search = ref('');
const textMessage = ref('');
const selectedRoom = ref<any>(null);
const members = ref<Array<user>>([]);
const memberSelected = ref<Array<user>>([]);
const isGettingMessages = ref(false);

const chatForm = useForm({
  name: '',
  members: [],
  avatar: null
});

const selectRoom = async (room: any) => {
  // if chat is not public and is not created
  isGettingMessages.value = true;
  if (!room.has_room) {
    await axios
      .post(route('private-chat.create'), {
        id: room.id,
        name: `${authUser.value.id}__${room.id}`
      })
      .then((response) => {
        room = response.data.chat;
        room.has_room = true;
        activeRooms.value?.push(room);
      })
      .catch((error) => {
        console.log(error);
      });
    Echo.join(`room.${room.id}`).listen('MessageSent', (data: any) => {
      if (selectedRoom.value && room.id === selectedRoom.value.id) {
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
  setTimeout(() => {
    isGettingMessages.value = false;
  }, 500);
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
}, 300);

const createNewChat = async () => {
  await axios.get(route('chat.members')).then((response) => {
    members.value = response.data.members;
  });
  isCreateNewChat.value = true;
};

const saveChat = () => {
  chatForm.members = memberSelected.value.map((member) => member.id);
  console.log(chatForm);

  chatForm.post(route('chat.create'), {
    preserveScroll: true,
    onSuccess: () => {
      isCreateNewChat.value = false;
      chatForm.reset();
      chatForm.clearErrors();
      memberSelected.value = [];
      const room = page.props.flash_data.chat;
      activeRooms.value.push(page.props.flash_data.chat);
      Echo.join(`room.${room.id}`).listen('MessageSent', (data: any) => {
        if (selectedRoom.value && room.id === selectedRoom.value.id) {
          selectedRoom.value.messages.push(data.message);
          scrollToBottom();
        } else {
          console.log(room.unseen_messages++);
        }
      });
    }
  });
};

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

  // join creating new room channel
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
