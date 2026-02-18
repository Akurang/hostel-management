<template>
  <div class="grid min-h-screen bg-slate-100 lg:grid-cols-2">
    <section class="relative hidden overflow-hidden bg-[#1B4332] p-10 text-white lg:flex lg:flex-col lg:justify-between">
      <div>
        <p class="text-3xl font-black"><span class="text-emerald-300">Campus</span>Stay</p>
        <p class="mt-4 max-w-sm text-sm text-emerald-100">Create your account and find a verified campus hostel with confidence.</p>
      </div>
      <div class="rounded-2xl border border-white/20 bg-white/10 p-5 backdrop-blur">
        <h2 class="font-serif text-2xl font-bold">Join CampusStay</h2>
        <p class="mt-2 text-sm text-emerald-100">Students and managers can get started in minutes.</p>
      </div>
      <div class="pointer-events-none absolute -bottom-16 -right-16 h-64 w-64 rounded-full bg-emerald-300/20 blur-3xl" />
    </section>

    <section class="flex items-center justify-center px-6 py-10">
      <div class="w-full max-w-md rounded-2xl bg-white p-8 shadow-sm">
        <h1 class="text-2xl font-black text-slate-900">Create Account</h1>

        <form class="mt-6 space-y-4" @submit.prevent="submit">
          <div>
            <label class="mb-1 block text-sm font-semibold text-slate-700">Full Name</label>
            <input v-model="form.name" type="text" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none ring-emerald-300 focus:ring">
            <p v-if="form.errors.name" class="mt-1 text-xs text-rose-600">{{ form.errors.name }}</p>
          </div>

          <div>
            <label class="mb-1 block text-sm font-semibold text-slate-700">Email Address</label>
            <input v-model="form.email" type="email" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none ring-emerald-300 focus:ring">
            <p v-if="form.errors.email" class="mt-1 text-xs text-rose-600">{{ form.errors.email }}</p>
          </div>

          <div>
            <label class="mb-1 block text-sm font-semibold text-slate-700">Phone Number</label>
            <input v-model="form.phone" type="text" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none ring-emerald-300 focus:ring">
            <p v-if="form.errors.phone" class="mt-1 text-xs text-rose-600">{{ form.errors.phone }}</p>
          </div>

          <div>
            <label class="mb-1 block text-sm font-semibold text-slate-700">University</label>
            <select v-model="form.university" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none ring-emerald-300 focus:ring">
              <option value="">Select University</option>
              <option value="KNUST">KNUST</option>
              <option value="UG">UG</option>
              <option value="UCC">UCC</option>
              <option value="Ashesi">Ashesi</option>
              <option value="Other">Other</option>
            </select>
            <p v-if="form.errors.university" class="mt-1 text-xs text-rose-600">{{ form.errors.university }}</p>
          </div>

          <div>
            <p class="mb-2 block text-sm font-semibold text-slate-700">Account Type</p>
            <div class="grid grid-cols-2 gap-2">
              <button
                type="button"
                class="rounded-full border px-3 py-2 text-sm font-semibold"
                :class="form.role === 'student' ? 'border-emerald-700 bg-emerald-50 text-emerald-700' : 'border-slate-200 text-slate-600'"
                @click="form.role = 'student'"
              >
                I'm a Student
              </button>
              <button
                type="button"
                class="rounded-full border px-3 py-2 text-sm font-semibold"
                :class="form.role === 'manager' ? 'border-emerald-700 bg-emerald-50 text-emerald-700' : 'border-slate-200 text-slate-600'"
                @click="form.role = 'manager'"
              >
                I'm a Manager
              </button>
            </div>
          </div>

          <div v-show="form.role === 'student'">
            <label class="mb-1 block text-sm font-semibold text-slate-700">Student ID</label>
            <input v-model="form.student_id" type="text" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none ring-emerald-300 focus:ring">
            <p v-if="form.errors.student_id" class="mt-1 text-xs text-rose-600">{{ form.errors.student_id }}</p>
          </div>

          <div v-if="form.role === 'manager'" class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-700">
            Manager accounts require admin approval before activation. You will be notified by email once approved.
          </div>

          <div>
            <label class="mb-1 block text-sm font-semibold text-slate-700">Password</label>
            <input v-model="form.password" type="password" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none ring-emerald-300 focus:ring">
            <p v-if="form.errors.password" class="mt-1 text-xs text-rose-600">{{ form.errors.password }}</p>
          </div>

          <div>
            <label class="mb-1 block text-sm font-semibold text-slate-700">Confirm Password</label>
            <input v-model="form.password_confirmation" type="password" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none ring-emerald-300 focus:ring">
          </div>

          <button
            type="submit"
            class="w-full rounded-lg bg-[#1B4332] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#163828]"
            :disabled="form.processing"
          >
            {{ form.processing ? 'Creating account...' : 'Create Account' }}
          </button>
        </form>

        <p class="mt-5 text-center text-sm text-slate-600">
          Already have an account?
          <Link href="/login" class="font-semibold text-emerald-700">Sign in</Link>
        </p>
      </div>
    </section>
  </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';

const form = useForm({
  name: '',
  email: '',
  phone: '',
  university: '',
  role: 'student',
  student_id: '',
  password: '',
  password_confirmation: '',
});

const submit = () => {
  form.post('/register');
};
</script>
