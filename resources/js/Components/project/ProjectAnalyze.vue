<template>
  <div v-if="totalTasks">
    <div class="pt-5 grid grid-cols-1 gap-6">
      <!-- Tasks by status -->
      <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-6 mb-6 text-white">
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
      <div class="grid grid-cols-1 gap-6">
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
                  <th class="text-center ltr:rounded-r-md rtl:rounded-l-md">EXECUTOR</th>
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
                  <td class="">
                    <user-info :user-data="task.assignee" />
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
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
</template>

<script lang="ts" setup>
import { ref, defineProps, onMounted } from 'vue';

const tasksByStatus = ref<any>(null);
const recentTasks = ref<any>();
const totalTasks = ref<number>(0);
const props = defineProps({
  project: {
    type: Number,
    required: true
  }
});

onMounted(async () => {
  await axios.get(`/project/analyze/${props.project}`).then((response: any) => {
    tasksByStatus.value = response.data.tasksByStatus;
    recentTasks.value = response.data.recentTasks;
    totalTasks.value = response.data.totalTasks;
  });
});
</script>
