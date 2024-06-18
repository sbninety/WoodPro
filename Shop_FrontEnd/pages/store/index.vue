<script setup lang="ts">
import StoreLayout from "~/layouts/store.vue";
import { useUtils } from "~/composables/utils";
import { useOrderService } from "~/services/order";
import { useProductService } from "~/services/product";
import { useUserService } from "~/services/user";
import { useSessionStore } from "~/stores/session";

definePageMeta({ middleware: "store" });

const sessionStore = useSessionStore();
const orderService = useOrderService();
const productService = useProductService();
const userService = useUserService();
const { months } = useUtils();

const totalProduct = ref(null);
const totalOrder = ref(null);
const totalCustomer = ref(null);
const staffLog = ref([]);
const worstSellingProducts = ref([]);
const bestSellingProducts = ref([]);
const bestSellKey = reactive({
  month: 4,
  year: 2024,
});

const worstSellKey = reactive({
  month: 4,
  year: 2024,
});

const chart = ref();

onMounted(async () => {
  sessionStore.isLoading = true;

  const currentDate = new Date();
  bestSellKey.month = currentDate.getMonth() + 1;
  bestSellKey.year = currentDate.getFullYear();
  worstSellKey.month = currentDate.getMonth() + 1;
  worstSellKey.year = currentDate.getFullYear();

  const [
    resTotalProduct,
    resTotalOrder,
    resTotalCustomer,
    resStaffLog,
    resRevenue,
    resBestSellingProducts,
    resWorstSellingProducts,
  ] = await Promise.all([
    productService.count(),
    orderService.count(),
    userService.countCustomer(),
    userService.getStaffLog(),
    orderService.getRevenue(),
    productService.getBestSellingProducts(bestSellKey),
    productService.getWorstSellingProducts(worstSellKey),
  ]);

  totalProduct.value = resTotalProduct.data;
  totalOrder.value = resTotalOrder.data;
  totalCustomer.value = resTotalCustomer.data;
  staffLog.value = resStaffLog.data;
  worstSellingProducts.value = resWorstSellingProducts.data;
  bestSellingProducts.value = resBestSellingProducts.data;

  initRevenue(resRevenue.data);

  setTimeout(() => {
    sessionStore.isLoading = false;
  }, 200);
});

const initRevenue = (figures) => {
  const currentDate = new Date();
  const currentMonth = currentDate.getMonth() + 1;

  let data = Array.from({ length: currentMonth }, () => 0);
  figures.forEach((item) => {
    const month = item.month;
    const total = item.total;
    data[month - 1] = total;
  });

  const ctx = document.getElementById("myChart");
  const labels = months({ count: currentMonth });
  new Chart(ctx, {
    type: "line",
    data: {
      labels: labels,
      datasets: [
        {
          data: data,
          backgroundColor: [
            "rgba(255, 99, 132, 0.2)",
            "rgba(255, 159, 64, 0.2)",
            "rgba(255, 205, 86, 0.2)",
            "rgba(75, 192, 192, 0.2)",
            "rgba(54, 162, 235, 0.2)",
            "rgba(153, 102, 255, 0.2)",
            "rgba(201, 203, 207, 0.2)",
            "rgba(153, 102, 255, 0.2)",
            "rgba(201, 203, 207, 0.2)",
            "rgba(153, 102, 255, 0.2)",
            "rgba(201, 203, 207, 0.2)",
            "rgba(153, 102, 255, 0.2)",
          ],
          borderColor: [
            "rgb(255, 99, 132)",
            "rgb(255, 159, 64)",
            "rgb(255, 205, 86)",
            "rgb(75, 192, 192)",
            "rgb(54, 162, 235)",
            "rgb(153, 102, 255)",
            "rgb(201, 203, 207)",
            "rgb(153, 102, 255)",
            "rgb(201, 203, 207)",
            "rgb(153, 102, 255)",
            "rgb(201, 203, 207)",
            "rgb(153, 102, 255)",
          ],
          borderWidth: 1,
        },
      ],
    },
    options: {
      plugins: {
        legend: {
          display: false,
        },
      },
    },
  });
};

const searchBestSell = async () => {
  const res = await productService.getBestSellingProducts(bestSellKey);
  if (res.data) {
    bestSellingProducts.value = res.data;
  } else {
    alert("Không có dữ liệu");
  }
};

const searchWorstSell = async () => {
  const res = await productService.getWorstSellingProducts(worstSellKey);
  worstSellingProducts.value = res.data;
};
</script>
<template>
  <StoreLayout>
    <div class="bg-[#F6F6F6] h-full py-5 px-8">
      <h3 class="text-xl font-bold border-b border-[#676767] py-2">Thống kê</h3>
      <div class="mt-5 flex gap-8">
        <div class="w-1/3 bg-white rounded-lg border flex items-center p-4">
          <div>
            <Icon
              name="mdi:sofa"
              size="80"
              color="#FFFFFF"
              class="bg-red-500"
            />
          </div>
          <div class="flex-1 text-right">
            <h4><b>Tổng sản phẩm</b></h4>
            <p class="mt-1 text-sm">Số lượng: {{ totalProduct }}</p>
          </div>
        </div>
        <div class="w-1/3 bg-white rounded-lg border flex items-center p-4">
          <div>
            <Icon
              name="mdi:order-bool-descending-variant"
              size="80"
              color="#FFFFFF"
              class="bg-green-600"
            />
          </div>
          <div class="flex-1 text-right">
            <h4><b>Tổng đơn hàng</b></h4>
            <p class="mt-1 text-sm">Số lượng: {{ totalOrder }}</p>
          </div>
        </div>
        <div class="w-1/3 bg-white rounded-lg border flex items-center p-4">
          <div>
            <Icon
              name="mdi:account-group"
              size="80"
              color="#FFFFFF"
              class="bg-[#00CAE3]"
            />
          </div>
          <div class="flex-1 text-right">
            <h4><b>Tổng khách hàng</b></h4>
            <p class="mt-1 text-sm">Số lượng: {{ totalCustomer }}</p>
          </div>
        </div>
      </div>
      <div class="flex mt-5 gap-4">
        <div class="w-8/12 bg-white">
          <h3 class="font-bold text-xl mt-3 mx-5">Doanh thu từng tháng</h3>
          <canvas id="myChart" class="mt-3"></canvas>
        </div>
        <div class="w-4/12 bg-white pt-2">
          <h4 class="mx-5 pb-1 font-bold border-b">Hoạt động nhân viên</h4>
          <table class="mt-3 w-full">
            <thead>
              <th class="py-1 w-[180px] border border-[#DDDDDD]">Nhân viên</th>
              <th class="py-1 border border-[#DDDDDD]">Thời gian</th>
            </thead>
            <tbody>
              <tr
                class="text-center text-[15px]"
                v-for="i in staffLog"
                :key="i"
              >
                <td class="border border-[#DDDDDD] py-2">{{ i.name }}</td>
                <td class="border border-[#DDDDDD] py-2">{{ i.login_at }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="bg-white mt-5 p-4">
        <h3 class="font-bold text-xl">Sản phẩm bán chạy</h3>
        <div class="flex items-center justify-between mt-5">
          <div>
            <form class="flex gap-5" @submit.prevent="searchBestSell()">
              <div class="flex">
                <button
                  type="submit"
                  class="border border-[#BDBDBD] p-1.5 rounded-l"
                >
                  <Icon
                    name="ic:outline-filter-alt"
                    size="27"
                    color="#262626"
                  />
                </button>
                <div class="relative w-[150px]">
                  <select
                    class="w-full border border-[#BDBDBD] text-sm px-3 py-2.5 rounded-r appearance-none focus:outline-none"
                    v-model="bestSellKey.month"
                  >
                    <option value="" selected disabled>Tháng</option>
                    <option value="1">Tháng 1</option>
                    <option value="2">Tháng 2</option>
                    <option value="3">Tháng 3</option>
                    <option value="4">Tháng 4</option>
                    <option value="5">Tháng 5</option>
                    <option value="6">Tháng 6</option>
                    <option value="7">Tháng 7</option>
                    <option value="8">Tháng 8</option>
                    <option value="9">Tháng 9</option>
                    <option value="10">Tháng 10</option>
                    <option value="11">Tháng 11</option>
                    <option value="12">Tháng 12</option>
                  </select>
                  <div class="absolute right-2 top-1.5">
                    <Icon name="ic:round-arrow-drop-down" size="29" />
                  </div>
                </div>
              </div>
              <div>
                <div class="relative w-[150px]">
                  <select
                    class="w-full border border-[#BDBDBD] text-sm px-3 py-2.5 rounded appearance-none focus:outline-none"
                    v-model="bestSellKey.year"
                  >
                    <option selected value="" disabled>Năm</option>
                    <option value="2023">Năm 2023</option>
                    <option value="2024">Năm 2024</option>
                  </select>
                  <div class="absolute right-2 top-1.5">
                    <Icon name="ic:round-arrow-drop-down" size="29" />
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
        <table class="mt-5 w-full">
          <tr class="text-center font-bold">
            <td class="border-b border-black w-[80px] py-2">ID</td>
            <td class="border-b border-x border-black w-[200px] py-2">
              Tên sản phẩm
            </td>
            <td class="border-b border-black w-[100px] py-2">Số lượng bán</td>
          </tr>
          <tr v-for="product in bestSellingProducts" :key="product">
            <td class="py-2 border-b text-center">{{ product.id }}</td>
            <td class="py-2 px-5 border-x border-b">{{ product.name }}</td>
            <td class="py-2 border-b text-center">
              {{ product.total_quantity }}
            </td>
          </tr>
        </table>
      </div>
      <div class="bg-white mt-5 p-4">
        <h3 class="font-bold text-xl">Sản phẩm doanh số thấp</h3>
        <div class="flex items-center justify-between mt-5">
          <div>
            <form class="flex gap-5" @submit.prevent="searchWorstSell()">
              <div class="flex">
                <button
                  type="submit"
                  class="border border-[#BDBDBD] p-1.5 rounded-l"
                >
                  <Icon
                    name="ic:outline-filter-alt"
                    size="27"
                    color="#262626"
                  />
                </button>
                <div class="relative w-[150px]">
                  <select
                    class="w-full border border-[#BDBDBD] text-sm px-3 py-2.5 rounded-r appearance-none focus:outline-none"
                    v-model="worstSellKey.month"
                  >
                    <option value="" selected disabled>Tháng</option>
                    <option value="1">Tháng 1</option>
                    <option value="2">Tháng 2</option>
                    <option value="3">Tháng 3</option>
                    <option value="4">Tháng 4</option>
                    <option value="5">Tháng 5</option>
                    <option value="6">Tháng 6</option>
                    <option value="7">Tháng 7</option>
                    <option value="8">Tháng 8</option>
                    <option value="9">Tháng 9</option>
                    <option value="10">Tháng 10</option>
                    <option value="11">Tháng 11</option>
                    <option value="12">Tháng 12</option>
                  </select>
                  <div class="absolute right-2 top-1.5">
                    <Icon name="ic:round-arrow-drop-down" size="29" />
                  </div>
                </div>
              </div>
              <div>
                <div class="relative w-[150px]">
                  <select
                    class="w-full border border-[#BDBDBD] text-sm px-3 py-2.5 rounded appearance-none focus:outline-none"
                    v-model="worstSellKey.year"
                  >
                    <option selected value="" disabled>Năm</option>
                    <option value="2023">Năm 2023</option>
                    <option value="2024">Năm 2024</option>
                  </select>
                  <div class="absolute right-2 top-1.5">
                    <Icon name="ic:round-arrow-drop-down" size="29" />
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
        <table class="mt-5 w-full">
          <tr class="text-center font-bold">
            <td class="border-b border-black w-[80px] py-2">ID</td>
            <td class="border-b border-x border-black w-[200px] py-2">
              Tên sản phẩm
            </td>
            <td class="border-b border-black w-[100px] py-2">Số lượng bán</td>
          </tr>
          <tr v-for="product in worstSellingProducts" :key="product">
            <td class="py-2 border-b text-center">{{ product.id }}</td>
            <td class="py-2 px-5 border-x border-b">{{ product.name }}</td>
            <td class="py-2 border-b text-center">
              {{ product.total_quantity }}
            </td>
          </tr>
        </table>
      </div>
    </div>
  </StoreLayout>
</template>