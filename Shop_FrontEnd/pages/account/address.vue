<script setup lang="ts">
import MainLayout from "~/layouts/default.vue";
import { useProfileService } from "~/services/profile";
import { useSessionStore } from "~/stores/session";
import { useCityService } from "~/services/city";
import { useDistrictService } from "~/services/district";
import { useTrans } from "~/composables/trans";
import { useVuelidate } from "@vuelidate/core";
import { required, helpers } from "@vuelidate/validators";

definePageMeta({ middleware: "order" });

const profileService = useProfileService();
const cityService = useCityService();
const districtService = useDistrictService();
const sessionStore = useSessionStore();
const { trans } = useTrans();
const { phoneNumber } = useValidate();

const cities = ref([]);
const districts = ref([]);
const formData = reactive({
  name: null,
  phone: null,
  city_id: null,
  district_id: null,
  address: null,
});

onMounted(async () => {
  sessionStore.isLoading = true;

  const res = await Promise.all([
    cityService.getAll(),
    profileService.getProfile(sessionStore.id),
  ]);
  cities.value = res[0].data;

  formData.name = res[1].data.name;
  formData.phone = res[1].data.phone;
  formData.city_id = res[1].data.city_id;
  formData.district_id = res[1].data.district_id;
  formData.address = res[1].data.address;

  setTimeout(() => {
    sessionStore.isLoading = false;
  }, 200);
});

watch(
  () => formData.city_id,
  async () => {
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
  };
});

const v$ = useVuelidate(rules, formData);

const submit = async () => {
  const result = await v$.value.$validate();
  if (result) {
    const res = await profileService.update(formData, sessionStore.id);
    if (res.status) {
      return navigateTo("/orders");
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
          class="absolute bottom-10 left-1/4 text-5xl text-[#676767] font-medium"
        >
          Cập nhật thông tin
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
                id="district"
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
          <button
            type="submit"
            class="uppercase bg-black mt-5 text-white px-5 py-2 font-light hover:bg-[#A8A88F] focus:bg-[#A8A88F] focus:outline-none"
          >
            Lưu thông tin
          </button>
        </form>
      </div>
    </div>
  </MainLayout>
</template>