<template>
  <AuthenticatedLayout>
    <Head title="Department" />
    <div class="flex items-center justify-between flex-wrap gap-4">
      <h2 class="text-xl">{{ $t('department') }}</h2>
      <div class="flex sm:flex-row flex-col sm:items-center sm:gap-3 gap-4 w-full sm:w-auto">
        <div class="flex gap-3">
          <button type="button" class="btn btn-primary" @click="addEditDepartment()">
            <icon-user-plus class="ltr:mr-2 rtl:ml-2" />
            Tạo phòng ban
          </button>
        </div>
      </div>
    </div>
    <div class="mt-5 panel p-0 border-0 overflow-hidden">
      <div class="table-responsive">
        <table class="table-striped table-hover">
          <thead>
            <tr>
              <th>Tên</th>
              <th>Ngày tạo</th>
              <th>Nhân viên</th>
              <th class="!text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(department, index) in departments" :key="index">
              <td>{{ department.name }}</td>
              <td class="whitespace-nowrap">
                <span class="badge bg-secondary">
                  {{ convertToCustomFormat(department.created_at) }}
                </span>
              </td>
              <td>
                <div
                  class="flex items-center justify-start -space-x-2 rtl:space-x-reverse text-white">
                  <span v-for="(employee, index) in department.employees" :key="index">
                    <img
                      v-if="employee.avatar"
                      v-tippy
                      class="w-8 h-8 rounded-full object-cover ring-2 ring-white dark:ring-white-dark"
                      :src="employee.avatar"
                      alt="avatar" />
                    <span
                      v-tippy
                      v-if="!employee.avatar"
                      class="flex justify-center items-center w-8 h-8 text-center rounded-full object-cover bg-info text-base ring-2 ring-white dark:ring-white-dark">
                      {{ takeSummaryName(employee.name) }}
                    </span>
                    <tippy>{{ employee.name }}</tippy>
                  </span>
                </div>
              </td>
              <td>
                <div class="flex gap-4 items-center justify-center">
                  <button
                    type="button"
                    class="btn btn-sm btn-outline-primary"
                    @click="addEditDepartment(department)">
                    Sửa
                  </button>
                  <button
                    type="button"
                    v-if="department.can_delete"
                    class="btn btn-sm btn-outline-danger"
                    @click="deleteDepartment(department)">
                    Xoá
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <TransitionRoot appear :show="addDepartmentModal" as="template">
      <Dialog as="div" @close="closeModal()" class="relative z-[51]">
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
                class="panel border-0 p-0 rounded-lg w-full max-w-lg text-black dark:text-white-dark">
                <button
                  type="button"
                  class="absolute top-4 ltr:right-4 rtl:left-4 text-gray-400 hover:text-gray-800 dark:hover:text-gray-600 outline-none"
                  @click="closeModal()">
                  <icon-x />
                </button>
                <div
                  class="text-lg font-medium bg-[#fbfbfb] dark:bg-[#121c2c] ltr:pl-5 rtl:pr-5 py-3 ltr:pr-[50px] rtl:pl-[50px]">
                  {{ departmentForm.id ? 'Chỉnh sửa thông tin' : 'Thêm mới' }}
                </div>
                <div class="p-5">
                  <form @submit.prevent="saveUser">
                    <div class="mb-5">
                      <label for="name">Tên</label>
                      <input
                        id="name"
                        type="text"
                        placeholder="Enter Name"
                        class="form-input"
                        v-model="departmentForm.name" />
                      <input-error :message="departmentForm.errors.name" />
                    </div>
                    <div class="mb-5">
                      <div class="mb-4">
                        <label for="">{{ $t('members') }}</label>
                        <div class="relative">
                          <span
                            class="absolute ltr:left-3 rtl:right-3 top-1/2 -translate-y-1/2 dark:text-white-dark">
                            <icon-users />
                          </span>
                          <multiselect
                            v-model="employeeSelected"
                            :options="employeeOptions"
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
                        <input-error :message="departmentForm.errors.employees" />
                      </div>
                    </div>
                    <div class="flex justify-end items-center mt-8">
                      <button type="button" class="btn btn-outline-danger" @click="closeModal">
                        Cancel
                      </button>
                      <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Lưu</button>
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
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogOverlay
} from '@headlessui/vue';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import IconUserPlus from '@/Components/icon/icon-user-plus.vue';
import IconX from '@/Components/icon/icon-x.vue';
import { ref, defineProps, onMounted } from 'vue';
import { department, user } from '@/interfaces/index.interfaces';
import InputError from '@/Components/InputError.vue';
import Multiselect from '@suadelabs/vue3-multiselect';
import '@suadelabs/vue3-multiselect/dist/vue3-multiselect.css';

const props = defineProps({
  departments: {
    type: Array as () => Array<department>,
    required: true
  },
  employees: {
    type: Array as () => Array<user>,
    required: true
  }
});

const closeModal = () => {
  addDepartmentModal.value = false;
  employeeOptions.value = employeesProp.value;
  departmentForm.reset();
  departmentForm.clearErrors();
};
const employeeSelected = ref<Array<user>>([]);
const employeeOptions = ref<Array<user>>([]);
const employeesProp = ref<Array<user>>([]);

const addDepartmentModal = ref(false);
const departmentForm = useForm({
  id: 0,
  name: '',
  employees: [] as Array<number>
});
const saveUser = () => {
  departmentForm.employees = [];
  employeeSelected.value.forEach((employee) => {
    departmentForm.employees.push(employee.id);
  });
  if (!departmentForm.id) {
    departmentForm.post(route('department.store'), {
      preserveScroll: true,
      onSuccess: () => {
        closeModal();
      }
    });
  } else {
    departmentForm.patch(route('department.update', departmentForm.id), {
      preserveScroll: true,
      onSuccess: () => {
        closeModal();
      }
    });
  }
};

const addEditDepartment = (department: department | null = null) => {
  departmentForm.reset();
  employeeSelected.value = [];
  employeeOptions.value = employeesProp.value;
  if (department) {
    departmentForm.id = department.id;
    departmentForm.name = department.name;
    if (department.employees) {
      employeeSelected.value = department.employees;
      department.employees.forEach((employee) => {
        employeeOptions.value.push(employee);
      });
    }
  }

  addDepartmentModal.value = true;
};

function convertToCustomFormat(isoDate) {
  // Create a new Date object from the ISO string
  const date = new Date(isoDate);

  // Format each component with padding if necessary
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
  const day = String(date.getDate()).padStart(2, '0');
  const hours = String(date.getHours()).padStart(2, '0');
  const minutes = String(date.getMinutes()).padStart(2, '0');
  const seconds = String(date.getSeconds()).padStart(2, '0');

  // Combine the components into the desired format
  return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}

const takeSummaryName = (name: string) => {
  return name.charAt(0) + name.charAt(1);
};

onMounted(() => {
  console.log(props);
  employeesProp.value = props.employees;
  employeeOptions.value = employeesProp.value;
});

const deleteDepartment = (department: department) => {
  console.log('test');
};
</script>
