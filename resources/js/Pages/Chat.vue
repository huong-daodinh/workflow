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
                  class="flex justify-center items-center w-14 h-14 text-center rounded-full object-cover bg-info text-lg"
                  >AG</span
                >
              </div>
              <div class="mx-3">
                <p class="mb-1 font-semibold">{{ $page.props.auth.user.name }}</p>
                <!-- <p class="text-xs text-white-dark">Software Developer</p> -->
              </div>
            </div>
          </div>
          <div class="relative">
            <input
              type="text"
              class="form-input peer ltr:pr-9 rtl:pl-9"
              placeholder="Search conversations"
              v-model="searchUser" />
            <div
              class="absolute ltr:right-2 rtl:left-2 top-1/2 -translate-y-1/2 peer-focus:text-primary">
              <icon-search />
            </div>
          </div>
          <div class="h-px w-full border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
          <div class="!mt-0">
            <div
              class="chat-users relative h-full min-h-[100px] overflow-y-auto sm:h-[calc(100vh_-_357px)] space-y-0.5 ltr:pr-3.5 rtl:pl-3.5 ltr:-mr-3.5 rtl:-ml-3.5">
              <template v-for="person in searchUsers" :key="person.id">
                <button
                  type="button"
                  class="w-full flex justify-between items-center p-2 hover:bg-gray-100 dark:hover:bg-[#050b14] rounded-md dark:hover:text-primary hover:text-primary"
                  :class="{
                    'bg-gray-100 dark:bg-[#050b14] dark:text-primary text-primary':
                      selectedUser && selectedUser.userId === person.userId
                  }"
                  @click="selectUser(person)">
                  <div class="flex-1">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 relative">
                        <img
                          :src="`/assets/images/${person.path}`"
                          class="rounded-full h-12 w-12 object-cover" />
                        <template v-if="person.active">
                          <div class="absolute bottom-0 ltr:right-0 rtl:left-0">
                            <div class="w-4 h-4 bg-success rounded-full"></div>
                          </div>
                        </template>
                      </div>
                      <div class="mx-3 ltr:text-left rtl:text-right">
                        <p class="mb-1 font-semibold">{{ person.name }}</p>
                        <p class="text-xs text-white-dark truncate max-w-[185px]">
                          {{ person.preview }}
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="font-semibold whitespace-nowrap text-xs">
                    <p>{{ person.time }}</p>
                  </div>
                </button>
              </template>
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
          <template v-if="isShowUserChat && selectedUser">
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
                      :src="`/assets/images/${selectedUser.path}`"
                      class="rounded-full w-10 h-10 sm:h-12 sm:w-12 object-cover" />
                    <div class="absolute bottom-0 ltr:right-0 rtl:left-0">
                      <div class="w-4 h-4 bg-success rounded-full"></div>
                    </div>
                  </div>
                  <div class="mx-3">
                    <p class="font-semibold">{{ selectedUser.name }}</p>
                    <p class="text-white-dark text-xs">
                      {{ selectedUser.active ? 'Active now' : 'Last seen at ' + selectedUser.time }}
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
                      <span class="relative top-2 px-3 bg-white dark:bg-[#0e1726]">{{
                        'Today, ' + selectedUser.time
                      }}</span>
                    </h4>
                  </div>
                  <template v-if="selectedUser.messages && selectedUser.messages.length">
                    <div v-for="(message, index) in selectedUser.messages" :key="index">
                      <div
                        class="flex items-start gap-3"
                        :class="{ 'justify-end': selectedUser.userId === message.fromUserId }">
                        <div
                          class="flex-none"
                          :class="{ 'order-2': selectedUser.userId === message.fromUserId }">
                          <template v-if="selectedUser.userId === message.fromUserId">
                            <img
                              :src="`/assets/images/${loginUser.path}`"
                              class="rounded-full h-10 w-10 object-cover" />
                          </template>
                          <template v-if="selectedUser.userId !== message.fromUserId">
                            <img
                              :src="`/assets/images/${selectedUser.path}`"
                              class="rounded-full h-10 w-10 object-cover" />
                          </template>
                        </div>
                        <div class="space-y-2">
                          <div class="flex items-center gap-3">
                            <div
                              class="dark:bg-gray-800 p-4 py-2 rounded-md bg-black/10"
                              :class="
                                message.fromUserId == selectedUser.userId
                                  ? 'ltr:rounded-br-none rtl:rounded-bl-none !bg-primary text-white'
                                  : 'ltr:rounded-bl-none rtl:rounded-br-none'
                              ">
                              {{ message.text }}
                            </div>
                            <div :class="{ hidden: selectedUser.userId === message.fromUserId }">
                              <icon-mood-smile class="hover:text-primary" />
                            </div>
                          </div>
                          <div
                            class="text-xs text-white-dark"
                            :class="{
                              'ltr:text-right rtl:text-left':
                                selectedUser.userId === message.fromUserId
                            }">
                            {{ message.time ? message.time : '5h ago' }}
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
                      class="form-input rounded-full border-0 bg-[#f4f4f4] px-12 focus:outline-none py-2"
                      placeholder="Type a message"
                      v-model="textMessage"
                      @keyup.enter.exact="sendMessage()" />
                    <button
                      type="button"
                      class="absolute ltr:left-4 rtl:right-4 top-1/2 -translate-y-1/2 hover:text-primary">
                      <icon-mood-smile />
                    </button>
                    <button
                      type="button"
                      class="absolute ltr:right-4 rtl:left-4 top-1/2 -translate-y-1/2 hover:text-primary"
                      @click="sendMessage()">
                      <icon-send />
                    </button>
                  </div>
                  <div
                    class="items-center space-x-3 rtl:space-x-reverse sm:py-0 py-3 hidden sm:block">
                    <button
                      type="button"
                      class="bg-[#f4f4f4] dark:bg-[#1b2e4b] hover:bg-primary-light rounded-md p-2 hover:text-primary">
                      <icon-horizontal-dots class="opacity-70" />
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
import { ref, computed } from 'vue';
import { useAppStore } from '@/stores/index';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import IconHorizontalDots from '@/Components/icon/icon-horizontal-dots.vue';
import IconSearch from '@/Components/icon/icon-search.vue';
import IconMenu from '@/Components/icon/icon-menu.vue';
import IconMoodSmile from '@/Components/icon/icon-mood-smile.vue';
import IconSend from '@/Components/icon/icon-send.vue';
import IconMessage from '@/Components/icon/icon-message.vue';

const store = useAppStore();
const isShowUserChat = ref(false);
const isShowChatMenu = ref(false);
const loginUser = ref({
  id: 0,
  name: 'Alon Smith',
  path: '',
  designation: 'Software Developer'
});
const contactList = ref([
  {
    userId: 1,
    name: 'Nguoi dung 1',
    path: '',
    time: '2:09 PM',
    preview: 'Hi',
    messages: [
      {
        fromUserId: 0,
        toUserId: 1,
        text: 'Hi'
      },
      {
        fromUserId: 0,
        toUserId: 1,
        text: 'Hi'
      },
      {
        fromUserId: 1,
        toUserId: 0,
        text: 'Welcome'
      },
      {
        fromUserId: 1,
        toUserId: 0,
        text: 'Hi'
      },
      {
        fromUserId: 0,
        toUserId: 1,
        text: 'Yes'
      }
    ],
    active: true
  }
]);
const searchUser = ref('');
const textMessage = ref('');
const selectedUser: any = ref(null);

// search co the tim truoc sau do gui request len danh sach thoa man r tra ve
//  hoac la lam nhu filter
const searchUsers = computed(() => {
  setTimeout(() => {
    const element: any = document.querySelector('.chat-users');
    if (element) {
      element.scrollTop = 0;
      element.behavior = 'smooth';
    }
  });
  return contactList.value.filter((d) => {
    return d.name.toLowerCase().includes(searchUser.value);
  });
});

const selectUser = (user: any) => {
  selectedUser.value = user;
  isShowUserChat.value = true;
  scrollToBottom();
  isShowChatMenu.value = false;
};

const sendMessage = () => {
  if (textMessage.value.trim()) {
    const user: any = contactList.value.find((d) => d.userId === selectedUser.value.userId);
    user.messages.push({
      fromUserId: selectedUser.value.userId,
      toUserId: 0,
      text: textMessage.value,
      time: 'Just now'
    });
    textMessage.value = '';
    scrollToBottom();
  }
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
</script>
