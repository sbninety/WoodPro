<script setup lang="ts">
import MainLayout from "~/layouts/default.vue";
import { useVuelidate } from "@vuelidate/core";
import { required, email, helpers } from "@vuelidate/validators";
import { useTrans } from "~/composables/trans";
import { useAuthService } from "~/services/auth";
import { useSessionStore } from "~/stores/session";
import { useUserStore } from "~/stores/user";

definePageMeta({ middleware: "auth" });

const { trans } = useTrans();
const authService = useAuthService();
const sessionStore = useSessionStore();
const userStore = useUserStore();

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
    if (res.status && res.data.type == 1) {
      sessionStore.id = res.data.id;
      sessionStore.type = res.data.type;
      sessionStore.setToken(res.data.token);
      await userStore.initCart();

      navigateTo("/");
    } else {
      error.value = trans("ERR_SCREEN_001");
    }
  }
};
</script>
<template>
  <MainLayout>
    <div class="w-[1200px] mx-auto mt-10">
      <div
        style="
          background-image: url('https://theme.hstatic.net/1000280685/1000722794/14/bg_header_image.png?v=1506');
        "
        class="h-[250px] relative"
      >
        <h2
          class="absolute bottom-10 left-1/2 text-5xl text-[#676767] font-medium"
        >
          Đăng nhập
        </h2>
      </div>
      <div class="w-1/2 mx-auto mt-10 mb-[100px]">
        <span class="text-red-400" v-if="error">{{ error }}</span>
        <form @submit.prevent="submit()">
          <div>
            <label for="email" class="text-sm font-light"
              >Email <span class="text-red-400">*</span></label
            ><br />
            <input
              type="text"
              id="email"
              autocomplete="off"
              placeholder="Email"
              class="border border-black w-full mt-2 px-4 py-3"
              v-model="formData.email"
            />
            <span
              class="text-red-400 font-light text-sm"
              v-for="e in v$.email.$errors"
              :key="e"
              >{{ e.$message }}</span
            >
          </div>
          <div class="mt-3 relative">
            <label for="password" class="text-sm font-light"
              >Mật khẩu <span class="text-red-400">*</span></label
            ><br />
            <input
              :type="isShowPassword ? 'text' : 'password'"
              id="password"
              autocomplete="off"
              placeholder="Mật khẩu"
              class="border border-black w-full mt-2 pl-4 pr-11 py-3"
              v-model="formData.password"
            />
            <span
              class="text-red-400 font-light text-sm"
              v-for="e in v$.password.$errors"
              :key="e"
              >{{ e.$message }}</span
            >
            <div
              class="absolute right-3 top-11 cursor-pointer"
              @click="isShowPassword = !isShowPassword"
            >
              <Icon
                :name="
                  isShowPassword ? 'mdi:eye-outline' : 'mdi:eye-off-outline'
                "
                size="25"
              />
            </div>
          </div>
          <div class="mt-4 text-sm font-light">
            <NuxtLink
              to="/account/forgot"
              class="focus:outline-none hover:font-extralight focus:font-extralight"
              >Quên mật khẩu?</NuxtLink
            >
          </div>
          <button
            type="submit"
            class="uppercase bg-black mt-5 text-white px-5 py-2 font-light hover:bg-[#A8A88F] focus:bg-[#A8A88F] focus:outline-none"
          >
            đăng nhập
          </button>
        </form>
        <div class="mt-4 text-sm font-light">
          Bạn chưa có tài khoản? -
          <NuxtLink
            to="/account/register"
            class="focus:outline-none hover:font-extralight focus:font-extralight"
            >Đăng ký</NuxtLink
          >
        </div>
      </div>
    </div>
  </MainLayout>
</template>