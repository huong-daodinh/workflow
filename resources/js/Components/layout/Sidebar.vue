<template>
  <div :class="{ 'dark text-white-dark': store.semidark }">
    <nav
      class="sidebar fixed min-h-screen h-full top-0 bottom-0 w-[260px] shadow-[5px_0_25px_0_rgba(94,92,154,0.1)] z-50 transition-all duration-300">
      <div class="bg-white dark:bg-[#0e1726] h-full">
        <div class="flex justify-between items-center px-4 py-3">
          <a to="/" class="main-logo flex items-center shrink-0">
            <img
              class="w-8 ml-[5px] flex-none"
              src="/assets/favicon_io/apple-touch-icon.png"
              alt="" />
            <span
              class="text-2xl ltr:ml-1.5 rtl:mr-1.5 font-semibold align-middle lg:inline dark:text-white-light">
              WORKFLOW
            </span>
          </a>
          <button
            class="collapse-icon w-8 h-8 rounded-full flex items-center hover:bg-gray-500/10 dark:hover:bg-dark-light/10 dark:text-white-light transition duration-300 rtl:rotate-180 hover:text-primary"
            @click="store.toggleSidebar()">
            <icon-carets-down class="m-auto rotate-90" />
          </button>
        </div>
        <div class="h-[calc(100vh-80px)] relative overflow-y-auto">
          <ul class="relative font-semibold space-y-0.5 p-4 py-0">
            <li class="menu nav-item">
              <button
                type="button"
                class="nav-link group w-full"
                :class="{ active: activeDropdown === 'dashboard' }"
                @click="
                  activeDropdown === 'dashboard'
                    ? (activeDropdown = null)
                    : (activeDropdown = 'dashboard')
                ">
                <div class="flex items-center">
                  <icon-menu-dashboard class="group-hover:!text-primary shrink-0" />
                  <span
                    class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">
                    {{ $t('dashboard') }}
                  </span>
                </div>
                <div :class="{ 'rtl:rotate-90 -rotate-90': activeDropdown !== 'dashboard' }">
                  <icon-caret-down />
                </div>
              </button>
              <vue-collapsible :isOpen="activeDropdown === 'dashboard'">
                <ul class="sub-menu text-gray-500">
                  <li class="hover:bg-gray-100 rounded">
                    <Link :href="route('dashboard')" @click="toggleMobileMenu">{{
                      $t('home')
                    }}</Link>
                  </li>
                  <!-- <li class="hover:bg-gray-100 rounded">
                    <Link :href="route('analyze')" @click="toggleMobileMenu">{{
                      $t('analytics')
                    }}</Link>
                  </li> -->
                </ul>
              </vue-collapsible>
            </li>
            <li class="menu nav-item">
              <Link
                :href="route('project.index')"
                :class="{ active: isUrl('project') }"
                class="nav-link group w-full"
                @click="toggleMobileMenu">
                <div class="flex items-center">
                  <icon-menu-documentation fill="true" class="group-hover:!text-primary shrink-0" />
                  <span
                    class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">
                    {{ $t('project') }}
                  </span>
                </div>
              </Link>
            </li>

            <li class="menu nav-item">
              <Link
                :href="route('timesheet.index')"
                :class="{ active: isUrl('timesheet') }"
                class="nav-link group w-full"
                @click="toggleMobileMenu">
                <div class="flex items-center">
                  <IconCircleCheck fill="true" class="group-hover:!text-primary shrink-0" />
                  <span
                    class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">
                    {{ $t('timesheet') }}
                  </span>
                </div>
              </Link>
            </li>

            <li class="menu nav-item">
              <!-- <Link
                :href="route('team.index')"
                :class="{ active: isUrl('team') }"
                class="nav-link group w-full"
                @click="toggleMobileMenu">
                <div class="flex items-center">
                  <icon-menu-users class="group-hover:!text-primary shrink-0" />
                  <span
                    class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">
                    {{ $t('team') }}
                  </span>
                </div>
              </Link> -->
            </li>

            <h2
              class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1">
              <icon-minus class="w-4 h-5 flex-none hidden" />
              <span>{{ $t('system') }}</span>
            </h2>
            <li class="menu nav-item">
              <button type="button" class="nav-link group w-full">
                <div class="flex items-center">
                  <icon-menu-apps class="group-hover:!text-primary shrink-0" />
                  <span
                    class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">
                    {{ $t('setting') }}
                  </span>
                </div>
              </button>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </div>
</template>

<script lang="ts" setup>
import { ref, onMounted, watch } from 'vue';
import { useAppStore } from '@/stores/index';
import VueCollapsible from 'vue-height-collapsible/vue3';
import IconCaretsDown from '@/Components/icon/icon-carets-down.vue';
import IconMenuDashboard from '@/Components/icon/menu/icon-menu-dashboard.vue';
import IconMinus from '@/Components/icon/icon-minus.vue';
import IconCaretDown from '@/Components/icon/icon-caret-down.vue';
import IconMenuApps from '../icon/menu/icon-menu-apps.vue';
import IconMenuUsers from '../icon/menu/icon-menu-users.vue';
import IconMenuDocumentation from '../icon/menu/icon-menu-documentation.vue';
import IconCircleCheck from '../icon/icon-circle-check.vue';

import { Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { showMessage } from '@/functions/alert';
import i18n from '@/i18n';

const page = usePage();
const store = useAppStore();
const activeDropdown: any = ref('');

onMounted(() => {
  const selector = document.querySelector('.sidebar ul a[href="' + window.location.href + '"]');

  if (selector) {
    selector.classList.add('active');
    const ul: any = selector.closest('ul.sub-menu');
    if (ul) {
      let ele: any = ul.closest('li.menu').querySelectorAll('.nav-link') || [];
      if (ele.length) {
        ele = ele[0];
        setTimeout(() => {
          ele.click();
        });
      }
    }
  }
});

const toggleMobileMenu = () => {
  if (window.innerWidth < 1024) {
    store.toggleSidebar();
  }
};

const isUrl = (...urls) => {
  let currentUrl = page.url.substring(1);
  if (urls[0] === '') {
    return currentUrl === '';
  }
  return urls.filter((url) => currentUrl.startsWith(url)).length;
};

watch(
  () => page.props.flash,
  () => {
    showMessage(page.props.flash.message, page.props.flash.type);
  }
);
</script>
