<script setup lang="ts">
import MainLayout from "~/layouts/default.vue";
import { useSessionStore } from "~/stores/session";
import { useVuelidate } from "@vuelidate/core";
import { required, email, helpers } from "@vuelidate/validators";
import { useTrans } from "~/composables/trans";
import { useAuthService } from "~/services/auth";

definePageMeta({ middleware: "auth" });

const sessionStore = useSessionStore();
const { trans } = useTrans();
const authService = useAuthService();

const error = ref(null);
const success = ref(null);

const formData = reactive({
  email: null,
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
  };
});

const v$ = useVuelidate(rules, formData);

const submit = async () => {
  error.value = "";
  success.value = "";
  const result = await v$.value.$validate();
  if (result) {
    sessionStore.isLoading = true;
    await authService
      .forgot(formData)
      .then((res) => {
        console.log(res.data);
        success.value = trans("INF_COM_0004");
      })
      .catch((e) => {
        console.log(e);
        error.value = trans("ERR_COM_0019");
      });
    setTimeout(() => {
      sessionStore.isLoading = false;
    }, 100);
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
          class="absolute bottom-10 left-1/4 text-5xl text-[#676767] font-medium"
        >
          Đặt lại mật khẩu
        </h2>
      </div>
      <div class="relative w-1/2 mx-auto mt-10 mb-[100px]">
        <span class="text-[#74B018]" v-if="success">{{ success }}</span>
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
            <span class="text-red-400 font-light text-sm" v-if="error">{{
              error
            }}</span>
          </div>
          <p class="text-sm font-light mt-5">
            Chúng tôi sẽ gửi cho bạn một email xác nhận đặt lại mật khẩu.
          </p>
          <button
            type="submit"
            class="uppercase bg-black mt-5 text-white px-5 py-2 font-light focus:bg-[#A8A88F] focus:outline-none"
          >
            Xác nhận
          </button>
        </form>
        <NuxtLink
          to="/account/login"
          class="absolute uppercase bg-[#CCCCCC] mt-5 px-5 py-2 font-light bottom-0 left-1/4"
        >
          Hủy bỏ
        </NuxtLink>
      </div>
    </div>
  </MainLayout>
</template>