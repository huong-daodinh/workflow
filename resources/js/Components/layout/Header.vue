<template>
  <header class="z-40" :class="{ dark: store.semidark && store.menu === 'horizontal' }">
    <div class="shadow-sm">
      <div class="relative bg-white flex w-full items-center px-5 py-2.5 dark:bg-[#0e1726]">
        <div class="horizontal-logo flex lg:hidden justify-between items-center ltr:mr-2 rtl:ml-2">
          <a to="/" class="main-logo flex items-center shrink-0">
            <img
              class="w-8 ltr:-ml-1 rtl:-mr-1 inline"
              src="/assets/favicon_io/apple-touch-icon.png"
              alt="" />
            <span
              class="text-2xl ltr:ml-1.5 rtl:mr-1.5 font-semibold align-middle hidden md:inline dark:text-white-light transition-all duration-300">
              WORKFLOW
            </span>
          </a>

          <a
            href="javascript:;"
            class="collapse-icon flex-none dark:text-[#d0d2d6] hover:text-primary dark:hover:text-primary flex lg:hidden ltr:ml-2 rtl:mr-2 p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:bg-white-light/90 dark:hover:bg-dark/60"
            @click="store.toggleSidebar()">
            <icon-menu class="w-5 h-5" />
          </a>
        </div>
        <div
          class="sm:flex-1 ltr:sm:ml-0 ltr:ml-auto sm:rtl:mr-0 rtl:mr-auto flex items-center space-x-1.5 lg:space-x-2 rtl:space-x-reverse dark:text-[#d0d2d6]">
          <div class="sm:ltr:mr-auto sm:rtl:ml-auto">
            <form
              class="sm:relative absolute inset-x-0 sm:top-0 top-1/2 sm:translate-y-0 -translate-y-1/2 sm:mx-0 mx-4 z-10 sm:block hidden"
              :class="{ '!block': search }"
              @submit.prevent="search = false">
              <div class="relative">
                <input
                  type="text"
                  class="form-input ltr:pl-9 rtl:pr-9 ltr:sm:pr-4 rtl:sm:pl-4 ltr:pr-9 rtl:pl-9 peer sm:bg-transparent bg-gray-100 placeholder:tracking-widest"
                  placeholder="Search..." />
                <button
                  type="button"
                  class="absolute w-9 h-9 inset-0 ltr:right-auto rtl:left-auto appearance-none peer-focus:text-primary">
                  <icon-search class="mx-auto" />
                </button>
                <button
                  type="button"
                  class="hover:opacity-80 sm:hidden block absolute top-1/2 -translate-y-1/2 ltr:right-2 rtl:left-2"
                  @click="search = false">
                  <icon-x-circle />
                </button>
              </div>
            </form>

            <button
              type="button"
              class="search_btn sm:hidden p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:bg-white-light/90 dark:hover:bg-dark/60"
              @click="search = !search">
              <icon-search class="w-4.5 h-4.5 mx-auto dark:text-[#d0d2d6]" />
            </button>
          </div>
          <div>
            <Link
              :href="route('chat')"
              class="block p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:text-primary hover:bg-white-light/90 dark:hover:bg-dark/60">
              <icon-chat-notification />
            </Link>
          </div>

          <div class="dropdown shrink-0">
            <Popper
              :placement="store.rtlClass === 'rtl' ? 'bottom-end' : 'bottom-start'"
              offsetDistance="8">
              <button
                type="button"
                class="block p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:text-primary hover:bg-white-light/90 dark:hover:bg-dark/60">
                <img :src="currentFlag" alt="flag" class="w-5 h-5 object-cover rounded-full" />
              </button>
              <template #content="{ close }">
                <ul
                  class="!px-2 text-dark dark:text-white-dark grid grid-cols-2 gap-2 font-semibold dark:text-white-light/90 w-[320px]">
                  <template v-for="item in store.languageList" :key="item.code">
                    <li>
                      <Link
                        as="a"
                        :href="route('set-locale', item.code)"
                        class="w-full hover:text-primary"
                        :class="{ 'bg-primary/10 text-primary': i18n.locale === item.code }"
                        @click="changeLanguage(item), close()">
                        <img
                          class="w-5 h-5 object-cover rounded-full"
                          :src="`/assets/images/flags/${item.code.toUpperCase()}.svg`"
                          alt="" />
                        <span class="ltr:ml-3 rtl:mr-3">{{ item.name }}</span>
                      </Link>
                    </li>
                  </template>
                </ul>
              </template>
            </Popper>
          </div>

          <div class="dropdown shrink-0">
            <Popper
              :placement="store.rtlClass === 'rtl' ? 'bottom-start' : 'bottom-end'"
              offsetDistance="8">
              <button
                type="button"
                class="block p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:text-primary hover:bg-white-light/90 dark:hover:bg-dark/60">
                <icon-mail-dot />
              </button>
              <template #content="{ close }">
                <ul
                  class="top-11 !py-0 text-dark dark:text-white-dark w-[300px] sm:w-[375px] text-xs">
                  <li class="mb-5">
                    <div class="overflow-hidden relative rounded-t-md !p-5 text-white">
                      <div
                        class="absolute h-full w-full bg-[url('/assets/images/lightbox5.jpeg')] bg-no-repeat bg-center bg-cover inset-0"></div>
                      <h4 class="font-semibold relative z-10 text-lg">Messages</h4>
                    </div>
                  </li>
                  <template v-for="msg in messages" :key="msg.id">
                    <li>
                      <div class="flex items-center py-3 px-5">
                        <div v-html="msg.image"></div>
                        <span class="px-3 dark:text-gray-500">
                          <div
                            class="font-semibold text-sm dark:text-white-light/90"
                            v-text="msg.title"></div>
                          <div v-text="msg.message"></div>
                        </span>
                        <span
                          class="font-semibold bg-white-dark/20 rounded text-dark/60 px-1 ltr:ml-auto rtl:mr-auto whitespace-pre dark:text-white-dark ltr:mr-2 rtl:ml-2"
                          v-text="msg.time"></span>
                        <button
                          type="button"
                          class="text-neutral-300 hover:text-danger"
                          @click="removeMessage(msg.id)">
                          <icon-x-circle />
                        </button>
                      </div>
                    </li>
                  </template>
                  <template v-if="messages.length">
                    <li class="border-t border-white-light text-center dark:border-white/10 mt-5">
                      <div
                        class="flex items-center py-4 px-5 text-primary font-semibold group dark:text-gray-400 justify-center cursor-pointer"
                        @click="close()">
                        <span class="group-hover:underline ltr:mr-1 rtl:ml-1">
                          VIEW ALL ACTIVITIES
                        </span>

                        <icon-arrow-left
                          class="group-hover:translate-x-1 transition duration-300 ltr:ml-1 rtl:mr-1" />
                      </div>
                    </li>
                  </template>
                  <template v-if="!messages.length">
                    <li class="mb-5">
                      <div
                        class="!grid place-content-center hover:!bg-transparent text-lg min-h-[200px]">
                        <div class="mx-auto ring-4 ring-primary/30 rounded-full mb-4 text-primary">
                          <icon-info-circle :fill="true" class="w-10 h-10" />
                        </div>
                        No data available.
                      </div>
                    </li>
                  </template>
                </ul>
              </template>
            </Popper>
          </div>

          <div class="dropdown shrink-0">
            <Popper
              :placement="store.rtlClass === 'rtl' ? 'bottom-end' : 'bottom-start'"
              offsetDistance="8">
              <button
                type="button"
                class="relative block p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:text-primary hover:bg-white-light/90 dark:hover:bg-dark/60">
                <icon-bell-bing />

                <span class="flex absolute w-3 h-3 ltr:right-0 rtl:left-0 top-0">
                  <span
                    class="animate-ping absolute ltr:-left-[3px] rtl:-right-[3px] -top-[3px] inline-flex h-full w-full rounded-full bg-success/50 opacity-75"></span>
                  <span class="relative inline-flex rounded-full w-[6px] h-[6px] bg-success"></span>
                </span>
              </button>
              <template #content="{ close }">
                <ul
                  class="!py-0 text-dark dark:text-white-dark w-[300px] sm:w-[350px] divide-y dark:divide-white/10">
                  <li>
                    <div class="flex items-center px-4 py-2 justify-between font-semibold">
                      <h4 class="text-lg">Notification</h4>
                      <template v-if="notifications.length">
                        <span
                          class="badge bg-primary/80"
                          v-text="notifications.length + 'New'"></span>
                      </template>
                    </div>
                  </li>
                  <template v-for="notification in notifications" :key="notification.id">
                    <li class="dark:text-white-light/90">
                      <div class="group flex items-center px-4 py-2">
                        <div class="grid place-content-center rounded">
                          <div class="w-12 h-12 relative">
                            <img
                              class="w-12 h-12 rounded-full object-cover"
                              :src="`/assets/images/${notification.profile}`"
                              alt="" />
                            <span
                              class="bg-success w-2 h-2 rounded-full block absolute right-[6px] bottom-0"></span>
                          </div>
                        </div>
                        <div class="ltr:pl-3 rtl:pr-3 flex flex-auto">
                          <div class="ltr:pr-3 rtl:pl-3">
                            <h6 v-html="notification.message"></h6>
                            <span
                              class="text-xs block font-normal dark:text-gray-500"
                              v-text="notification.time"></span>
                          </div>
                          <button
                            type="button"
                            class="ltr:ml-auto rtl:mr-auto text-neutral-300 hover:text-danger opacity-0 group-hover:opacity-100"
                            @click="removeNotification(notification.id)">
                            <icon-x-circle />
                          </button>
                        </div>
                      </div>
                    </li>
                  </template>
                  <template v-if="notifications.length">
                    <li>
                      <div class="p-4">
                        <button class="btn btn-primary block w-full btn-small" @click="close()">
                          Read All Notifications
                        </button>
                      </div>
                    </li>
                  </template>
                  <template v-if="!notifications.length">
                    <li>
                      <div
                        class="!grid place-content-center hover:!bg-transparent text-lg min-h-[200px]">
                        <div class="mx-auto ring-4 ring-primary/30 rounded-full mb-4 text-primary">
                          <icon-info-circle :fill="true" class="w-10 h-10" />
                        </div>
                        No data available.
                      </div>
                    </li>
                  </template>
                </ul>
              </template>
            </Popper>
          </div>

          <div class="dropdown shrink-0">
            <Popper
              :placement="store.rtlClass === 'rtl' ? 'bottom-end' : 'bottom-start'"
              offsetDistance="8"
              class="!block">
              <button type="button" class="relative group block">
                <span
                  class="flex justify-center items-center w-9 h-9 text-center rounded-full object-cover bg-danger text-base">
                  {{ $page.props.auth.user.name.charAt(0) + $page.props.auth.user.name.charAt(1) }}
                </span>
              </button>
              <template #content="{ close }">
                <ul
                  class="text-dark dark:text-white-dark !py-0 w-[230px] font-semibold dark:text-white-light/90">
                  <li>
                    <div class="flex items-center px-4 py-4">
                      <div class="flex-none">
                        <span
                          class="flex justify-center items-center w-10 h-10 text-center rounded-full object-cover bg-danger text-base">
                          {{
                            $page.props.auth.user.name.charAt(0) +
                            $page.props.auth.user.name.charAt(1)
                          }}
                        </span>
                      </div>
                      <div class="ltr:pl-4 rtl:pr-4 truncate">
                        <h4 class="text-base">{{ $page.props.auth.user.name }}</h4>
                        <a
                          class="text-black/60 hover:text-primary dark:text-dark-light/60 dark:hover:text-white"
                          href="javascript:;"
                          >{{ $page.props.auth.user.email }}</a
                        >
                      </div>
                    </div>
                  </li>
                  <li>
                    <Link
                      :href="route('profile.edit')"
                      class="dark:hover:text-white"
                      @click="close()">
                      <icon-user class="w-4.5 h-4.5 ltr:mr-2 rtl:ml-2 shrink-0" />
                      Profile
                    </Link>
                  </li>
                  <li class="border-t border-white-light dark:border-white-light/10">
                    <Link
                      :href="route('logout')"
                      method="post"
                      as="button"
                      class="text-danger !py-3"
                      @click="close()">
                      <icon-logout class="w-4.5 h-4.5 ltr:mr-2 rtl:ml-2 rotate-90 shrink-0" />
                      Sign Out
                    </Link>
                  </li>
                </ul>
              </template>
            </Popper>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script lang="ts" setup>
import { ref, onMounted, computed, reactive } from 'vue';
import { useI18n } from 'vue-i18n';

import appSetting from '@/app-setting';

import { useAppStore } from '@/stores/index';

import IconMenu from '@/Components/icon/icon-menu.vue';
import IconChatNotification from '@/Components/icon/icon-chat-notification.vue';
import IconSearch from '@/Components/icon/icon-search.vue';
import IconXCircle from '@/Components/icon/icon-x-circle.vue';
import IconMailDot from '@/Components/icon/icon-mail-dot.vue';
import IconArrowLeft from '@/Components/icon/icon-arrow-left.vue';
import IconInfoCircle from '@/Components/icon/icon-info-circle.vue';
import IconBellBing from '@/Components/icon/icon-bell-bing.vue';
import IconUser from '@/Components/icon/icon-user.vue';
import IconLogout from '@/Components/icon/icon-logout.vue';
import { Link } from '@inertiajs/vue3';

const store = useAppStore();
const search = ref(false);
// multi language
const i18n = reactive(useI18n());
const changeLanguage = (item: any) => {
  i18n.locale = item.code;
  appSetting.toggleLanguage(item);
};
const currentFlag = computed(() => {
  return `/assets/images/flags/${i18n.locale.toUpperCase()}.svg`;
});

const notifications = ref([
  {
    id: 1,
    profile: 'user-profile.jpeg',
    message: 'test',
    time: '45 min ago'
  },
  {
    id: 2,
    profile: 'profile-34.jpeg',
    message: 'test',
    time: '9h Ago'
  },
  {
    id: 3,
    profile: 'profile-16.jpeg',
    message: 'test',
    time: '9h Ago'
  }
]);

const messages = ref([
  {
    id: 1,
    image:
      '<span class="grid place-content-center w-9 h-9 rounded-full bg-success-light dark:bg-success text-success dark:text-success-light"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg></span>',
    title: 'Congratulations!',
    message: 'Your OS has been updated.',
    time: '1hr'
  },
  {
    id: 2,
    image:
      '<span class="grid place-content-center w-9 h-9 rounded-full bg-info-light dark:bg-info text-info dark:text-info-light"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg></span>',
    title: 'Did you know?',
    message: 'You can switch between artboards.',
    time: '2hr'
  },
  {
    id: 3,
    image:
      '<span class="grid place-content-center w-9 h-9 rounded-full bg-danger-light dark:bg-danger text-danger dark:text-danger-light"> <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></span>',
    title: 'Something went wrong!',
    message: 'Send Reposrt',
    time: '2days'
  },
  {
    id: 4,
    image:
      '<span class="grid place-content-center w-9 h-9 rounded-full bg-warning-light dark:bg-warning text-warning dark:text-warning-light"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">    <circle cx="12" cy="12" r="10"></circle>    <line x1="12" y1="8" x2="12" y2="12"></line>    <line x1="12" y1="16" x2="12.01" y2="16"></line></svg></span>',
    title: 'Warning',
    message: 'Your password strength is low.',
    time: '5days'
  }
]);

onMounted(() => {
  setActiveDropdown();
});

const setActiveDropdown = () => {
  const selector = document.querySelector(
    'ul.horizontal-menu a[href="' + window.location.pathname + '"]'
  );
  if (selector) {
    selector.classList.add('active');
    const all: any = document.querySelectorAll('ul.horizontal-menu .nav-link.active');
    for (let i = 0; i < all.length; i++) {
      all[0]?.classList.remove('active');
    }
    const ul: any = selector.closest('ul.sub-menu');
    if (ul) {
      let ele: any = ul.closest('li.menu').querySelectorAll('.nav-link');
      if (ele) {
        ele = ele[0];
        setTimeout(() => {
          ele?.classList.add('active');
        });
      }
    }
  }
};

const removeNotification = (value: number) => {
  notifications.value = notifications.value.filter((d) => d.id !== value);
};

const removeMessage = (value: number) => {
  messages.value = messages.value.filter((d) => d.id !== value);
};
</script>
