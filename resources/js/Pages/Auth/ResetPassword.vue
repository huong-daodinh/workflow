<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import IconLockDots from '@/Components/icon/icon-lock-dots.vue';
import IconMail from '@/Components/icon/icon-mail.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
  email: {
    type: String,
    required: true
  },
  token: {
    type: String,
    required: true
  }
});

const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: ''
});

const submit = () => {
  form.post(route('password.store'), {
    onFinish: () => form.reset('password', 'password_confirmation')
  });
};
</script>

<template>
  <GuestLayout>
    <Head title="Reset Password" />
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
              <div class="mb-16">
                <h1
                  class="text-3xl font-extrabold uppercase !leading-snug text-primary md:text-4xl">
                  Reset Password
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
                  <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing">
                    Reset Password
                  </PrimaryButton>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>
