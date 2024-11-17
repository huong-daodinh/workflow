<template>
  <AuthenticatedLayout>
    <Head title="Analyze" />
    <div>
      <ul class="flex space-x-2 rtl:space-x-reverse">
        <li>
          <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
        </li>
        <li class="before:content-['/'] before:mr-2 rtl:before:ml-2">
          <span>Analytics</span>
        </li>
      </ul>
      <div class="pt-5 grid grid-cols-1 gap-6">
        <!-- Tasks by status -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-6 text-white">
          <div class="panel bg-gradient-to-r from-primary to-secondary">
            <div class="flex justify-between">
              <div class="ltr:mr-1 rtl:ml-1 text-md font-semibold">Total</div>
            </div>
            <div class="flex items-center mt-5">
              <div class="text-3xl font-bold ltr:mr-3 rtl:ml-3">{{ totalTasks }}</div>
              <div class="badge bg-white/30">100 %</div>
            </div>
          </div>
          <div
            class="panel bg-gradient-to-r"
            :class="{
              [`from-red-500 to-red-400`]: task.color === 'red',
              [`from-sky-500 to-sky-400`]: task.color === 'sky',
              [`from-blue-500 to-blue-400`]: task.color === 'blue',
              [`from-green-500 to-green-400`]: task.color === 'green',
              [`from-yellow-500 to-yellow-400`]: task.color === 'yellow'
            }"
            v-for="(task, index) in tasksByStatus"
            :key="index">
            <div class="flex justify-between">
              <div class="ltr:mr-1 rtl:ml-1 text-md font-semibold">{{ $t(`${task.slug}`) }}</div>
            </div>
            <div class="flex items-center mt-5">
              <div class="text-3xl font-bold ltr:mr-3 rtl:ml-3">{{ task.count }}</div>
              <div class="badge bg-white/30">{{ task.rate }} %</div>
            </div>
            <div class="flex items-center font-semibold mt-5">
              <icon-eye class="ltr:mr-2 rtl:ml-2 shrink-0" />
            </div>
          </div>
        </div>
        <!-- Recent Tasks -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <div class="panel">
            <div class="mb-5 text-lg font-bold">Recent Tasks</div>
            <div class="table-responsive">
              <table>
                <thead>
                  <tr>
                    <th class="ltr:rounded-l-md rtl:rounded-r-md">ID</th>
                    <th>DEADLINE</th>
                    <th>TITLE</th>
                    <th class="text-center ltr:rounded-r-md rtl:rounded-l-md">ASSIGNED BY</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(task, index) in recentTasks" :key="index">
                    <td class="font-semibold">{{ task.id }}</td>
                    <td class="whitespace-nowrap">
                      {{ new Date(task.due_date).toLocaleDateString() }}
                    </td>
                    <td class="whitespace-nowrap">{{ task.title }}</td>
                    <td class="">
                      <user-info :user-data="task.assigner" />
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="panel"></div>
        </div>
        <!-- task rate -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <div class="panel bg-gradient-to-r from-green-500 to-green-400 text-white">
            <div class="flex items-center font-semibold mb-5">
              <div class="ltr:ml-2 rtl:mr-2">
                <h6 class="text-white-light">Rate</h6>
                <p class="text-white text-xl">Task Done</p>
              </div>
            </div>
            <div class="font-bold text-4xl">
              {{ tasksByStatus.done.rate }}% ({{ tasksByStatus.done.count }})
            </div>
          </div>

          <div class="panel bg-gradient-to-r from-red-500 to-red-400 text-white">
            <div class="flex items-center font-semibold mb-5">
              <div class="ltr:ml-2 rtl:mr-2">
                <h6 class="text-white-light">Rate</h6>
                <p class="text-white text-xl">Overdue Task</p>
              </div>
            </div>
            <div class="font-bold text-4xl">
              {{ tasksByStatus.overdue.rate }}% ({{ tasksByStatus.overdue.count }})
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
<script lang="ts" setup>
import { ref, computed, defineProps } from 'vue';
import apexchart from 'vue3-apexcharts';
import { useAppStore } from '@/stores/index';
import { Head } from '@inertiajs/vue3';
import UserInfo from '@/Components/UserInfo.vue';
import IconChrome from '@/Components/icon/icon-chrome.vue';
import IconSafari from '@/Components/icon/icon-safari.vue';
import IconGlobe from '@/Components/icon/icon-globe.vue';
import IconChatDots from '@/Components/icon/icon-chat-dots.vue';
import IconEye from '@/Components/icon/icon-eye.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
const store = useAppStore();

defineProps({
  tasksByStatus: {} as any,
  recentTasks: [] as Array<any>,
  totalTasks: Number
});
</script>
