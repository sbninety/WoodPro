<script setup lang="ts">
import MainLayout from "~/layouts/default.vue";
import { useUtils } from "~/composables/utils";
import { useSessionStore } from "~/stores/session";
import { useOrderService } from "~/services/order";
import { useProfileService } from "~/services/profile";

definePageMeta({ middleware: "order" });

const orderService = useOrderService();
const sessionStore = useSessionStore();
const profileService = useProfileService();
const { price } = useUtils();

const orders = ref([]);
const order = ref({});
const profile = ref({});
const active = ref(1);
const isShowDialog = ref(false);
const isCancelDialog = ref(false);
const cancelId = ref(null);

onMounted(async () => {
  sessionStore.isLoading = true;
  const res = await Promise.all([
    orderService.getOrderByUser(sessionStore.id),
    profileService.getProfile(sessionStore.id),
  ]);
  orders.value = res[0].data;
  profile.value = res[1].data;
  setTimeout(() => {
    sessionStore.isLoading = false;
  }, 200);
});

const ordersDisplay = computed(() => {
  return orders.value.filter((i) => i.status == active.value);
});

const updateOrder = async () => {
  const index = orders.value.findIndex((o) => o.id == cancelId.value);
  orders.value[index].status = 5;

  let formData = new FormData();

  formData.append("status", "5");
  formData.append("_method", "PATCH");

  await orderService.update(cancelId.value, formData);

  isCancelDialog.value = false;
};
</script>
<template>
  <MainLayout>
    <div
      style="
        background-image: url('https://theme.hstatic.net/1000280685/1000722794/14/bg_header_image.png?v=1506');
      "
      class="h-[250px] relative"
    >
      <h2
        class="absolute bottom-10 left-[150px] text-5xl text-[#676767] font-medium"
      >
        Lịch sử đặt hàng
      </h2>
    </div>
    <div class="w-[1200px] mx-auto flex mt-10 gap-5">
      <div class="w-[70%]">
        <h3 class="text-2xl text-[#676767] font-semibold">Đơn hàng của bạn</h3>
        <div class="flex mt-5">
          <div
            class="w-1/4 flex items-center justify-center py-2 text-sm text-[#676767]"
            :class="
              active === 1
                ? 'bg-[#EDEDED] font-bold'
                : 'bg-[#ddd] font-light hover:bg-[#EDEDED] hover:text-[#a8a88f] hover:font-medium cursor-pointer'
            "
            @click="active = 1"
          >
            Chờ xác nhận
          </div>
          <div
            class="w-1/4 text-center p-2 text-sm text-[#676767] border-l-2 border-r-2 border-[#ddd]"
            :class="
              active === 2
                ? 'bg-[#EDEDED] font-bold'
                : 'bg-[#ddd] font-light hover:bg-[#EDEDED] hover:text-[#a8a88f] hover:font-medium cursor-pointer'
            "
            @click="active = 2"
          >
            Chờ lấy hàng
          </div>
          <div
            class="w-1/4 flex items-center justify-center py-2 text-sm text-[#676767] border-l-2 border-r-2 border-[#ddd]"
            :class="
              active === 3
                ? 'bg-[#EDEDED] font-bold'
                : 'bg-[#ddd] font-light hover:bg-[#EDEDED] hover:text-[#a8a88f] hover:font-medium cursor-pointer'
            "
            @click="active = 3"
          >
            Đang giao hàng
          </div>
          <div
            class="w-1/4 flex items-center justify-center py-2 text-sm text-[#676767]"
            :class="
              active === 4
                ? 'bg-[#EDEDED] font-bold'
                : 'bg-[#ddd] font-light hover:bg-[#EDEDED] hover:text-[#a8a88f] hover:font-medium cursor-pointer'
            "
            @click="active = 4"
          >
            Đã hoàn thành
          </div>
          <div
            class="w-1/4 flex items-center justify-center py-2 text-sm text-[#676767]"
            :class="
              active === 5
                ? 'bg-[#EDEDED] font-bold'
                : 'bg-[#ddd] font-light hover:bg-[#EDEDED] hover:text-[#a8a88f] hover:font-medium cursor-pointer'
            "
            @click="active = 5"
          >
            Đã hủy
          </div>
        </div>
        <div class="mt-3">
          <table>
            <thead class="border-b-2 border-[#676767]">
              <th class="w-[250px] py-2">Mã đơn hàng</th>
              <th class="w-[250px] py-2">Người nhận</th>
              <th class="w-[200px] py-2">Ngày đặt</th>
              <th class="w-[200px] py-2">Tổng tiền</th>
              <th class="w-[200px] py-2">Thao tác</th>
            </thead>
            <tbody>
              <tr v-for="o in ordersDisplay" :key="o">
                <td class="text-center py-2">{{ o.order_id }}</td>
                <td class="text-center py-2">{{ o.name }}</td>
                <td class="text-center py-2">{{ o.date }}</td>
                <td class="text-center py-2">{{ price(o.total) }}đ</td>
                <td class="text-center py-2">
                  <button
                    @click="(isShowDialog = true), (order = o)"
                    class="bg-green-600 text-xs text-white px-2 py-1 font-semibold"
                  >
                    <p>chi tiết</p>
                  </button>
                  <button
                    v-if="active == 1"
                    @click="(isCancelDialog = true), (cancelId = o.id)"
                    class="bg-red-600 ml-2 text-xs text-white px-2 py-1 font-semibold"
                  >
                    <p>hủy</p>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="w-[30%]">
        <div class="bg-[#545454] text-white px-4 py-3 font-semibold">
          Thông tin của bạn
        </div>
        <div class="bg-[#FAFAFA] pb-8 pt-3">
          <p class="text-sm px-5">
            Họ tên: <span class="font-semibold ml-20">{{ profile.name }}</span>
          </p>
          <p class="text-sm mt-3 px-5">
            Số điện thoại:
            <span class="font-semibold ml-9">{{ profile.phone }}</span>
          </p>
          <p class="text-sm mt-3 px-5">
            Thành phố:
            <span class="font-semibold ml-[52px]">{{ profile.city }}</span>
          </p>
          <p class="text-sm mt-3 px-5">
            Quận/huyện:
            <span class="font-semibold ml-10">{{ profile.district }}</span>
          </p>
          <p class="text-sm mt-3 px-5">Địa chỉ:</p>
          <p class="text-sm mt-2 px-5 font-semibold">{{ profile.address }}</p>
          <div class="px-5 mt-5">
            <NuxtLink
              to="/account/address"
              class="bg-black text-white font-light uppercase px-4 py-2 text-sm hover:bg-[#A8A88F]"
            >
              Sửa thông tin
            </NuxtLink>
          </div>
        </div>
      </div>
    </div>
    <div
      v-if="isShowDialog"
      class="fixed bg-black bg-opacity-40 inset-0 w-full z-50 flex items-center justify-center h-[100vh] overflow-hidden"
    >
      <div
        class="relative rounded-md bg-white w-[950px] h-[460px] pb-5 overflow-auto"
      >
        <button
          class="absolute right-0 bg-black p-1.5"
          @click="isShowDialog = false"
        >
          <Icon name="mdi:close" color="#FFFFFF" size="25" />
        </button>
        <div class="px-8 mt-5">
          <div class="flex">
            <div class="w-1/2 py-2">
              <p>
                <b>Người nhận:</b>
                <span class="ml-[94px]">{{ order.name }}</span>
              </p>
            </div>
            <div class="w-1/2 py-2">
              <p>
                <b>Số điện thoại:</b>
                <span class="ml-[85px]">{{ order.phone }}</span>
              </p>
            </div>
          </div>
          <div class="flex">
            <div class="w-1/2 py-2">
              <p>
                <b>Ngày đặt:</b> <span class="ml-28">{{ order.date }}</span>
              </p>
            </div>
            <div class="w-1/2 py-2">
              <p>
                <b>Tổng tiền:</b>
                <span class="ml-[115px]">{{ price(order.total) }}đ</span>
              </p>
            </div>
          </div>
          <div class="flex">
            <div class="w-1/2 py-2">
              <p>
                <b>Hình thức giao hàng:</b>
                <span class="ml-5" v-if="order.ship_method == 1"
                  >Giao hàng tận nơi</span
                >
                <span class="ml-5" v-else>Nhận tại cửa hàng</span>
              </p>
            </div>
            <div class="w-1/2 py-2">
              <p>
                <b>Hình thức thanh toán:</b>
                <span class="ml-5" v-if="order.payment_method == 1"
                  >Thanh toán khi nhận hàng</span
                >
                <span class="ml-5" v-else>Thanh toán online</span>
              </p>
            </div>
          </div>
          <div class="mt-2">
            <p><b>Danh sách sản phẩm:</b></p>
            <div class="mt-5" v-for="i in order.items" :key="i">
              <div
                class="flex items-center justify-between bg-[#FAFAFA] p-4 rounded-md"
              >
                <div class="flex gap-3">
                  <div class="relative w-[80px] h-[80px]">
                    <img
                      :src="i.product_image"
                      class="rounded-md border-2 border-[#676767] w-[80px] h-[80px]"
                    />
                    <div
                      class="absolute w-5 h-5 bg-[#A3A3A3] rounded-full flex items-center justify-center text-white text-xs font-bold -top-2 -right-2"
                    >
                      {{ i.quantity }}
                    </div>
                  </div>
                  <div>
                    <h3 class="text-sm font-semibold w-[250px]">
                      {{ i.product_name }}
                    </h3>
                    <div class="flex mt-2 gap-3">
                      <div
                        class="bg-[#EEE3CE] px-1 py-1 border border-[#b58023] text-[#b58023] text-[11px] rounded-lg"
                      >
                        {{ i.color_name }}
                      </div>
                      <div
                        class="bg-white px-1.5 py-1 border border-[#676767] text-[11px] rounded-lg"
                      >
                        {{ i.length }} x {{ i.width }} x {{ i.height }}
                      </div>
                      <div
                        class="bg-white px-1.5 py-1 border border-[#74B018] text-[#74B018] text-[11px] rounded-lg"
                      >
                        {{ i.material_name }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="font-semibold text-[15px]">
                  {{ price(i.price) }}đ
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
  <div
    v-if="isCancelDialog"
    class="fixed flex justify-center z-50 top-0 left-0 w-full h-full bg-black bg-opacity-50 overflow-auto"
  >
    <div class="w-[600px] h-[250px] m-auto bg-white rounded-lg pt-[80px]">
      <div class="text-center leading-[19.36px]">
        Bạn có muốn hủy đơn hàng này không? <br /><br />
        Nhân viên sẽ liên hệ hoàn tiền nếu đơn hàng đã thanh toán.
      </div>
      <div class="mt-[40px] flex justify-center gap-[25px]">
        <button
          @click="isCancelDialog = false"
          class="px-[26px] py-[10px] border border-[#00000033] rounded text-[#00000080] leading-[19.36px]"
        >
          Không
        </button>
        <button
          @click="updateOrder()"
          class="px-[48px] py-[10px] bg-[#D0011B] border border-[#D0011B] rounded text-white leading-[19.36px]"
        >
          Có
        </button>
      </div>
    </div>
  </div>
</template>