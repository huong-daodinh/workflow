<template>
  <AuthenticatedLayout>
    <Head title="Timesheet" />
    <div>
      <div class="panel">
        <div class="mb-5">
          <div
            class="mb-4 flex items-center sm:flex-row flex-col sm:justify-between justify-center">
            <div class="sm:mb-0 mb-4">
              <div class="text-lg font-semibold ltr:sm:text-left rtl:sm:text-right text-center">
                Calendar
              </div>
              <div class="flex items-center mt-2 flex-wrap sm:justify-start justify-center">
                <div class="flex items-center ltr:mr-4 rtl:ml-4">
                  <div class="h-2.5 w-2.5 rounded-sm ltr:mr-2 rtl:ml-2 bg-success"></div>
                  <div>On time</div>
                </div>
                <div class="flex items-center">
                  <div class="h-2.5 w-2.5 rounded-sm ltr:mr-2 rtl:ml-2 bg-danger"></div>
                  <div>Late</div>
                </div>
              </div>
            </div>
            <button type="button" class="btn btn-primary" @click="editEvent()">
              <icon-plus class="ltr:mr-2 rtl:ml-2" />
              Check in / out
            </button>
          </div>
          <div class="calendar-wrapper">
            <FullCalendar ref="calendar" :options="calendarOptions">
              <template v-slot:eventContent="arg">
                <div class="fc-event-main-frame flex items-center px-1 py-0.5 text-white">
                  <div class="fc-event-time font-semibold px-0.5">
                    {{ arg.timeText }}
                  </div>
                  <div class="fc-event-title-container">
                    <div class="fc-event-title fc-sticky !font-medium px-0.5">
                      {{ arg.event.title }}
                    </div>
                  </div>
                </div>
              </template>
            </FullCalendar>
          </div>
        </div>
      </div>
      <TransitionRoot appear :show="isAddEventModal" as="template">
        <Dialog as="div" @close="isAddEventModal = false" class="relative z-[51]">
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
            <div class="flex min-h-full items-center justify-center px-4 py-8">
              <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0 scale-95"
                enter-to="opacity-100 scale-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100 scale-100"
                leave-to="opacity-0 scale-95">
                <DialogPanel
                  class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg text-black dark:text-white-dark">
                  <button
                    type="button"
                    class="absolute top-4 ltr:right-4 rtl:left-4 text-gray-400 hover:text-gray-800 dark:hover:text-gray-600 outline-none"
                    @click="isAddEventModal = false">
                    <icon-x />
                  </button>
                  <div
                    class="text-lg font-medium bg-[#fbfbfb] dark:bg-[#121c2c] ltr:pl-5 rtl:pr-5 py-3 ltr:pr-[50px] rtl:pl-[50px]">
                    {{ params.id ? 'Cập nhật Timesheet' : 'Tạo Timesheet' }}
                  </div>
                  <div class="p-5">
                    <form @submit.prevent="saveEvent">
                      <div class="mb-5">
                        <label for="dateStart">From :</label>
                        <input
                          id="dateStart"
                          type="datetime-local"
                          name="start"
                          class="form-input"
                          placeholder="Event Start Date"
                          v-model="params.start" />
                        <div class="text-danger mt-2" id="startDateErr"></div>
                      </div>
                      <div class="mb-5">
                        <label for="dateEnd">To :</label>
                        <input
                          id="dateEnd"
                          type="datetime-local"
                          name="end"
                          class="form-input"
                          placeholder="Event End Date"
                          v-model="params.end" />
                        <div class="text-danger mt-2" id="endDateErr"></div>
                      </div>

                      <div class="flex justify-end items-center mt-8">
                        <button
                          type="button"
                          class="btn btn-outline-danger"
                          @click="isAddEventModal = false">
                          Cancel
                        </button>
                        <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">
                          {{ params.id ? 'Cập nhật' : 'Tạo' }}
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

      <!-- check in modal -->
      <TransitionRoot appear :show="isCheckInModal" as="template">
        <Dialog as="div" @close="isCheckInModal = false" class="relative z-[51]">
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
            <div class="flex min-h-full items-start justify-center px-4 py-8">
              <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0 scale-95"
                enter-to="opacity-100 scale-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100 scale-100"
                leave-to="opacity-0 scale-95">
                <DialogPanel
                  class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg text-black dark:text-white-dark">
                  <button
                    type="button"
                    class="absolute top-4 ltr:right-4 rtl:left-4 text-gray-400 hover:text-gray-800 dark:hover:text-gray-600 outline-none"
                    @click="isCheckInModal = false">
                    <icon-x />
                  </button>
                  <div
                    class="text-lg font-bold bg-[#fbfbfb] dark:bg-[#121c2c] ltr:pl-5 rtl:pr-5 py-3 ltr:pr-[50px] rtl:pl-[50px]">
                    Thực hiện chấm công
                  </div>
                  <div class="p-5">
                    <p>Bạn có muốn chấm công lúc này?</p>

                    <div class="flex justify-end items-center mt-8">
                      <button
                        type="button"
                        @click="isCheckInModal = false"
                        class="btn btn-outline-danger">
                        Cancel
                      </button>
                      <button
                        type="button"
                        @click="saveEvent()"
                        class="btn btn-primary ltr:ml-4 rtl:mr-4">
                        Ok
                      </button>
                    </div>
                  </div>
                </DialogPanel>
              </TransitionChild>
            </div>
          </div>
        </Dialog>
      </TransitionRoot>
    </div>
  </AuthenticatedLayout>
</template>
<script lang="ts" setup>
import { computed, onMounted, reactive, ref } from 'vue';
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogOverlay
} from '@headlessui/vue';
import '@fullcalendar/core/vdom';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import Swal from 'sweetalert2';
import { Head, useForm } from '@inertiajs/vue3';
import IconPlus from '@/Components/icon/icon-plus.vue';
import IconX from '@/Components/icon/icon-x.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useI18n } from 'vue-i18n';
import vnLocale from '@fullcalendar/core/locales/vi';
import enLocale from '@fullcalendar/core/locales/en-gb';
import { defineProps } from 'vue';
import { timesheet } from '@/interfaces/index.interfaces';

const localeMatch = {
  vi: vnLocale,
  en: enLocale
};

const props = defineProps({
  timesheets: {
    type: Array<timesheet>,
    required: true
  },
  user_id: {
    type: Number,
    required: true
  }
});

function isCheckInTimeOk(checkInTime: string) {
  const checkInHourThreshold = 9;
  const checkInHour = new Date(checkInTime).getHours();
  const checkInMinute = new Date(checkInTime).getMinutes();
  if (checkInHour < checkInHourThreshold) {
    return true;
  }
  return checkInMinute <= 0;
}

function isCheckOutTimeOk(checkOutTime: string) {
  const checkOutHourThreshold = 17;
  const checkOutMinimumHourThreshold = 30;
  const checkOutHour = new Date(checkOutTime).getHours();
  const checkOutMinute = new Date(checkOutTime).getMinutes();
  if (checkOutHour > checkOutHourThreshold) {
    return true;
  }
  if (checkOutHour == checkOutHourThreshold && checkOutMinute >= checkOutMinimumHourThreshold) {
    return true;
  }
  return false;
}

function fetchTimesheetToCalendar(timesheetData: Array<timesheet>) {
  return [
    ...(timesheetData.map((item) => {
      const isCheckInOk = isCheckInTimeOk(item.date + ' ' + item.check_in_time);
      return {
        title: item.check_in_time + (isCheckInOk ? ' Ok' : ' Late'),
        start: item.date,
        className: isCheckInOk ? 'bg-green-500' : 'bg-red-500'
      };
    }) || []),
    ...(
      timesheetData.map((item) => {
        if (item.check_out_time !== null) {
          const isCheckOutOk = isCheckOutTimeOk(item.date + ' ' + item.check_out_time);
          return {
            title: item.check_out_time + (isCheckOutOk ? ' Ok' : ' Left early'),
            start: item.date,
            className: isCheckOutOk ? 'bg-green-500' : 'bg-red-500'
          };
        }
        return null;
      }) || []
    ).filter((item) => item !== null)
  ];
}

const axios = window.axios;
const timesheetForm = useForm({
  date: String,
  check_in_time: String,
  check_out_time: String,
  user_id: Number
});

const i18n = reactive(useI18n());
const params = ref({
  id: null,
  title: '',
  start: '',
  end: '',
  description: '',
  type: 'primary'
});
const isAddEventModal = ref(false);
const isCheckInModal = ref(false);
const calendar: any = ref(null);
const now = new Date();
const events: any = ref([]);
const timesheetCheck = ref({
  check_in: false,
  check_out: false
});

const filter = ref({
  month: now.getMonth() + 1,
  year: now.getFullYear()
});

const calendarOptions = computed(() => {
  return {
    plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
    initialView: 'dayGridMonth',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: ''
    },
    locale: localeMatch[i18n.locale],
    showNonCurrentDates: true,
    fixedWeekCount: false,
    contentHeight: 800,
    businessHours: {
      daysOfWeek: [1, 2, 3, 4, 5],
      startTime: '8:30',
      endTime: '17:30'
    },
    events: events.value
  };
});

const getMonth = (dt: Date, add: number = 0) => {
  let month = dt.getMonth() + 1 + add;
  const str = (month < 10 ? '0' + month : month).toString();
  return str;
};

const editEvent = () => {
  const startDate = new Date();
  const currMinutes = startDate.getMinutes();
  const currHours = startDate.getHours();
  const checkInHours = 8;
  const checkInMinute = 30;
  params.value = {
    id: null,
    title:
      currHours > checkInHours
        ? 'Late'
        : checkInHours == currHours && currMinutes > checkInMinute + 5
        ? 'Late'
        : 'Ok',
    start: dateFormat(startDate),
    end: dateFormat(startDate),
    type:
      currHours > checkInHours
        ? 'danger'
        : checkInHours == currHours && currMinutes > checkInMinute + 5
        ? 'danger'
        : 'success',
    description: ''
  };
  //   saveEvent();
  isCheckInModal.value = true;
};

const dateFormat = (dt: any) => {
  dt = new Date(dt);
  const month = dt.getMonth() + 1 < 10 ? '0' + (dt.getMonth() + 1) : dt.getMonth() + 1;
  const date = dt.getDate() < 10 ? '0' + dt.getDate() : dt.getDate();
  const hours = dt.getHours() < 10 ? '0' + dt.getHours() : dt.getHours();
  const mins = dt.getMinutes() < 10 ? '0' + dt.getMinutes() : dt.getMinutes();
  dt = dt.getFullYear() + '-' + month + '-' + date + 'T' + hours + ':' + mins;
  return dt;
};

const saveEvent = () => {
  if (!params.value.title) {
    return true;
  }
  if (!params.value.start) {
    return true;
  }
  if (!params.value.end) {
    return true;
  }

  let maxEventId = 0;
  if (events.value) {
    maxEventId = events.value.reduce(
      (max: number, character: any) => (character.id > max ? character.id : max),
      events.value[0].id
    );
  }

  let event = {
    id: maxEventId + 1,
    title: params.value.title,
    start: params.value.start,
    end: params.value.end,
    description: params.value.description,
    className: params.value.type
  };
  if (!timesheetCheck.value.check_in) {
    timesheetCheck.value.check_in = true;
    events.value.push(event);
    showMessage('Chấm công thành công');
  } else if (!timesheetCheck.value.check_out) {
    timesheetCheck.value.check_out = true;
    events.value.push(event);
    showMessage('Chấm công thành công');
  } else if (timesheetCheck.value.check_in && timesheetCheck.value.check_out) {
    showMessage('Không thể chấm công lúc này', 'error');
  }
  calendar.value.getApi(); //refresh Calendar
  isCheckInModal.value = false;
};

onMounted(() => {
  // calendarOptions.value.events = fetchTimesheetToCalendar(props.timesheets);
  events.value = fetchTimesheetToCalendar(props.timesheets);
});

const showMessage = (msg = '', type = 'success') => {
  const toast: any = Swal.mixin({
    toast: true,
    position: 'top',
    showConfirmButton: false,
    timer: 3000,
    customClass: { container: 'toast' }
  });
  toast.fire({
    icon: type,
    title: msg,
    padding: '10px 20px'
  });
};
</script>
