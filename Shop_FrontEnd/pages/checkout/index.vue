<script setup lang="ts">
import MainLayout from "~/layouts/default.vue";
import CheckoutItem from "~/components/pages/checkout/item.vue";
import Swal from "sweetalert2";
import { useUserStore } from "~/stores/user";
import { useSessionStore } from "~/stores/session";
import { useUtils } from "~/composables/utils";
import { useCityService } from "~/services/city";
import { useDistrictService } from "~/services/district";
import { useProfileService } from "~/services/profile";
import { useOrderService } from "~/services/order";
import { useCartService } from "~/services/cart";
import { useTrans } from "~/composables/trans";
import { useVuelidate } from "@vuelidate/core";
import { required, helpers } from "@vuelidate/validators";

const userStore = useUserStore();
const sessionStore = useSessionStore();
const { price } = useUtils();
const cityService = useCityService();
const districtService = useDistrictService();
const profileService = useProfileService();
const orderService = useOrderService();
const cartService = useCartService();
const { trans } = useTrans();
const { phoneNumber } = useValidate();

let stripe = null;
let elements = null;
let card = null;
let clientSecret = null;
const cities = ref([]);
const districts = ref([]);

const formData = reactive({
  user_id: null,
  name: null,
  phone: null,
  city_id: "",
  district_id: "",
  address: null,
  ship_method: 1,
  payment_method: 1,
  total: null,
  items: [],
});

onMounted(async () => {
  await cityService.getAll().then((res) => {
    cities.value = res.data;
  });

  formData.total = totalPriceComputed.value;
  formData.items = userStore.checkout;

  if (sessionStore.id && sessionStore.isCustomerLogin) {
    formData.user_id = sessionStore.id;
    await profileService.getProfile(sessionStore.id).then((res) => {
      formData.name = res.data.name;
      formData.phone = res.data.phone;
      formData.city_id = res.data.city_id;
      formData.district_id = res.data.district_id;
      formData.address = res.data.address;
    });
  }
  stripeInit();
});

watch(
  () => formData.city_id,
  async () => {
    await districtService.getDistrictByCityId(formData.city_id).then((res) => {
      districts.value = res.data;
    });
  }
);

const stripeInit = async () => {
  const runtimeConfig = useRuntimeConfig();
  stripe = Stripe(`${runtimeConfig.public.stripePk}`);

  let res = await $fetch(`/api/stripe/paymentintent`, {
    method: "POST",
    body: {
      amount: Math.round(formData.total),
    },
  });

  clientSecret = res.client_secret;

  elements = stripe.elements();

  var style = {
    base: {
      fontSize: "18px",
    },
    invalid: {
      fontFamily: "Arial, sans-serif",
      color: "#ee4b2b",
      iconColor: "#ee4b2b",
    },
  };

  card = elements.create("card", {
    hidePostalCode: true,
    style: style,
  });

  card.mount("#card-element");

  card.on("change", function (event: any) {
    document.querySelector("#card-error").textContent = event.error
      ? event.error.message
      : "";
  });
};

const totalPriceComputed = computed(() => {
  let price = 0;
  userStore.checkout.forEach((item) => {
    price += item.price * item.quantity;
  });
  return price;
});

const pay = async () => {
  let result = await stripe.confirmCardPayment(clientSecret, {
    payment_method: { card: card },
  });

  return result;
};

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
    sessionStore.isLoading = true;
    if (formData.payment_method == 2) {
      let payment_result = await pay();
      if (payment_result.error) {
        showError(result.error.message);
        setTimeout(() => {
          sessionStore.isLoading = false;
        }, 200);
        return;
      }
    }
    const res = await orderService.create(formData);
    if (res.status) {
      showAlert();
      userStore.checkout.forEach((prod) => {
        userStore.cart.forEach(async (item, index) => {
          if (
            prod.product_id == item.product_id &&
            prod.color_id == item.color_id &&
            prod.material_id == item.material_id &&
            prod.size_id == item.size_id
          ) {
            userStore.cart.splice(index, 1);
            if (sessionStore.id) {
              await cartService.delete(prod.id);
            }
          }
        });
      });
      userStore.checkout = [];
      setTimeout(() => {
        sessionStore.isLoading = false;
      }, 200);
      return navigateTo("/");
    }
  }
};

const showError = (errorMsgText: string) => {
  let errorMsg = document.querySelector("#card-error");
  errorMsg.textContent = errorMsgText;
  setTimeout(() => {
    errorMsg.textContent = "";
  }, 4000);
};

const showAlert = () => {
  const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.onmouseenter = Swal.stopTimer;
      toast.onmouseleave = Swal.resumeTimer;
    },
  });
  Toast.fire({
    icon: "success",
    title: "Đặt hàng thành công",
  });
};
</script>
<template>
  <MainLayout>
    <div class="w-[1200px] mx-auto">
      <div class="py-10">
        <ul>
          <NuxtLink class="text-sm text-[#676767] italic font-light"
            >Trang chủ
            <span class="px-2"> / </span>
          </NuxtLink>
          <NuxtLink class="text-sm text-[#676767] italic font-light"
            >Giỏ hàng <span class="px-2"> / </span></NuxtLink
          >
          <NuxtLink class="text-sm text-[#676767] italic font-light"
            >Thanh toán</NuxtLink
          >
        </ul>
      </div>
      <div class="flex gap-5">
        <div class="w-[55%]">
          <h3 class="text-2xl font-semibold">Thông tin giao hàng</h3>
          <div class="mt-5">
            <div class="flex gap-8">
              <div class="w-full">
                <label for="name" class="text-sm font-light"
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
              <div class="w-full">
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
            </div>
            <div class="mt-3 flex gap-8">
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
            <h4 class="mt-5 font-semibold">Hình thức giao hàng</h4>
            <div class="border border-[#676767] rounded-md mt-3">
              <div
                class="px-6 py-4 flex justify-between items-center border-b border-[#676767]"
              >
                <div class="flex items-center gap-3">
                  <input
                    type="radio"
                    id="home"
                    name="ship_method"
                    value="1"
                    class="w-4 h-4"
                    v-model="formData.ship_method"
                  />
                  <label for="home"
                    >TOÀN QUỐC - Giao hàng tận nơi (có phí)</label
                  >
                </div>
                <div class="text-sm font-semibold">
                  0<span class="underline">đ</span>
                </div>
              </div>
              <div class="px-6 py-4 flex justify-between items-center">
                <div class="flex items-center gap-3">
                  <input
                    type="radio"
                    id="shop"
                    name="ship_method"
                    value="2"
                    class="w-4 h-4"
                    v-model="formData.ship_method"
                  />
                  <label for="shop">Tôi sẽ nhận tại cửa hàng</label>
                </div>
                <div class="text-sm font-semibold">
                  0<span class="underline">đ</span>
                </div>
              </div>
            </div>
            <h4 class="mt-5 font-semibold">Hình thức thanh toán</h4>
            <div class="border border-[#676767] rounded-md mt-3">
              <div
                class="px-6 py-4 flex items-center gap-3 border-b border-[#676767]"
              >
                <input
                  type="radio"
                  id="delivery"
                  name="pay_method"
                  value="1"
                  class="w-4 h-4"
                  v-model="formData.payment_method"
                />
                <img
                  src="https://hstatic.net/0/0/global/design/seller/image/payment/cod.svg?v=6"
                />
                <label for="delivery">Thanh toán khi nhận hàng</label>
              </div>
              <div class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <input
                    type="radio"
                    id="card"
                    name="pay_method"
                    value="2"
                    class="w-4 h-4"
                    v-model="formData.payment_method"
                  />
                  <img
                    src="https://hstatic.net/0/0/global/design/seller/image/payment/other.svg?v=6"
                  />
                  <label for="card">Thanh toán online</label>
                </div>
                <div v-show="formData.payment_method == 2" class="mt-3">
                  <div
                    class="border border-gray-500 p-2 rounded-sm"
                    id="card-element"
                  />
                  <p
                    id="card-error"
                    role="alert"
                    class="text-red-700 font-semibold"
                  ></p>
                </div>
              </div>
            </div>
            <div class="flex flex-row-reverse">
              <button
                @click="submit()"
                class="mt-5 px-10 py-3 bg-[#1990C6] text-white text-sm font-bold rounded hover:bg-[#1EADEE]"
              >
                Đặt hàng
              </button>
            </div>
          </div>
        </div>
        <div class="w-[45%] bg-[#FAFAFA] px-5 py-8">
          <div class="flex flex-col gap-8 pb-10 border-b border-black">
            <div v-for="product in userStore.checkout" :key="product">
              <CheckoutItem :product="product" />
            </div>
          </div>
          <div class="py-5 px-4 border-b border-black">
            <div class="flex items-center justify-between">
              <p class="text-sm">Tạm tính:</p>
              <p class="text-sm font-semibold">
                {{ price(totalPriceComputed) }}đ
              </p>
            </div>
            <div class="flex items-center justify-between mt-4">
              <p class="text-sm">Phí vận chuyển:</p>
              <p class="text-sm font-semibold">
                Nhân viên sẽ liên hệ và báo phí vận chuyển
              </p>
            </div>
          </div>
          <div class="flex items-end justify-between mt-5 px-4">
            <p class="text-xl">Tổng:</p>
            <p class="text-3xl font-semibold">
              {{ price(totalPriceComputed) }}đ
            </p>
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>