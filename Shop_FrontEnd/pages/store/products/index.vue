<script setup lang="ts">
import * as XLSX from "xlsx";
import StoreLayout from "~/layouts/store.vue";
// import { useUtils } from "~/composables/utils";
import { useProductService } from "~/services/product";
import { useSessionStore } from "~/stores/session";

definePageMeta({ middleware: "store" });

const { price } = useUtils();
const productService = useProductService();
const sessionStore = useSessionStore();

const products = ref([]);
const itemsPerPage = ref(10);
const currentPage = ref(1);
const formData = reactive({
  keyword: "",
  publish: "",
});
const isAddMenu = ref(false);
const isImport = ref(false);
const error = ref(false);
// const dataImport = ref(null);
const file = ref(null);

onMounted(async () => {
  sessionStore.isLoading = true;
  const res = await productService.getAll();
  products.value = res.data;
  setTimeout(() => {
    sessionStore.isLoading = false;
  }, 200);
});

const totalProducts = computed(() => {
  return products.value.length;
});

const totalPages = computed(() => {
  return Math.ceil(totalProducts.value / itemsPerPage.value);
});

const displayProducts = computed(() => {
  const startIndex = (currentPage.value - 1) * itemsPerPage.value;
  const endIndex = startIndex + itemsPerPage.value;
  return products.value.slice(startIndex, endIndex);
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

const search = async () => {
  sessionStore.isLoading = true;
  if (!formData.keyword && !formData.publish) {
    const res = await productService.getAll();
    products.value = res.data;
    setTimeout(() => {
      sessionStore.isLoading = false;
    }, 500);
    return;
  }
  const res = await productService.getAll(formData);
  products.value = res.data;
  setTimeout(() => {
    sessionStore.isLoading = false;
  }, 200);
};

const isProductPublished = (product: object) => {
  return product.published === 1;
};

const togglePublished = async (product: object) => {
  product.published = product.published === 1 ? 2 : 1;
  let formData = new FormData();
  formData.append("published", product.published);
  formData.append("_method", "PATCH");
  await productService.updateStatus(formData, product.id);
};

const exportProduct = async () => {
  await productService.exportProducts();
};

const upload = (event) => {
  file.value = event.target.files[0];
  // if (file) {
  //   const reader = new FileReader();
  //   reader.onload = (e) => {
  //     const data = new Uint8Array(e.target.result);
  //     const workbook = XLSX.read(data, { type: "array" });
  //     const firstSheetName = workbook.SheetNames[0];
  //     const worksheet = workbook.Sheets[firstSheetName];
  //     const jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
  //     console.log(jsonData);

  //     const headers = jsonData[0]; // Lấy hàng đầu tiên làm tiêu đề
  //     dataImport.value = jsonData.slice(1).map((row) => {
  //       let rowData = {};
  //       headers.forEach((header, index) => {
  //         rowData[header] = row[index];
  //       });
  //       return rowData;
  //     });
  //   };
  //   reader.readAsArrayBuffer(file);
  // }
};

const handleOk = async () => {
  if (!file.value) {
    error.value = true;
    return;
  }
  // sessionStore.isLoading = true;
  const formData = new FormData();
  formData.append("file", file.value);
  const res = await productService.importProducts(formData);
  if (res.status) {
    products.value = res.data;
    isImport.value = false;
  }
  // setTimeout(() => {
  //   sessionStore.isLoading = false;
  // }, 200);
};
</script>
<template>
  <StoreLayout>
    <div class="bg-[#F6F6F6] h-full p-6">
      <div class="bg-white p-4 mt-5">
        <h3 class="text-xl font-bold">Tất cả sản phẩm</h3>
        <div class="flex items-center justify-between mt-5">
          <div>
            <form class="flex gap-5" @submit.prevent="search()">
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
                <div>
                  <input
                    type="text"
                    placeholder="Tên sản phẩm"
                    class="border-t border-b border-r rounded-r border-[#BDBDBD] text-sm px-3 py-2.5 focus:outline-none"
                    v-model="formData.keyword"
                  />
                </div>
              </div>
              <div>
                <div class="relative w-[150px]">
                  <select
                    class="w-full border border-[#BDBDBD] text-sm px-3 py-2.5 rounded appearance-none focus:outline-none"
                    v-model="formData.publish"
                  >
                    <option selected value="">Trạng thái</option>
                    <option value="1">Đang bán</option>
                    <option value="2">Dừng bán</option>
                  </select>
                  <div class="absolute right-2 top-1.5">
                    <Icon name="ic:round-arrow-drop-down" size="29" />
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="flex gap-5">
            <button
              class="border border-[#007006] text-[#007006] text-sm px-4 rounded-md"
              @click="exportProduct()"
            >
              <Icon name="lets-icons:export-light" size="20" />
              Xuất D/s Sản Phẩm
            </button>
            <div
              class="relative flex items-center bg-[#007006] rounded-md text-white text-sm cursor-pointer"
              @mouseenter="isAddMenu = true"
              @mouseleave="isAddMenu = false"
            >
              <div class="border-r border-white p-2.5">Thêm Sản Phẩm</div>
              <Icon
                name="material-symbols:arrow-drop-down"
                size="25"
                color="#FFFFFF"
                class=""
              />
              <div
                v-if="isAddMenu"
                class="absolute bg-[#fbfbfb] text-black rounded-md w-[180px] z-40 top-10 -left-4 border border-[#007006]"
              >
                <div class="py-2.5 px-3 rounded-md">
                  <NuxtLink
                    :to="sessionStore.type == 2 ? '' : '/store/products/create'"
                  >
                    <Icon name="mdi:plus" size="20" class="mr-2" />
                    Nhập Thủ Công
                  </NuxtLink>
                </div>
                <div class="border-b border-black"></div>
                <button
                  class="py-2.5 px-3"
                  @click="isImport = true"
                  :disabled="sessionStore.type == 2"
                >
                  <Icon name="uiw:file-excel" size="20" class="mr-2" />
                  Nhập File Excel
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="mt-5">
          <table class="border-y w-full">
            <tr class="font-medium">
              <td class="w-[80px] text-center py-2">ID</td>
              <td class="w-[350px] text-center py-2">Tên Sản Phẩm</td>
              <td class="text-center py-2">Giá Bán</td>
              <td class="w-[160px]">Số lượng hiện có</td>
              <td class="text-center">Trạng thái</td>
              <td>Hành động</td>
            </tr>
            <tr
              class="text-sm border-t"
              v-for="product in displayProducts"
              :key="product"
            >
              <td class="py-2 text-center">{{ product.id }}</td>
              <td class="line-clamp-2">
                <div class="pt-2">
                  {{ product.name }}
                </div>
              </td>
              <td class="py-2 text-center">{{ price(product.price) }}</td>
              <td class="py-2 pl-3">{{ product.quantity }}</td>
              <td class="py-2 text-center">
                <label v-if="product.published == 3">Nháp</label>
                <label
                  v-else
                  class="inline-flex items-center"
                  :class="sessionStore.type == 2 ? '' : 'cursor-pointer'"
                >
                  <input
                    type="checkbox"
                    class="sr-only peer"
                    :checked="isProductPublished(product)"
                    @change="togglePublished(product)"
                    :disabled="sessionStore.type == 2"
                  />
                  <div
                    class="relative w-11 h-6 bg-[#CCCCCC] peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#007006]"
                  ></div>
                </label>
              </td>
              <td class="py-2 pl-8">
                <NuxtLink
                  :to="
                    sessionStore.type == 2
                      ? ''
                      : `/store/products/edit/${product.id}`
                  "
                >
                  <Icon name="ph:note-pencil-light" size="25" />
                </NuxtLink>
              </td>
            </tr>
          </table>
          <div
            class="mt-[40px] mb-[88px] flex items-center justify-center gap-[5px]"
          >
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
  </StoreLayout>
  <div
    v-if="isImport"
    class="fixed bg-black bg-opacity-40 inset-0 w-full z-40 flex justify-center h-[100vh] pt-10 overflow-hidden"
  >
    <div class="p-7 rounded-md bg-white w-[800px] h-[290px]">
      <div
        class="h-full flex flex-col items-center justify-center gap-5 border-2 border-dashed border-black"
      >
        <p class="font-bold text-xl">Chọn file Excel danh sách sản phẩm</p>
        <div class="flex flex-col items-center gap-2">
          <input
            type="file"
            class="ml-[70px]"
            @change="upload"
            accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
          />
          <p v-if="error" class="text-red-400 text-sm">Vui lòng nhập file</p>
        </div>
        <div class="flex gap-10">
          <button
            class="bg-red-500 w-[150px] py-2 rounded-md text-white font-medium"
            @click="(isImport = false), (error = false), (dataImport = null)"
          >
            Hủy Bỏ
          </button>
          <button
            class="bg-blue-500 w-[150px] py-2 rounded-md text-white font-medium"
            @click="handleOk"
          >
            Xác Nhận
          </button>
        </div>
      </div>
    </div>
  </div>
</template>