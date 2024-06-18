<script setup lang="ts">
import { useVuelidate } from "@vuelidate/core";
import { required, email, helpers } from "@vuelidate/validators";
import { useTrans } from "~/composables/trans";
import { useAuthService } from "~/services/auth";
import { useSessionStore } from "~/stores/session";

definePageMeta({ middleware: "store" });

const { trans } = useTrans();
const authService = useAuthService();
const sessionStore = useSessionStore();

const isShowPassword = ref(false);
const error = ref(null);

const formData = reactive({
  email: null,
  password: null,
});

const rules = computed(() => {
  return {
    email: {
      required: helpers.withMessage(
        trans("ERR_VAL_0001", [trans("ATTRIBUTE.EMAIL")]),
        required
      ),
      email: helpers.withMessage(
        trans("ERR_VAL_0003", [trans("ATTRIBUTE.EMAIL")]),
        email
      ),
    },
    password: {
      required: helpers.withMessage(
        trans("ERR_VAL_0001", [trans("ATTRIBUTE.PASSWORD")]),
        required
      ),
    },
  };
});

const v$ = useVuelidate(rules, formData);

const submit = async () => {
  const result = await v$.value.$validate();
  if (result) {
    const res = await authService.login(formData);
    if (res.status && (res.data.type == 2 || res.data.type == 3)) {
      console.log(11111);

      sessionStore.id = res.data.id;
      sessionStore.type = res.data.type;
      sessionStore.setToken(res.data.token);

      return navigateTo("/store");
    } else {
      error.value = trans("ERR_SCREEN_001");
    }
  }
};
</script>
<template>
  <div class="flex items-center justify-center h-[100vh] bg-[#F3F4F6]">
    <div class="bg-white p-5 w-[450px] mx-auto rounded shadow-sm">
      <h2 class="text-4xl px-4 mb-10">Đăng nhập</h2>
      <span class="text-red-400" v-if="error">{{ error }}</span>
      <form class="space-y-8 mt-2" @submit.prevent="submit()">
        <input
          class="w-full border rounded h-12 px-4 focus:outline-none"
          placeholder="Email"
          type="text"
          v-model="formData.email"
        />
        <span
          class="text-red-400 font-light text-sm"
          v-for="e in v$.email.$errors"
          :key="e"
          >{{ e.$message }}</span
        >
        <div class="relative flex items-center">
          <input
            class="w-full border rounded h-12 px-4 focus:outline-none -mr-7"
            placeholder="Mật khẩu"
            :type="isShowPassword ? 'text' : 'password'"
            v-model="formData.password"
          />
          <span
            class="text-red-400 font-light text-sm"
            v-for="e in v$.password.$errors"
            :key="e"
            >{{ e.$message }}</span
          >
          <div
            class="absolute right-3 top-3 cursor-pointer"
            @click="isShowPassword = !isShowPassword"
          >
            <Icon
              :name="isShowPassword ? 'mdi:eye-outline' : 'mdi:eye-off-outline'"
              size="25"
            />
          </div>
        </div>
        <div>
          <div
            class="flex flex-col md:flex-row md:items-center justify-between"
          >
            <button
              class="bg-orange-500 text-sm active:bg-gray-700 cursor-pointer font-regular text-white px-4 py-2 rounded uppercase"
              type="submit"
            >
              ĐĂNG NHẬP
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>