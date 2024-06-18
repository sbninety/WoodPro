<script setup lang="ts">
import MainLayout from "~/layouts/default.vue";
import ProductCard from "~/components/globals/card/product.vue";
import CommentCard from "~/components/globals/card/comment.vue";
import Swal from "sweetalert2";
import { useUtils } from "~/composables/utils";
import { useProductService } from "~/services/product";
import { useSessionStore } from "~/stores/session";
import { useUserStore } from "~/stores/user";
import { useCommentService } from "~/services/comment";

const route = useRoute();
const { price, onlyNumber } = useUtils();
const productService = useProductService();
const commentService = useCommentService();
const sessionStore = useSessionStore();
const userStore = useUserStore();

const otherProducts = ref([]);
const product = ref({});
const selectedColor = ref({});
const selectedMaterial = ref({});
const selectedSize = ref({});
const quantity = ref(1);
const successDialog = ref(false);
const rate = ref(0);
const dialogComment = ref(false);
const images = ref([]);
const comment = reactive({
  rate: 0,
  name: null,
  content: null,
  images: [],
});
const comments = ref([]);
const active = ref(1);
const itemsPerPage = ref(3);
const currentPage = ref(1);
const selectedRating = ref("all");
const filterComments = ref([]);
const averageRating = ref(null);

onMounted(async () => {
  sessionStore.isLoading = true;

  const [resDetailProduct, resOtherProduct] = await Promise.all([
    productService.getProductBySlug(route.params.slug),
    productService.getOther(),
  ]);

  product.value = resDetailProduct.data;
  selectedColor.value = product.value.colors[0];
  selectedMaterial.value = product.value.materials[0];
  selectedSize.value = product.value.sizes[0];
  comments.value = product.value.comments;
  filterComments.value = product.value.comments;

  otherProducts.value = resOtherProduct.data;

  const totalRatings = comments.value.reduce(
    (sum, comment) => sum + comment.rate,
    0
  );
  averageRating.value = Math.round(
    totalRatings / comments.value.length
  ).toFixed(1);

  setTimeout(() => {
    sessionStore.isLoading = false;
  }, 200);
});

const uploadImage = (e: any) => {
  const selectedFiles = e.target;
  if (selectedFiles.files.length > 0) {
    for (let i = 0; i < selectedFiles.files.length; i++) {
      comment.images.push(selectedFiles.files[i]);
      const selectedFile = selectedFiles.files[i];

      const reader = new FileReader();
      reader.onload = () => {
        images.value.push({
          filePath: reader.result,
        });
      };

      reader.readAsDataURL(selectedFile);
    }
  }
};

const deleteImage = (index: number) => {
  images.value.splice(index, 1);
  comment.images.splice(index, 1);
};

const priceProduct = computed(() => {
  if (selectedSize.value.price) {
    return selectedSize.value.price;
  }
  return product.value.price;
});

const priceProductSale = computed(() => {
  if (product.value.discount) {
    return (
      priceProduct.value - priceProduct.value * (product.value.discount / 100)
    );
  }
  return priceProduct.value;
});

const watchQuantity = () => {
  if (quantity.value < 0) {
    quantity.value = 1;
  }
  if (quantity.value > product.value.quantity) {
    quantity.value = product.value.quantity;
  }
};

const decrease = () => {
  if (quantity.value > 1) {
    quantity.value--;
  }
  return;
};

const increase = () => {
  if (quantity.value < product.value.quantity) {
    quantity.value++;
  }
  return;
};

const execute = () => {
  const item = {
    id: null,
    user_id: null,
    product_id: product.value.id,
    product_name: product.value.name,
    product_quantity: product.value.quantity,
    product_image: product.value.images[0].path,
    price: priceProductSale.value,
    color_id: selectedColor.value.id,
    color_name: selectedColor.value.name,
    material_id: selectedMaterial.value.id,
    material_name: selectedMaterial.value.name,
    size_id: selectedSize.value.id,
    length: selectedSize.value.length,
    width: selectedSize.value.width,
    height: selectedSize.value.height,
    quantity: quantity.value,
  };
  userStore.addToCart(item);
};

const addToCart = () => {
  execute();
  showAlert();
};

const buy = () => {
  execute();
  return navigateTo("/cart");
};

const showAlert = () => {
  Swal.fire({
    icon: "success",
    title: "Thêm giỏ hàng thành công",
    showConfirmButton: false,
    timer: 1500,
  });
};

const submit = async () => {
  let formData = new FormData();
  formData.append("rate", comment.rate);
  formData.append("name", comment.name);
  formData.append("product_id", product.value.id);
  formData.append("content", comment.content);
  comment.images.forEach((image) => {
    formData.append("images[]", image);
  });

  const res = await commentService.create(formData);
  comments.value.unshift(res.data);

  comment.rate = 0;
  comment.name = null;
  comment.content = null;
  comment.images = [];

  dialogComment.value = false;
};

watch(
  () => selectedRating.value,
  () => {
    currentPage.value = 1;
    if (selectedRating.value == "all") {
      filterComments.value = comments.value;
    } else {
      filterComments.value = comments.value.filter(
        (comment) => comment.rate == selectedRating.value
      );
    }
  }
);

const totalComments = computed(() => {
  return filterComments.value.length;
});

const totalPages = computed(() => {
  return Math.ceil(totalComments.value / itemsPerPage.value);
});

const displayComments = computed(() => {
  const startIndex = (currentPage.value - 1) * itemsPerPage.value;
  const endIndex = startIndex + itemsPerPage.value;

  return filterComments.value.slice(startIndex, endIndex);
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
</script>
<template>
  <MainLayout>
    <div class="w-[1200px] mx-auto py-10">
      <ul>
        <NuxtLink class="text-sm text-[#676767] italic font-light"
          >Trang chủ
          <span class="px-2"> / </span>
        </NuxtLink>
        <NuxtLink class="text-sm text-[#676767] italic font-light uppercase"
          >{{ product.category_name }} <span class="px-2"> / </span></NuxtLink
        >
        <NuxtLink class="text-sm text-[#676767] italic font-light">{{
          product.name
        }}</NuxtLink>
      </ul>
    </div>
    <div class="w-[1200px] mx-auto flex gap-5">
      <div class="w-[500px]">
        <swiper-container
          class="mySwiper2"
          loop="true"
          space-between="10"
          slides-per-view="1"
        >
          <swiper-slide v-for="image in product.images" :key="image">
            <img
              :src="image.path"
              class="w-[500px] h-[500px] object-cover mx-auto"
            />
          </swiper-slide>
        </swiper-container>
        <swiper-container
          class="mySwiper mt-2"
          thumbs-swiper=".mySwiper2"
          slides-per-view="5"
          loop="true"
          space-between="5"
          navigation="true"
          watch-slides-progress="true"
        >
          <swiper-slide v-for="image in product.images" :key="image">
            <img :src="image.path" class="w-[95px] h-[95px] object-cover" />
          </swiper-slide>
        </swiper-container>
      </div>
      <div class="flex-1">
        <h2 class="text-2xl text-[#676767] font-bold">
          {{ product.name }}
        </h2>
        <div class="mt-3 flex items-center gap-10 bg-[#E0E0E033] py-3">
          <div></div>
          <div
            class="text-xl text-[#676767] font-medium"
            :class="product.discount ? 'line-through' : ''"
          >
            {{ price(priceProduct) }}₫
          </div>
          <div class="text-3xl text-[#74B018] font-medium">
            {{ price(priceProductSale) }}₫
          </div>
          <div
            v-if="product.discount"
            class="w-[100px] h-[30px] flex items-center justify-center bg-[#FFED4C] font-bold"
          >
            GIẢM {{ product.discount }}%
          </div>
        </div>
        <div class="bg-[#F5F5F5] mt-5 border border-[#CCC] p-3">
          <div>
            <h4>Màu sắc:</h4>
            <div class="flex flex-wrap mt-2 gap-3">
              <div
                v-for="color in product.colors"
                :key="color"
                class="px-3 py-2 border cursor-pointer text-sm"
                :class="
                  selectedColor == color
                    ? 'bg-[#EEE3CE] border-[#b58023] text-[#b58023]'
                    : 'bg-white border-[#CCC]'
                "
                @click="selectedColor = color"
              >
                {{ color.name }}
              </div>
            </div>
          </div>
          <div class="mt-6">
            <h4>Kích thước (ngang x cao x sâu) (cm):</h4>
            <div class="flex flex-wrap mt-2 gap-3">
              <div
                v-for="size in product.sizes"
                :key="size"
                class="px-3 py-2 border cursor-pointer text-sm"
                :class="
                  selectedSize == size
                    ? 'bg-[#EEE3CE] border-[#b58023] text-[#b58023]'
                    : 'bg-white border-[#CCC]'
                "
                @click="selectedSize = size"
              >
                {{ size.length }} x {{ size.width }} x {{ size.height }}
              </div>
            </div>
          </div>
          <div class="mt-6">
            <h4>Chất liệu:</h4>
            <div class="flex flex-wrap mt-2 gap-3">
              <div
                v-for="material in product.materials"
                :key="material"
                class="px-3 py-2 border cursor-pointer text-sm"
                :class="
                  selectedMaterial == material
                    ? 'bg-[#EEE3CE] border-[#b58023] text-[#b58023]'
                    : 'bg-white border-[#CCC]'
                "
                @click="selectedMaterial = material"
              >
                {{ material.name }}
              </div>
            </div>
          </div>
        </div>
        <div class="flex items-center mt-5" v-if="product.quantity">
          <div class="w-[110px] text-[14px] text-[#000000CC]">Số lượng:</div>
          <div class="flex h-8">
            <button
              @click="decrease()"
              class="border-y border-l border-[#00000033] w-8"
            >
              -
            </button>
            <input
              type="text"
              class="w-16 border border-[#00000033] text-center focus:outline-none appearance-none"
              v-model="quantity"
              @keypress="onlyNumber($event)"
              @input="watchQuantity()"
            />
            <button
              @click="increase()"
              class="border-y border-r border-[#00000033] w-8"
            >
              +
            </button>
          </div>
          <div class="text-[14px] text-[#00000066] ml-5">
            {{ product.quantity }} sản phẩm có sẵn
          </div>
        </div>
        <div class="flex gap-10 mt-10" v-if="product.quantity">
          <button
            @click="addToCart()"
            class="px-8 py-4 border border-[#74B018] text-[#74B018] rounded"
          >
            Thêm Vào Giỏ Hàng
          </button>
          <button
            @click="buy()"
            class="px-14 py-4 bg-[#74B018] text-white rounded"
          >
            Mua Ngay
          </button>
        </div>
        <div class="flex gap-10 mt-10" v-else>
          <button
            disabled
            class="px-14 py-4 bg-[#7AA7CF] text-white rounded cursor-not-allowed"
          >
            Hết Hàng
          </button>
        </div>
      </div>
    </div>
    <div class="w-[1200px] mx-auto flex mt-10">
      <div
        class="w-1/3 text-center py-2 text-sm text-[#676767]"
        :class="
          active === 1
            ? 'bg-[#EDEDED] font-bold'
            : 'bg-[#ddd] font-light hover:bg-[#EDEDED] hover:text-[#a8a88f] hover:font-medium cursor-pointer'
        "
        @click="active = 1"
      >
        Mô tả sản phẩm
      </div>
      <div
        class="w-1/3 text-center py-2 text-sm text-[#676767] border-l-2 border-r-2 border-[#ddd]"
        :class="
          active === 2
            ? 'bg-[#EDEDED] font-bold'
            : 'bg-[#ddd] font-light hover:bg-[#EDEDED] hover:text-[#a8a88f] hover:font-medium cursor-pointer'
        "
        @click="active = 2"
      >
        Bảo hành - Bảo quản
      </div>
      <div
        class="w-1/3 text-center py-2 text-sm text-[#676767]"
        :class="
          active === 3
            ? 'bg-[#EDEDED] font-bold'
            : 'bg-[#ddd] font-light hover:bg-[#EDEDED] hover:text-[#a8a88f] hover:font-medium cursor-pointer'
        "
        @click="active = 3"
      >
        Giao hàng - Lắp đặt
      </div>
    </div>
    <div class="w-[1200px] mx-auto">
      <div v-if="active === 1" class="mt-5">
        <!-- <img src="/mota1.png" />
        <img src="/mota2.png" />
        <img src="/mota3.png" />
        <img src="/mota4.png" />
        <img src="/mota5.png" />
        <img src="/mota6.png" /> -->
        <!-- {{ product.description }} -->
        <span v-html="product.description"></span>
      </div>
      <div v-if="active === 2" class="mt-5">
        <p class="text-sm font-bold">Bảo quản:</p>
        <p class="text-sm font-light mt-3">
          Dùng khăn ẩm với nước sạch hoặc dung dịch tẩy rửa nhẹ để làm sạch sản
          phẩm. Sau đó, lau khô sản phẩm bằng khăn mềm.
        </p>
        <p class="text-sm font-light mt-3">
          Không để sản phẩm tiếp xúc với nước hoặc nguồn nhiệt độ cao trong thời
          gian dài.
        </p>
        <p class="text-sm font-light mt-3">
          Không dùng các vật sắc nhọn, thô cứng, sần sùi chà xát sản phẩm.
        </p>
        <p class="text-sm font-bold mt-3">Bảo hành:</p>
        <p class="text-sm font-light mt-3">Thời hạn bảo hành: 12 tháng</p>
        <p class="text-sm font-light mt-3">
          Bảo hành cho các sản phẩm có lỗi nguyên vật liệu hay lỗi từ nhà sản
          xuất (như móp, méo, cong vênh các chi tiết sản phẩm, bong tróc
          sơn,...). Hoặc các sản phẩm bị bể vỡ/ trầy xước/ biến dạng trong quá
          trình vận chuyển.
        </p>
        <p class="text-sm font-light mt-3">
          Không bảo hành cho các sản phẩm hư hỏng trong quá trình quý khách sử
          dụng do sử dụng không cẩn thận, bảo quản và vệ sinh không đúng cách.
          Cũng như sản phẩm bị các hao mòn thông thường (như phai mờ, rỉ sét do
          trầy xước, lỏng ốc vít sau một khoảng thời gian...).
        </p>
      </div>
      <div v-if="active === 3" class="mt-5">
        <p class="text-sm font-bold">Giao hàng:</p>
        <p class="font-light text-[15px] mt-3">
          <span class="italic"
            >- Đối với hàng hoá có kích thước nhỏ và trọng lượng nhẹ:</span
          >
          (kích thước cạnh dài nhất <40cm và dưới 10kg), Phí giao hàng sẽ được
          tính theo phí dịch vụ Grab, Lalamove, Ahamove áp dụng mức giá hiện
          hành không bao gồm mã khuyến mãi.
        </p>
        <p class="font-light text-[15px] mt-3">
          <span class="italic">- Đối với hàng hoá cồng kềnh, cần lắp đặt:</span>
          Phí giao hàng của đơn hàng được tính theo biểu phí như sau:
        </p>
        <div class="w-2/3 mx-auto mt-10">
          <table>
            <tbody>
              <tr class="text-[15px] italic text-center">
                <td class="font-bold">Khoảng cách tính từ kho hàng WoodPro</td>
                <td>
                  <span class="font-bold"
                    >Đối với mặt hàng có thể giao xe máy
                  </span>
                  <span class="font-light"
                    >(Chiều dài <= 1,2m, trọng lượng <= 30kg)</span
                  >
                </td>
                <td>
                  <span class="font-bold">Đối với mặt hàng cồng kềnh </span
                  ><span class="font-light"
                    >(Chiều dài > 1,2m, trọng lượng > 30kg)</span
                  >
                </td>
              </tr>
              <tr class="text-[15px] text-center">
                <td class="font-bold pt-3">Dưới 3km</td>
                <td class="font-light pt-3">80.000đ</td>
                <td class="font-light pt-3">180.000đ</td>
              </tr>
              <tr class="text-[15px] text-center">
                <td class="font-bold pt-3">Từ 3-7km</td>
                <td class="font-light pt-3">100.000đ</td>
                <td class="font-light pt-3">200.000đ</td>
              </tr>
              <tr class="text-[15px] text-center">
                <td class="font-bold pt-3">Từ 7-10km</td>
                <td class="font-light pt-3">130.000đ</td>
                <td class="font-light pt-3">250.000đ</td>
              </tr>
              <tr class="text-[15px] text-center">
                <td class="font-bold pt-3">Từ 10-12km</td>
                <td class="font-light pt-3">150.000đ</td>
                <td class="font-light pt-3">280.000đ</td>
              </tr>
              <tr class="text-[15px] text-center">
                <td class="font-bold pt-3">Từ 12-15km</td>
                <td class="font-light pt-3">180.000đ</td>
                <td class="font-light pt-3">300.000đ</td>
              </tr>
              <tr class="text-[15px] text-center">
                <td class="font-bold pt-3">Từ 15-17km</td>
                <td class="font-light pt-3">200.000đ</td>
                <td class="font-light pt-3">350.000đ</td>
              </tr>
              <tr class="text-[15px] text-center">
                <td class="font-bold pt-3">Từ 17-20km</td>
                <td class="font-light pt-3">250.000đ</td>
                <td class="font-light pt-3">380.000đ</td>
              </tr>
              <tr class="text-[15px] text-center">
                <td class="font-bold pt-3">Trên 20km</td>
                <td class="font-light pt-3">Theo phí dịch vụ của bên thứ 3</td>
                <td class="font-light pt-3">Theo phí dịch vụ của bên thứ 3</td>
              </tr>
            </tbody>
          </table>
        </div>
        <p class="font-light text-[15px] mt-10">
          - Phí giao hàng đã bao gồm phí dịch vụ lắp ráp sản phẩm.
        </p>
        <p class="font-light text-[15px] mt-3">
          - Trường hợp giao hàng ngoài giờ hành chính, quý khách vui lòng thanh
          toán thêm 30% phí giao hàng theo quy định và tối thiểu là 50.000đ
        </p>
        <p class="font-light text-[15px] mt-3 italic">
          Lưu ý: WoodPro là đơn vị quyết định phương thức vận chuyển cuối cùng
          để đảm bảo an toàn cho hàng hoá cách tốt nhất.
        </p>
      </div>
    </div>
    <div class="w-[1200px] mx-auto mt-10 border-t border-black pt-5">
      <div class="flex items-center justify-between">
        <div>
          <h3 class="font-bold text-xl">ĐÁNH GIÁ SẢN PHẨM</h3>
        </div>
        <div>
          <button
            @click="dialogComment = true"
            class="bg-[#1871E3] text-white px-10 py-2 rounded-md"
          >
            Viết đánh giá
          </button>
        </div>
      </div>

      <div class="bg-[#0070060D] mt-5 flex">
        <div class="w-[250px] flex flex-col items-center justify-center py-10">
          <div class="text-[#007006]">
            <span class="text-[40px]">{{ averageRating }}</span
            ><span class="text-[20px]"> trên</span
            ><span class="text-[20px]"> 5</span>
          </div>
          <div class="flex gap-0.5">
            <div v-for="i in 5" :key="i">
              <Icon
                :name="
                  i <= averageRating
                    ? 'ic:outline-star'
                    : 'ic:outline-star-border'
                "
                size="28"
                color="#FFC107"
              />
            </div>
          </div>
        </div>
        <div class="flex items-center">
          <div class="flex flex-wrap gap-[15px]">
            <button
              @click="selectedRating = 'all'"
              class="bg-white border rounded px-[28px] py-[5px]"
              :class="
                selectedRating == 'all'
                  ? 'border-[#007006] text-[#007006]'
                  : 'border-[#0000001A]'
              "
            >
              Tất Cả
            </button>
            <button
              @click="selectedRating = 5"
              class="bg-white border rounded px-[28px] py-[5px]"
              :class="
                selectedRating == 5
                  ? 'border-[#007006] text-[#007006]'
                  : 'border-[#0000001A]'
              "
            >
              5 Sao
            </button>
            <button
              @click="selectedRating = 4"
              class="bg-white border rounded px-[28px] py-[5px]"
              :class="
                selectedRating == 4
                  ? 'border-[#007006] text-[#007006]'
                  : 'border-[#0000001A]'
              "
            >
              4 Sao
            </button>
            <button
              @click="selectedRating = 3"
              class="bg-white border rounded px-[28px] py-[5px]"
              :class="
                selectedRating == 3
                  ? 'border-[#007006] text-[#007006]'
                  : 'border-[#0000001A]'
              "
            >
              3 Sao
            </button>
            <button
              @click="selectedRating = 2"
              class="bg-white border rounded px-[28px] py-[5px]"
              :class="
                selectedRating == 2
                  ? 'border-[#007006] text-[#007006]'
                  : 'border-[#0000001A]'
              "
            >
              2 Sao
            </button>
            <button
              @click="selectedRating = 1"
              class="bg-white border rounded px-[28px] py-[5px]"
              :class="
                selectedRating == 1
                  ? 'border-[#007006] text-[#007006]'
                  : 'border-[#0000001A]'
              "
            >
              1 Sao
            </button>
          </div>
        </div>
      </div>
      <div class="mt-5">
        <div v-for="comment in displayComments" :key="comment">
          <CommentCard :comment="comment" />
        </div>
        <div class="mt-10 flex items-center justify-end gap-3">
          <button @click="prevPage" :disabled="currentPage === 1"><</button>
          <div v-for="i in pagesToShow" :key="i">
            <button
              class="w-[20px]"
              :class="i === currentPage ? 'bg-gray-300' : ''"
              @click="getCurrentPage(i)"
            >
              {{ i }}
            </button>
          </div>
          <button @click="nextPage" :disabled="currentPage === totalPages">
            >
          </button>
        </div>
      </div>
    </div>
    <div class="w-[1200px] mx-auto mt-10 border-t border-black pt-10">
      <h4 class="text-2xl text-[#676767] font-bold">Sản Phẩm Liên Quan</h4>
      <div class="grid grid-cols-4 gap-10 mt-5">
        <div v-for="product in otherProducts" :key="product">
          <ProductCard :product="product" />
        </div>
      </div>
    </div>
  </MainLayout>
  <div
    v-if="dialogComment"
    class="fixed bg-black bg-opacity-40 inset-0 w-full z-40 flex items-center justify-center h-[100vh] overflow-hidden"
  >
    <div class="relative w-[700px] m-auto bg-white rounded-2xl">
      <button class="absolute right-4 top-4" @click="dialogComment = false">
        <Icon name="mdi:close" color="black" size="25" />
      </button>
      <div
        class="flex items-center justify-center font-bold py-5 text-xl border-b"
      >
        Đánh giá sản phẩm
      </div>
      <div class="flex flex-col items-center justify-center gap-2.5">
        <img :src="product.images[0].path" width="100" class="mt-5" />
        <h3 class="font-bold text-xl">{{ product.name }}</h3>
        <div class="flex gap-5">
          <div
            v-for="i in 5"
            :key="i"
            class="hover:cursor-pointer"
            @click="comment.rate = i"
          >
            <Icon
              :name="
                i <= comment.rate ? 'ic:outline-star' : 'ic:outline-star-border'
              "
              color="#FFC107"
              size="40"
            />
          </div>
        </div>
      </div>
      <div class="mt-5 px-8">
        <input
          type="text"
          placeholder="Họ tên"
          class="w-[270px] border rounded-lg px-4 py-2 focus:outline-none"
          v-model="comment.name"
        />
        <textarea
          class="w-full h-[100px] mt-5 border px-4 py-2 rounded-lg focus:outline-none"
          placeholder="Chia sẻ cảm nhận..."
          v-model="comment.content"
        />
        <div class="mt-1.5">
          <label
            for="image"
            class="flex items-center gap-1.5 text-[15px] text-[#0071E3] hover:cursor-pointer"
          >
            <Icon
              name="material-symbols:photo-camera-outline"
              size="25"
              color="#0071E3"
            />
            Gửi ảnh thực tế</label
          >
          <input type="file" id="image" hidden multiple @change="uploadImage" />
        </div>
        <div v-if="images" class="mt-2.5 px-[30px] flex flex-wrap gap-[30px]">
          <div class="relative" v-for="(image, i) in images" :key="image">
            <img
              :src="image.filePath"
              width="50"
              class="rounded border border-[#00000033]"
            />
            <div
              class="absolute -top-3.5 -right-2.5 hover:cursor-pointer"
              @click="deleteImage(i)"
            >
              <Icon
                name="material-symbols-light:close-rounded"
                size="20"
                class="bg-gray-400 rounded-full"
              />
            </div>
          </div>
        </div>
      </div>
      <div class="my-5 px-8">
        <button
          @click="submit()"
          class="w-full bg-[#0071E3] text-center text-white font-medium py-3 rounded-lg"
        >
          Gửi đánh giá
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.mySwiper .swiper-slide-active {
  border: solid 1px #ff5353;
}
.bounce-enter-active {
  animation: bounce-in 0.5s;
}
.bounce-leave-active {
  animation: bounce-in 0.5s reverse;
}
@keyframes bounce-in {
  0% {
    transform: scale(0);
  }
  /* 50% {
    transform: scale(0.5);
  } */
  100% {
    transform: scale(1);
  }
}
</style>