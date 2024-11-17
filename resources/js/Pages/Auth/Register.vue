<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import IconLockDots from '@/Components/icon/icon-lock-dots.vue';
import IconMail from '@/Components/icon/icon-mail.vue';
import IconUser from '@/Components/icon/icon-user.vue';
const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
});

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation')
  });
};
</script>

<template>
  <GuestLayout>
    <Head title="Register" />
    <div>
      <div class="absolute inset-0">
        <img
          src="/assets/images/auth/bg-gradient.png"
          alt="image"
          class="h-full w-full object-cover" />
      </div>

      <div
        class="relative flex min-h-screen items-center justify-center px-6 py-10 dark:bg-[#060818] sm:px-16">
        <div
          class="relative w-full max-w-[870px] rounded-md bg-[linear-gradient(45deg,#fff9f9_0%,rgba(255,255,255,0)_25%,rgba(255,255,255,0)_75%,_#fff9f9_100%)] p-2 dark:bg-[linear-gradient(52.22deg,#0E1726_0%,rgba(14,23,38,0)_18.66%,rgba(14,23,38,0)_51.04%,rgba(14,23,38,0)_80.07%,#0E1726_100%)]">
          <div
            class="relative flex flex-col justify-center rounded-md bg-white/60 backdrop-blur-lg dark:bg-black/50 px-6 lg:min-h-[758px] py-20">
            <div class="mx-auto w-full max-w-[440px]">
              <div class="mb-10">
                <h1
                  class="text-3xl font-extrabold uppercase !leading-snug text-primary md:text-4xl">
                  Register
                </h1>
              </div>
              <form @submit.prevent="submit" class="space-y-5">
                <div>
                  <label for="Email">Email</label>
                  <div class="relative text-white-dark">
                    <input
                      id="Email"
                      v-model="form.email"
                      placeholder="Enter Email"
                      class="form-input ps-10 placeholder:text-white-dark" />
                    <span class="absolute start-4 top-1/2 -translate-y-1/2">
                      <icon-mail :fill="true" />
                    </span>
                  </div>
                  <input-error :message="form.errors.email" class="mt-2" />
                </div>
                <div>
                  <label for="Password">Name</label>
                  <div class="relative text-white-dark">
                    <input
                      id="Password"
                      v-model="form.name"
                      type="password"
                      placeholder="Enter Your Full Name"
                      class="form-input ps-10 placeholder:text-white-dark" />
                    <span class="absolute start-4 top-1/2 -translate-y-1/2">
                      <icon-user :fill="true" />
                    </span>
                  </div>
                  <input-error :message="form.errors.name" class="mt-2" />
                </div>
                <div>
                  <label for="Password">Password</label>
                  <div class="relative text-white-dark">
                    <input
                      id="Password"
                      v-model="form.password"
                      type="password"
                      placeholder="Enter Password"
                      class="form-input ps-10 placeholder:text-white-dark" />
                    <span class="absolute start-4 top-1/2 -translate-y-1/2">
                      <icon-lock-dots :fill="true" />
                    </span>
                  </div>
                  <input-error :message="form.errors.password" class="mt-2" />
                </div>
                <div>
                  <label for="Password">Confirm Password</label>
                  <div class="relative text-white-dark">
                    <input
                      id="Password"
                      v-model="form.password_confirmation"
                      type="password"
                      placeholder="Enter Password"
                      class="form-input ps-10 placeholder:text-white-dark" />
                    <span class="absolute start-4 top-1/2 -translate-y-1/2">
                      <icon-lock-dots :fill="true" />
                    </span>
                  </div>
                  <input-error :message="form.errors.password_confirmation" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                  <Link
                    :href="route('login.view')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Already registered?
                  </Link>
                </div>
                <button
                  type="submit"
                  :disabled="form.processing"
                  :class="{ 'opacity-25': form.processing }"
                  class="btn btn-gradient !mt-6 w-full border-0 uppercase shadow-[0_10px_20px_-10px_rgba(67,97,238,0.44)]">
                  Register
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>
