<template>
  <div class="grid min-h-screen bg-slate-100 lg:grid-cols-2">
    <section class="relative hidden overflow-hidden bg-[#1B4332] p-10 text-white lg:flex lg:flex-col lg:justify-between">
      <div>
        <p class="text-3xl font-black"><span class="text-emerald-300">Campus</span>Stay</p>
        <p class="mt-4 max-w-sm text-sm text-emerald-100">Secure hostel discovery and booking for Ghanaian university students.</p>
      </div>
      <div class="rounded-2xl border border-white/20 bg-white/10 p-5 backdrop-blur">
        <h2 class="font-serif text-2xl font-bold">Welcome Back</h2>
        <p class="mt-2 text-sm text-emerald-100">Log in to continue your booking journey.</p>
      </div>
      <div class="pointer-events-none absolute -bottom-16 -right-16 h-64 w-64 rounded-full bg-emerald-300/20 blur-3xl" />
    </section>

    <section class="flex items-center justify-center px-6 py-10">
      <div class="w-full max-w-md rounded-2xl bg-white p-8 shadow-sm">
        <h1 class="text-2xl font-black text-slate-900">Sign In</h1>
        <p class="mt-1 text-sm text-slate-500">Access your CampusStay account</p>

        <p v-if="$page.props.flash.error" class="mt-4 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-700">
          {{ $page.props.flash.error }}
        </p>
        <p v-if="$page.props.flash.success" class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-700">
          {{ $page.props.flash.success }}
        </p>

        <form class="mt-6 space-y-4" @submit.prevent="submit">
          <div>
            <label class="mb-1 block text-sm font-semibold text-slate-700">Email address</label>
            <input
              v-model="form.email"
              type="email"
              class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none ring-emerald-300 focus:ring"
            >
            <p v-if="form.errors.email" class="mt-1 text-xs text-rose-600">{{ form.errors.email }}</p>
          </div>

          <div>
            <label class="mb-1 block text-sm font-semibold text-slate-700">Password</label>
            <div class="relative">
              <input
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                class="w-full rounded-lg border border-slate-200 px-3 py-2 pr-16 text-sm outline-none ring-emerald-300 focus:ring"
              >
              <button
                type="button"
                class="absolute right-2 top-1/2 -translate-y-1/2 rounded px-2 py-1 text-xs font-semibold text-slate-500 hover:bg-slate-100"
                @click="showPassword = !showPassword"
              >
                {{ showPassword ? 'Hide' : 'Show' }}
              </button>
            </div>
            <p v-if="form.errors.password" class="mt-1 text-xs text-rose-600">{{ form.errors.password }}</p>
          </div>

          <div class="flex items-center justify-between">
            <label class="inline-flex items-center gap-2 text-sm text-slate-600">
              <input v-model="form.remember" type="checkbox" class="rounded border-slate-300 text-emerald-700 focus:ring-emerald-500">
              Remember me
            </label>
            <span class="text-sm text-emerald-700">Forgot password?</span>
          </div>

          <button
            type="submit"
            class="w-full rounded-lg bg-[#1B4332] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#163828]"
            :disabled="form.processing"
          >
            {{ form.processing ? 'Signing in...' : 'Sign In' }}
          </button>
        </form>

        <p class="mt-5 text-center text-sm text-slate-600">
          Don't have an account?
          <Link href="/register" class="font-semibold text-emerald-700">Sign up</Link>
        </p>
      </div>
    </section>
  </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const showPassword = ref(false);

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submit = () => {
  form.post('/login');
};
</script>
