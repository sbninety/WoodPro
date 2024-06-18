<script setup lang="ts">
import MainLayout from "~/layouts/default.vue";
import { useCityService } from "~/services/city";
import { useDistrictService } from "~/services/district";
import { useAuthService } from "~/services/auth";
import { useTrans } from "~/composables/trans";
import { useVuelidate } from "@vuelidate/core";
import { required, email, sameAs, helpers } from "@vuelidate/validators";

definePageMeta({ middleware: "auth" });

const cityService = useCityService();
const districtService = useDistrictService();
const authService = useAuthService();
const { trans } = useTrans();
const { phoneNumber } = useValidate();

const isShowPassword = ref(false);
const isShowConfirmPassword = ref(false);
const cities = ref([]);
const districts = ref([]);

onMounted(async () => {
  await cityService.getAll().then((res) => {
    cities.value = res.data;
  });
});

const formData = reactive({
  name: null,
  phone: null,
  city_id: "",
  district_id: "",
  address: null,
  email: null,
  password: null,
  password_confirmation: null,
  type: 1,
  accept: false,
});

watch(
  () => formData.city_id,
  async () => {
    formData.district_id = "";
    await districtService.getDistrictByCityId(formData.city_id).then((res) => {
      districts.value = res.data;
    });
  }
);

const rules = computed(() => {
  return {
    name: {
      required: helpers.withMessage(
        trans("ERR_VAL_0001", [trans("ATTRIBUTE.NAME")]),
        required
      ),
    },
    phone: {
      required: helpers.withMessage(
        trans("ERR_VAL_0001", [trans("ATTRIBUTE.PHONE")]),
        required
      ),
      phoneNumber: helpers.withMessage(
        trans("ERR_VAL_0003", [trans("ATTRIBUTE.PHONE")]),
        phoneNumber
      ),
    },
    city_id: {
      required: helpers.withMessage(
        trans("ERR_VAL_0001", [trans("ATTRIBUTE.CITY")]),
        required
      ),
    },
    district_id: {
      required: helpers.withMessage(
        trans("ERR_VAL_0001", [trans("ATTRIBUTE.DISTRICT")]),
        required
      ),
    },
    address: {
      required: helpers.withMessage(
        trans("ERR_VAL_0001", [trans("ATTRIBUTE.ADDRESS")]),
        required
      ),
    },
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
    password_confirmation: {
      required: helpers.withMessage(
        trans("ERR_VAL_0001", [trans("ATTRIBUTE.PASSWORD")]),
        required
      ),
      sameAsPassword: helpers.withMessage(
        "Mật khẩu phải giống nhau",
        sameAs(formData.password)
      ),
    },
    accept: {
      sameAs: helpers.withMessage(
        "Vui lòng đồng ý điều lệ của chúng tôi",
        sameAs(true)
      ),
    },
  };
});

const v$ = useVuelidate(rules, formData);

const submit = async () => {
  const result = await v$.value.$validate();
  if (result) {
    await authService.register(formData).then((res) => {
      console.log(res.data);
      navigateTo("/account/login");
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
          class="absolute bottom-10 left-1/2 text-5xl text-[#676767] font-medium"
        >
          Đăng ký
        </h2>
      </div>
      <div class="w-1/2 mx-auto mt-10 mb-[100px]">
        <form @submit.prevent="submit()">
          <div>
            <label for="email" class="text-sm font-light"
              >Họ tên <span class="text-red-400">*</span></label
            ><br />
            <input
              type="text"
              id="name"
              autocomplete="off"
              placeholder="Họ tên"
              class="border border-black w-full mt-2 px-4 py-3"
              v-model="formData.name"
            />
            <span
              class="text-red-400 font-light text-sm"
              v-for="e in v$.name.$errors"
              :key="e"
              >{{ e.$message }}</span
            >
          </div>
          <div class="mt-3">
            <label for="phone" class="text-sm font-light"
              >Số điện thoại <span class="text-red-400">*</span></label
            ><br />
            <input
              type="text"
              id="phone"
              autocomplete="off"
              placeholder="Số điện thoại"
              class="border border-black w-full mt-2 px-4 py-3"
              v-model="formData.phone"
            />
            <span
              class="text-red-400 font-light text-sm"
              v-for="e in v$.phone.$errors"
              :key="e"
              >{{ e.$message }}</span
            >
          </div>
          <div class="mt-3 flex gap-5">
            <div class="relative w-1/2">
              <label for="city" class="text-sm font-light"
                >Thành phố <span class="text-red-400">*</span></label
              ><br />
              <select
                id="city"
                class="w-full border border-black px-4 py-3 mt-2 appearance-none"
                v-model="formData.city_id"
              >
                <option disabled selected value="">Thành phố</option>
                <option v-for="city in cities" :key="city" :value="city.id">
                  {{ city.name }}
                </option>
              </select>
              <span
                class="text-red-400 font-light text-sm"
                v-for="e in v$.city_id.$errors"
                :key="e"
                >{{ e.$message }}</span
              >
              <div class="absolute right-2 top-[42px]">
                <Icon name="ic:round-arrow-drop-down" size="29" />
              </div>
            </div>
            <div class="relative w-1/2">
              <label for="city" class="text-sm font-light"
                >Quận/huyện <span class="text-red-400">*</span></label
              ><br />
              <select
                id="city"
                class="w-full border border-black px-4 py-3 mt-2 appearance-none"
                v-model="formData.district_id"
              >
                <option selected value="">Quận/huyện</option>
                <option
                  v-for="district in districts"
                  :key="district"
                  :value="district.id"
                >
                  {{ district.name }}
                </option>
              </select>
              <span
                class="text-red-400 font-light text-sm"
                v-for="e in v$.district_id.$errors"
                :key="e"
                >{{ e.$message }}</span
              >
              <div class="absolute right-2 top-[42px]">
                <Icon name="ic:round-arrow-drop-down" size="29" />
              </div>
            </div>
          </div>
          <div class="mt-3">
            <label for="address" class="text-sm font-light"
              >Địa chỉ <span class="text-red-400">*</span></label
            ><br />
            <input
              type="text"
              id="address"
              autocomplete="off"
              placeholder="Địa chỉ"
              class="border border-black w-full mt-2 px-4 py-3"
              v-model="formData.address"
            />
            <span
              class="text-red-400 font-light text-sm"
              v-for="e in v$.address.$errors"
              :key="e"
              >{{ e.$message }}</span
            >
          </div>
          <div class="mt-3">
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
              v-model="formData.password_confirmation"
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
          <div class="mt-3 flex items-center gap-2">
            <input
              type="checkbox"
              id="accept"
              class="p-2"
              v-model="formData.accept"
            />
            <label for="accept" class=""
              >Đồng ý với chúng tôi về
              <span class="text-[#74B018]">Điều khoản dịch vụ </span> &
              <span class="text-[#74B018]">Chính sách bảo mật</span></label
            >
          </div>
          <div
            class="text-red-400 font-light text-sm"
            v-for="e in v$.accept.$errors"
            :key="e"
          >
            {{ e.$message }}
          </div>
          <button
            type="submit"
            class="uppercase bg-black mt-5 text-white px-5 py-2 font-light hover:bg-[#A8A88F] focus:bg-[#A8A88F] focus:outline-none"
          >
            đăng ký
          </button>
        </form>
        <div class="mt-4 text-sm font-light">
          Bạn đã có tài khoản? -
          <NuxtLink
            to="/account/login"
            class="focus:outline-none hover:font-extralight focus:font-extralight"
            >Đăng nhập</NuxtLink
          >
        </div>
      </div>
    </div>
  </MainLayout>
</template>