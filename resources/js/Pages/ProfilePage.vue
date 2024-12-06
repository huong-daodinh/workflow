<template>
  <AuthenticatedLayout>
    <Head title="User Detail" />
    <div>
      <form
        class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-4 mb-5 bg-white dark:bg-[#0e1726]">
        <h6 class="text-lg font-bold mb-5">General Information</h6>
        <div class="flex flex-col sm:flex-row">
          <div class="ltr:sm:mr-4 rtl:sm:ml-4 w-full sm:w-2/12 mb-5">
            <img
              src="/assets//images/profile-34.jpeg"
              alt=""
              class="w-20 h-20 md:w-32 md:h-32 rounded-full object-cover mx-auto" />
          </div>
          <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
              <label for="name">Full Name</label>
              <input id="name" type="text" placeholder="Jimmy Turner" class="form-input" />
            </div>
            <div>
              <label for="profession">Profession</label>
              <input id="profession" type="text" placeholder="Web Developer" class="form-input" />
            </div>
            <div>
              <label for="country">Country</label>
              <select id="country" class="form-select text-white-dark">
                <option>All Countries</option>
                <option selected>United States</option>
                <option>India</option>
                <option>Japan</option>
                <option>China</option>
                <option>Brazil</option>
                <option>Norway</option>
                <option>Canada</option>
              </select>
            </div>
            <div>
              <label for="address">Address</label>
              <input id="address" type="text" placeholder="New York" class="form-input" />
            </div>
            <div>
              <label for="location">Location</label>
              <input id="location" type="text" placeholder="Location" class="form-input" />
            </div>
            <div>
              <label for="phone">Phone</label>
              <input id="phone" type="text" placeholder="+1 (530) 555-12121" class="form-input" />
            </div>
            <div>
              <label for="email">Email</label>
              <input id="email" type="email" placeholder="Jimmy@gmail.com" class="form-input" />
            </div>
            <div>
              <label for="web">Website</label>
              <input id="web" type="text" placeholder="Enter URL" class="form-input" />
            </div>
            <div>
              <label class="inline-flex cursor-pointer">
                <input type="checkbox" class="form-checkbox" />
                <span class="text-white-dark relative checked:bg-none"
                  >Make this my default address</span
                >
              </label>
            </div>
            <div class="sm:col-span-2 mt-3">
              <button type="button" class="btn btn-primary">Save</button>
            </div>
          </div>
        </div>
      </form>
      <form
        class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-4 bg-white dark:bg-[#0e1726]">
        <h6 class="text-lg font-bold mb-5">Social</h6>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
          <div class="flex">
            <div
              class="bg-[#eee] flex justify-center items-center rounded px-3 font-semibold dark:bg-[#1b2e4b] ltr:mr-2 rtl:ml-2">
              <icon-linkedin class="w-5 h-5" />
            </div>
            <input type="text" placeholder="jimmy_turner" class="form-input" />
          </div>
          <div class="flex">
            <div
              class="bg-[#eee] flex justify-center items-center rounded px-3 font-semibold dark:bg-[#1b2e4b] ltr:mr-2 rtl:ml-2">
              <icon-twitter class="w-5 h-5" />
            </div>
            <input type="text" placeholder="jimmy_turner" class="form-input" />
          </div>
          <div class="flex">
            <div
              class="bg-[#eee] flex justify-center items-center rounded px-3 font-semibold dark:bg-[#1b2e4b] ltr:mr-2 rtl:ml-2">
              <icon-facebook class="w-5 h-5" />
            </div>
            <input type="text" placeholder="jimmy_turner" class="form-input" />
          </div>
          <div class="flex">
            <div
              class="bg-[#eee] flex justify-center items-center rounded px-3 font-semibold dark:bg-[#1b2e4b] ltr:mr-2 rtl:ml-2">
              <icon-github />
            </div>
            <input type="text" placeholder="jimmy_turner" class="form-input" />
          </div>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

<script lang="ts" setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>
