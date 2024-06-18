<script setup lang="ts">
import StoreLayout from "~/layouts/store.vue";
// import { useUtils } from "~/composables/utils";
import { useSessionStore } from "~/stores/session";
import { useOrderService } from "~/services/order";

definePageMeta({ middleware: "store" });

const { price } = useUtils();
const sessionStore = useSessionStore();
const orderService = useOrderService();

const orders = ref([]);
const order = ref({});
const formData = reactive({
  date: null,
  status: "",
});
const isShowDialog = ref(false);
const isConfirmDialog = ref(false);
const itemsPerPage = ref(10);
const currentPage = ref(1);
const confirmId = ref(null);

onMounted(() => {
  const today = new Date();

  const day = today.getDate();
  const month = today.getMonth() + 1;
  const year = today.getFullYear();

  formData.date =
    year +
    "-" +
    (month < 10 ? "0" + month : month) +
    "-" +
    (day < 10 ? "0" + day : day);

  search();
});

const search = async () => {
  sessionStore.isLoading = true;
  const res = await orderService.search(formData);
  orders.value = res.data;
  setTimeout(() => {
    sessionStore.isLoading = false;
  }, 500);
};

const totalOrders = computed(() => {
  return orders.value.length;
});

const totalPages = computed(() => {
  return Math.ceil(totalOrders.value / itemsPerPage.value);
});

const displayOrders = computed(() => {
  const startIndex = (currentPage.value - 1) * itemsPerPage.value;
  const endIndex = startIndex + itemsPerPage.value;
  return orders.value.slice(startIndex, endIndex);
});

const getCurrentPage = (i: any) => {
  currentPage.value = i;
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
};

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
  }
};

const pagesToShow = computed(() => {
  const pages = [];
  const startPage = Math.max(1, currentPage.value - 2);
  const endPage = Math.min(totalPages.value, currentPage.value + 2);
  for (let i = startPage; i <= endPage; i++) {
    pages.push(i);
  }
  return pages;
});

const updateOrder = async () => {
  const index = orders.value.findIndex((o) => o.id == confirmId.value);
  orders.value[index].status++;

  let formData = new FormData();

  formData.append("status", orders.value[index].status);
  formData.append("_method", "PATCH");

  await orderService.update(confirmId.value, formData);

  isConfirmDialog.value = false;
};

const exportInvoice = (o: object) => {
  var mywindow = window.open("#", "PRINT", "height=800,width=1200");

  mywindow?.document.write("<html><head><title>" + document.title + "</title>");
  mywindow?.document.write("</head><body >");
  mywindow?.document.write("<h1>" + document.title + "</h1>");
  // mywindow?.document.write(document.getElementById("content").innerHTML);
  mywindow?.document.write(
    `<div>
    <div>
      <span style="font-size: 20px">WOODPRO</span
      ><span style="font-size: 12px; margin-left: 140px"
        >Đơn Hàng #` +
      o.order_id +
      `</span
      >
    </div>
    <div style="font-size: 10px">
      <p>90-92 Đường B2, Khu đô thị Sala, An Lợi Đông, Thủ Đức, HCM</p>
      <p>info@gmail.com</p>
      <p>0987.654.321</p>
    </div>
    <hr />
    <div>
      <p><b>Thông tin khách hàng:</b></p>
      <p style="margin-top: -10px">` +
      o.name +
      `</p>
      <div style="font-size: 10px; margin-top: -10px">
        <span>Địa chỉ: </span>
        <span>` +
      o.address +
      `, </span>
        <span>` +
      o.district +
      `, </span>
        <span>` +
      o.city +
      `</span>
      </div>
      <p style="font-size: 10px">Số điện thoại: ` +
      o.phone +
      `</p>
      <p style="font-size: 10px">Ngày đặt: ` +
      o.date +
      `</p>
    </div>
    <hr />
    <div>
      <p><b>Thông tin đơn hàng:</b></p>`
  );

  o.items.forEach((item, i) => {
    mywindow?.document.write(
      `
        <div style="font-size: 12px; margin-top: -5px">
          <p>` +
        (i + 1) +
        `. ` +
        item.product_name +
        `</p>
        <p style="margin-top: -5px;">
          <span>` +
        item.color_name +
        `</span>
          <span style="margin-left: 15px;">` +
        item.length +
        ` x ` +
        item.width +
        ` x ` +
        item.height +
        `</span>
          <span style="margin-left: 15px;">` +
        item.material_name +
        `</span>
        </p>
        <p style="margin-top: -5px;">
          <span>Số lượng: ` +
        item.quantity +
        `</span>
          <span style="margin-left: 200px">Giá: <b>` +
        price(item.price) +
        `đ</b></span>
        </p>
        </div>
      `
    );
  });

  mywindow?.document.write(
    `
      <hr />
      <p style="text-align: right; font-size: 12px">Tổng tiền: <span style="font-size: 20px"><b>` +
      price(o.total) +
      `đ</b></span></p>
      <p style="margin-top: 75px; text-align: center; font-size: 12px">Cảm ơn đã mua sản phẩm của chúng tôi!!!</p>
    `
  );

  mywindow?.document.write(`</div>
  </div>`);
  mywindow?.document.write("</body></html>");

  // mywindow.document.close(); // necessary for IE >= 10
  mywindow?.focus(); // necessary for IE >= 10*/

  mywindow?.print();
  mywindow?.close();
};
</script>
<template>
  <StoreLayout>
    <div class="bg-[#F6F6F6] h-full py-5 px-8">
      <h3 class="text-xl font-bold border-b border-[#676767] py-2">
        Danh sách đơn hàng
      </h3>
      <div class="bg-white mt-5">
        <div class="p-5">
          <form class="flex gap-5" @submit.prevent="search()">
            <div>
              <label for="date">Ngày đặt</label><br />
              <input
                type="date"
                id="date"
                v-model="formData.date"
                class="border border-black mt-2 p-1"
              />
            </div>
            <div>
              <label for="status">Trạng thái</label><br />
              <select
                id="status"
                class="border border-black mt-2 p-[7px]"
                v-model="formData.status"
              >
                <option value="" selected>Tất cả</option>
                <option value="1">Chờ xác nhận</option>
                <option value="2">Chờ lấy hàng</option>
                <option value="3">Đang giao hàng</option>
                <option value="4">Đã hoàn thành</option>
                <option value="5">Đã hủy</option>
              </select>
            </div>
            <button
              type="submit"
              class="border border-black py-1 px-2.5 mt-8 text-sm bg-[#DCDEE0] rounded-sm"
            >
              Tìm kiếm
            </button>
          </form>
        </div>
        <div class="px-5 pb-10">
          <table class="w-full">
            <tr class="text-center font-semibold border-b border-black">
              <td class="w-[150px] pb-2">Mã đơn hàng</td>
              <td class="w-[200px] pb-2">Tên người nhận</td>
              <td class="w-[150px] pb-2">Ngày đặt</td>
              <td class="w-[150px] pb-2">Tổng tiền</td>
              <td class="w-[150px] pb-2">Trạng thái</td>
              <td>Thao tác</td>
            </tr>
            <tr v-for="o in displayOrders" :key="o" class="text-center">
              <td class="pt-2.5 pb-2 border-b">{{ o.order_id }}</td>
              <td class="pt-2.5 pb-2 border-b">{{ o.name }}</td>
              <td class="pt-2.5 pb-2 border-b">{{ o.date }}</td>
              <td class="pt-2.5 pb-2 border-b">{{ price(o.total) }}đ</td>
              <td class="pt-2.5 pb-2 border-b">
                <span
                  v-if="o.status == 1"
                  class="bg-red-500 px-2 py-1 font-bold text-xs text-white"
                  >Chờ xác nhận</span
                >
                <span
                  v-if="o.status == 2"
                  class="bg-orange-500 px-2 py-1 font-bold text-xs text-white"
                  >Chờ lấy hàng</span
                >
                <span
                  v-if="o.status == 3"
                  class="bg-yellow-500 px-2 py-1 font-bold text-xs text-white"
                  >Đang giao hàng</span
                >
                <span
                  v-if="o.status == 4"
                  class="bg-green-500 px-2 py-1 font-bold text-xs text-white"
                  >Đã hoàn thành</span
                >
                <span
                  v-if="o.status == 5"
                  class="bg-purple-500 px-2 py-1 font-bold text-xs text-white"
                  >Đã hủy</span
                >
              </td>
              <td
                class="pt-3.5 pb-2 border-b flex items-center justify-center gap-2"
              >
                <button
                  @click="(isShowDialog = true), (order = o)"
                  class="bg-[#1EADEE] px-2 py-1 font-bold text-xs text-white"
                >
                  chi tiết
                </button>
                <button
                  v-if="o.status != 4 && o.status != 5"
                  @click="(isConfirmDialog = true), (confirmId = o.id)"
                  class="bg-green-600 px-2 py-1 font-bold text-xs text-white"
                >
                  xác nhận
                </button>
                <button
                  @click="exportInvoice(o)"
                  class="bg-green-500 px-2 py-1 font-bold text-xs text-white"
                >
                  in
                </button>
              </td>
            </tr>
          </table>
          <div class="mt-[40px] flex items-center justify-center gap-[5px]">
            <button
              @click="prevPage"
              :disabled="currentPage === 1"
              class="w-[24px] h-[24px] flex items-center justify-center border border-[#0000001A] rounded text-[14px] text-[#0000001A] leading-[16.94px]"
            >
              <
            </button>
            <button
              v-for="i in pagesToShow"
              :key="i"
              class="w-[24px] h-[24px] flex items-center justify-center border border-[#0000001A] rounded text-[14px] leading-[16.94px]"
              :class="
                i === currentPage
                  ? 'text-white bg-[#007006]'
                  : 'text-[#00000066]'
              "
              @click="getCurrentPage(i)"
            >
              {{ i }}
            </button>
            <button
              @click="nextPage"
              :disabled="currentPage === totalPages"
              class="w-[24px] h-[24px] flex items-center justify-center border border-[#0000001A] rounded text-[14px] text-[#0000001A] leading-[16.94px]"
            >
              >
            </button>
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
          <div>
            <p>
              <b>Địa chỉ:</b>
              <span class="ml-5">{{ order.address }}, </span>
              <span>{{ order.district }}, </span>
              <span>{{ order.city }}</span>
            </p>
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
  </StoreLayout>
  <div
    v-if="isConfirmDialog"
    class="fixed flex justify-center z-50 top-0 left-0 w-full h-full bg-black bg-opacity-50 overflow-auto"
  >
    <div class="w-[500px] h-[200px] m-auto bg-white rounded-lg pt-[50px]">
      <div class="text-center leading-[19.36px]">
        Xác nhận chuyển trạng thái đơn hàng
      </div>
      <div class="mt-[40px] flex justify-center gap-[25px]">
        <button
          @click="isConfirmDialog = false"
          class="px-[26px] py-[10px] border border-[#00000033] rounded text-[#00000080] leading-[19.36px]"
        >
          Hủy bỏ
        </button>
        <button
          @click="updateOrder()"
          class="px-[25px] py-[10px] bg-[#D0011B] border border-[#D0011B] rounded text-white leading-[19.36px]"
        >
          Xác nhận
        </button>
      </div>
    </div>
  </div>
  <div id="content" v-show="false">
    <div>
      <span style="font-size: 20px">WOODPRO</span
      ><span style="font-size: 12px; margin-left: 140px"
        >Đơn Hàng #{{ order.order_id }}</span
      >
    </div>
    <div style="font-size: 10px">
      <p>90-92 Đường B2, Khu đô thị Sala, An Lợi Đông, Thủ Đức, HCM</p>
      <p>info@gmail.com</p>
      <p>0987.654.321</p>
    </div>
    <hr />
    <div>
      <p><b>Thông tin khách hàng:</b></p>
      <p style="margin-top: -10px">{{ order.name }}</p>
      <div style="font-size: 10px; margin-top: -10px">
        <span>Địa chỉ: </span>
        <span>{{ order.address }}, </span>
        <span>{{ order.district }}, </span>
        <span>{{ order.city }}</span>
      </div>
      <p style="font-size: 10px">Số điện thoại: {{ order.phone }}</p>
      <p style="font-size: 10px">Ngày đặt: {{ order.date }}</p>
    </div>
    <hr />
    <div>
      <p><b>Thông tin đơn hàng:</b></p>
      <div v-for="(o, i) in order.items" :key="i">
        <p>{{ i + 1 }}. {{ o.product_name }}</p>
      </div>
    </div>
  </div>
</template>