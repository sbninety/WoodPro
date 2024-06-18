<script setup lang="ts">
import MainLayout from "~/layouts/default.vue";
import { useTrans } from "~/composables/trans";
import { useAuthService } from "~/services/auth";
import { useVuelidate } from "@vuelidate/core";
import { required, sameAs, helpers } from "@vuelidate/validators";

const route = useRoute();
const { trans } = useTrans();
const authService = useAuthService();

const isShowPassword = ref(false);
const isShowConfirmPassword = ref(false);

const data = reactive({
  token: null,
  password: null,
  password_confirmation: null,
});

onMounted(async () => {
  data.token = route.params.token;

  await authService
    .checkToken({ token: data.token })
    .then((res) => {
      console.log(res.status);
    })
    .catch((e) => {
      console.log(e);
      navigateTo("/404");
    });
});

const rules = computed(() => {
  return {
    password: {
      required: helpers.withMessage(
        trans("ERR_VAL_0001", [trans("ATTRIBUTE.PASSWORD")]),
        required
      ),
    },
    password_confirmation: {
      required: helpers.withMessage(
        trans("ERR_VAL_0001", [trans("ATTRIBUTE.PASSWORD")]),
        required
      ),
      sameAsPassword: helpers.withMessage(
        "Mật khẩu phải giống nhau",
        sameAs(data.password)
      ),
    },
  };
});

const v$ = useVuelidate(rules, data);

const submit = async () => {
  const result = await v$.value.$validate();
  if (result) {
    let formData = new FormData();
    formData.append("token", data.token);
    formData.append("password", data.password);
    formData.append("password_confirmation", data.password_confirmation);
    formData.append("_method", "PATCH");

    await authService
      .resetPassword(formData)
      .then((res) => {
        console.log(res.status);
        navigateTo("/account/login");
      })
      .catch((e) => {
        console.log(e);
      });
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
      <div class="w-1/2 mx-auto mt-10 mb-[100px]">
        <form @submit.prevent="submit()">
          <div class="mt-3 relative">
            <label for="password" class="text-sm font-light"
              >Mật khẩu mới <span class="text-red-400">*</span></label
            ><br />
            <input
              :type="isShowPassword ? 'text' : 'password'"
              id="password"
              autocomplete="off"
              placeholder="Mật khẩu"
              class="border border-black w-full mt-2 pl-4 pr-11 py-3"
              v-model="data.password"
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
          <div class="mt-3 relative">
            <label for="confirm_password" class="text-sm font-light"
              >Nhập lại mật khẩu <span class="text-red-400">*</span></label
            ><br />
            <input
              :type="isShowConfirmPassword ? 'text' : 'password'"
              id="confirm_password"
              autocomplete="off"
              placeholder="Nhập lại mật khẩu"
              class="border border-black w-full mt-2 pl-4 pr-11 py-3"
              v-model="data.password_confirmation"
            />
            <span
              class="text-red-400 font-light text-sm"
              v-for="e in v$.password_confirmation.$errors"
              :key="e"
              >{{ e.$message }}</span
            >
            <div
              class="absolute right-3 top-11 cursor-pointer"
              @click="isShowConfirmPassword = !isShowConfirmPassword"
            >
              <Icon
                :name="
                  isShowConfirmPassword
                    ? 'mdi:eye-outline'
                    : 'mdi:eye-off-outline'
                "
                size="25"
              />
            </div>
          </div>
          <button
            type="submit"
            class="uppercase bg-black mt-5 text-white px-5 py-2 font-light focus:bg-[#A8A88F] focus:outline-none"
          >
            Xác nhận
          </button>
        </form>
      </div>
    </div>
  </MainLayout>
</template>