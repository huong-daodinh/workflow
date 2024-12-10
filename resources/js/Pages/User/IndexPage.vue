<template>
  <AuthenticatedLayout>
    <Head title="Department" />
    <div class="flex items-center justify-between flex-wrap gap-4">
      <h2 class="text-xl">Contacts</h2>
      <div class="flex sm:flex-row flex-col sm:items-center sm:gap-3 gap-4 w-full sm:w-auto">
        <div class="flex gap-3">
          <div>
            <button type="button" class="btn btn-primary" @click="addEditUser()">
              <icon-user-plus class="ltr:mr-2 rtl:ml-2" />
              Tạo tài khoản nhân viên
            </button>
          </div>
          <div>
            <button
              type="button"
              class="btn btn-outline-primary p-2"
              :class="{ 'bg-primary text-white': displayType === 'list' }"
              @click="displayType = 'list'">
              <icon-list-check />
            </button>
          </div>
          <div>
            <button
              type="button"
              class="btn btn-outline-primary p-2"
              :class="{ 'bg-primary text-white': displayType === 'grid' }"
              @click="displayType = 'grid'">
              <icon-layout-grid />
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="mt-5 panel p-0 border-0 overflow-hidden">
      <template v-if="displayType === 'list'">
        <div class="table-responsive">
          <table class="table-striped table-hover">
            <thead>
              <tr>
                <th>Tên</th>
                <th>Email</th>
                <th>Vai trò</th>
                <th>Trạng thái tài khoản</th>
                <th class="!text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <template v-for="user in users" :key="user.id">
                <tr>
                  <td>
                    <div class="flex items-center w-max">
                      <div v-if="user.avatar" class="w-max">
                        <img
                          :src="user.avatar"
                          class="h-8 w-8 rounded-full object-cover ltr:mr-2 rtl:ml-2"
                          alt="avatar" />
                      </div>
                      <div
                        v-show="!user.avatar"
                        class="border border-gray-300 dark:border-gray-800 rounded-full grid place-content-center h-8 w-8 ltr:mr-2 rtl:ml-2">
                        <icon-user class="w-4.5 h-4.5" />
                      </div>
                      <div>{{ user.name }}</div>
                    </div>
                  </td>
                  <td>{{ user.email }}</td>
                  <td class="whitespace-nowrap">
                    <span
                      class="badge"
                      :class="{
                        'badge-outline-warning': user.role === 'manager',
                        'badge-outline-secondary': user.role === 'member'
                      }">
                      {{
                        user.role == 'member'
                          ? 'Nhân viên'
                          : user.role == 'manager'
                          ? 'Trưởng phòng'
                          : ''
                      }}
                    </span>
                  </td>
                  <td class="whitespace-nowrap">
                    <span v-if="user.active" class="badge bg-success">Hoạt động</span>
                    <span v-else class="badge bg-danger">Vô hiệu hoá</span>
                  </td>
                  <td>
                    <div class="flex gap-4 items-center justify-center" v-if="user.can_action">
                      <button
                        type="button"
                        class="btn btn-sm btn-outline-primary"
                        @click="addEditUser(user)">
                        Sửa
                      </button>
                      <button
                        type="button"
                        class="btn btn-sm btn-outline-danger"
                        @click="deleteUser(user)">
                        Xoá
                      </button>
                    </div>
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
      </template>
    </div>
    <template v-if="displayType === 'grid'">
      <div class="grid 2xl:grid-cols-4 xl:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-6 w-full">
        <template v-for="user in users" :key="user.id">
          <div
            class="bg-white dark:bg-[#1c232f] rounded-md overflow-hidden text-center shadow relative">
            <div
              class="bg-white/40 rounded-t-md bg-[url('/assets/images/notification-bg.png')] bg-center bg-cover p-6 pb-0">
              <template v-if="user.avatar">
                <img class="object-contain w-4/5 max-h-40 mx-auto" :src="user.avatar" />
              </template>
            </div>
            <div class="px-6 pb-24 -mt-10 relative">
              <div class="shadow-md bg-white dark:bg-gray-900 rounded-md px-2 py-4">
                <div class="text-xl">{{ user.name }}</div>
                <div class="text-white-dark">{{ user.role }}</div>
              </div>
              <div class="mt-6 grid grid-cols-1 gap-4 ltr:text-left rtl:text-right">
                <div class="flex items-center">
                  <div class="flex-none ltr:mr-2 rtl:ml-2">Email :</div>
                  <div class="truncate text-white-dark">{{ user.email }}</div>
                </div>
                <div class="flex items-center">
                  <div class="flex-none ltr:mr-2 rtl:ml-2">Phone :</div>
                  <div class="text-white-dark">{{ user.phone }}</div>
                </div>
              </div>
            </div>
            <div
              class="mt-6 flex gap-4 absolute bottom-0 w-full ltr:left-0 rtl:right-0 p-6"
              v-if="user.can_action">
              <button
                type="button"
                class="btn btn-outline-primary w-1/2"
                @click="addEditUser(user)">
                Edit
              </button>
              <button type="button" class="btn btn-outline-danger w-1/2" @click="deleteUser(user)">
                Delete
              </button>
            </div>
          </div>
        </template>
      </div>
    </template>

    <TransitionRoot appear :show="addUserModal" as="template">
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
                class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg text-black dark:text-white-dark">
                <button
                  type="button"
                  class="absolute top-4 ltr:right-4 rtl:left-4 text-gray-400 hover:text-gray-800 dark:hover:text-gray-600 outline-none"
                  @click="closeModal()">
                  <icon-x />
                </button>
                <div
                  class="text-lg font-medium bg-[#fbfbfb] dark:bg-[#121c2c] ltr:pl-5 rtl:pr-5 py-3 ltr:pr-[50px] rtl:pl-[50px]">
                  {{ userForm.id ? 'Chỉnh sửa thông tin' : 'Thêm mới' }}
                </div>
                <div class="p-5">
                  <form @submit.prevent="saveUser">
                    <div class="mb-5">
                      <label for="name">Họ tên</label>
                      <input
                        id="name"
                        type="text"
                        placeholder="Enter Name"
                        class="form-input"
                        v-model="userForm.name" />
                      <input-error :message="userForm.errors.name" />
                    </div>
                    <div class="mb-5">
                      <label for="email">Email</label>
                      <input
                        id="email"
                        type="email"
                        placeholder="Enter Email"
                        class="form-input"
                        v-model="userForm.email" />
                      <input-error :message="userForm.errors.email" />
                    </div>
                    <div class="mb-5" v-if="!userForm.id">
                      <label for="password">Mật khẩu</label>
                      <input
                        id="password"
                        type="password"
                        placeholder="Nhập mật khẩu"
                        class="form-input"
                        v-model="userForm.password" />
                      <input-error :message="userForm.errors.password" />
                    </div>
                    <div class="mb-5" v-if="!userForm.id">
                      <label for="password_confirmation">Nhập lại mật khẩu</label>
                      <input
                        id="password_confirmation"
                        type="password"
                        placeholder="Nhập lại mật khẩu"
                        class="form-input"
                        v-model="userForm.password_confirmation" />
                      <input-error :message="userForm.errors.password_confirmation" />
                    </div>
                    <div class="mb-5">
                      <label for="number">Số điện thoại</label>
                      <input
                        id="number"
                        placeholder="Enter Phone Number"
                        class="form-input"
                        v-model="userForm.phone" />
                      <input-error :message="userForm.errors.phone" />
                    </div>
                    <div class="mb-5">
                      <label for="occupation">Vai trò</label>
                      <select
                        id="occupation"
                        :disabled="authUser.role !== 'admin'"
                        class="form-select text-white-dark"
                        v-model="userForm.role">
                        <option value="manager">Trưởng phòng</option>
                        <option value="member">Nhân viên</option>
                      </select>
                      <input-error :message="userForm.errors.role" />
                    </div>
                    <div class="mb-5">
                      <label for="occupation">Phòng ban</label>
                      <select
                        id="occupation"
                        :disabled="authUser.role !== 'admin'"
                        class="form-select text-white-dark"
                        v-model="userForm.department_id">
                        <option
                          :value="department.id"
                          v-for="(department, index) in departments"
                          :key="index">
                          {{ department.name }}
                        </option>
                      </select>
                      <input-error :message="userForm.errors.role" />
                    </div>
                    <div class="mb-5">
                      <label for="active">Active</label>
                      <label class="w-12 h-6 relative">
                        <input
                          type="checkbox"
                          v-model="userForm.active"
                          class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                          id="active" />
                        <span
                          for="active"
                          class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                      </label>
                      <input-error :message="userForm.errors.active" />
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
import { Head, useForm, usePage } from '@inertiajs/vue3';
import IconUserPlus from '@/Components/icon/icon-user-plus.vue';
import IconListCheck from '@/Components/icon/icon-list-check.vue';
import IconLayoutGrid from '@/Components/icon/icon-layout-grid.vue';
import IconUser from '@/Components/icon/icon-user.vue';
import IconX from '@/Components/icon/icon-x.vue';
import { ref, defineProps, computed } from 'vue';
import { department, user } from '@/interfaces/index.interfaces';
import InputError from '@/Components/InputError.vue';
import { delay } from 'lodash';

const page = usePage();
const displayType = ref('list');
const props = defineProps({
  users: Array<user>,
  departments: Array<department>
});

const closeModal = () => {
  addUserModal.value = false;
  delay(100);
  userForm.reset();
  userForm.clearErrors();
};

const authUser = page.props.auth.user;
console.log(authUser);
console.log(props.departments);

const addUserModal = ref(false);
const userForm = useForm({
  id: 0,
  name: '',
  email: '',
  role: authUser.role === 'manager' ? 'member' : '',
  phone: '',
  avatar: null,
  password: '',
  password_confirmation: '',
  active: false,
  department_id: authUser.role === 'manager' ? authUser.department_id : null
});
const saveUser = () => {
  if (!userForm.id) {
    userForm.post(route('user.store'), {
      preserveScroll: true,
      onSuccess: () => {
        closeModal();
      }
    });
  } else {
    userForm.patch(route('user.update', userForm.id), {
      preserveScroll: true,
      onSuccess: () => {
        closeModal();
      }
    });
  }
};

const addEditUser = (user: user | null = null) => {
  userForm.reset();
  if (user) {
    userForm.id = user.id;
    userForm.name = user.name;
    userForm.email = user.email;
    userForm.role = user.role;
    userForm.phone = user.phone;
    userForm.active = user.active ? true : false;
    userForm.department_id = user.department_id;
  }
  addUserModal.value = true;
};

const deleteUser = (user: user) => {
  console.log('test');
};
</script>
