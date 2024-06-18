<script setup lang="ts">
import StoreLayout from "~/layouts/store.vue";
import Swal from "sweetalert2";
import { useCategoryService } from "~/services/category";
import { useProductService } from "~/services/product";
import { useSessionStore } from "~/stores/session";
import { useTrans } from "~/composables/trans";
import { useVuelidate } from "@vuelidate/core";
import { required, numeric, maxValue, helpers } from "@vuelidate/validators";

const route = useRoute();
const categoryService = useCategoryService();
const productService = useProductService();
const sessionStore = useSessionStore();
const { trans } = useTrans();

const categories = ref([]);
const product = reactive({
  id: null,
  category_id: "",
  name: null,
  slug: null,
  price: null,
  discount: null,
  quantity: null,
  published: 1,
  images: [],
  description: null,
  colors: [
    {
      name: null,
    },
  ],
  materials: [
    {
      name: null,
    },
  ],
  sizes: [
    {
      length: null,
      width: null,
      height: null,
      price: null,
    },
  ],
});
const deleteImages = ref([]);
const colorsLength = ref(0);
const materialsLength = ref(0);
const sizesLength = ref(0);

onMounted(async () => {
  sessionStore.isLoading = true;
  const [resCategories, resProduct] = await Promise.all([
    categoryService.getAll(),
    productService.getProductById(route.params.id),
  ]);

  categories.value = resCategories.data;

  product.id = resProduct.data.id;
  product.category_id = resProduct.data.category_id;
  product.name = resProduct.data.name;
  product.slug = resProduct.data.slug;
  product.price = resProduct.data.price;
  product.discount = resProduct.data.discount;
  product.quantity = resProduct.data.quantity;
  product.published = resProduct.data.published;
  product.images = resProduct.data.images;
  // images.value = resProduct.data.images;
  product.description = resProduct.data.description;

  product.colors = resProduct.data.colors;
  colorsLength.value = resProduct.data.colors.length;

  product.materials = resProduct.data.materials;
  materialsLength.value = resProduct.data.materials.length;

  product.sizes = resProduct.data.sizes;
  sizesLength.value = resProduct.data.sizes.length;

  setTimeout(() => {
    sessionStore.isLoading = false;
  }, 200);
});

const addColor = () => {
  product.colors.push({ id: "", product_id: product.id, name: null });
};

const deleteColor = (index: number) => {
  product.colors.splice(index, 1);
};

const addMaterial = () => {
  product.materials.push({ id: "", product_id: product.id, name: null });
};

const deleteMaterial = (index: number) => {
  product.materials.splice(index, 1);
};

const addSize = () => {
  product.sizes.push({
    id: "",
    product_id: product.id,
    length: null,
    width: null,
    height: null,
    price: "",
  });
};

const deleteSize = (index: number) => {
  product.sizes.splice(index, 1);
};

const uploadImage = (e: any) => {
  const selectedFiles = e.target;
  if (selectedFiles.files.length > 0) {
    for (let i = 0; i < selectedFiles.files.length; i++) {
      const selectedFile = selectedFiles.files[i];

      const reader = new FileReader();
      reader.onload = () => {
        product.images.push({
          path: reader.result,
          file: selectedFiles.files[i],
        });
      };

      reader.readAsDataURL(selectedFile);
    }
  }
};

const deleteImage = (index: number) => {
  if (product.images[index].hasOwnProperty("id")) {
    deleteImages.value.push(product.images[index]);
  }
  product.images.splice(index, 1);
};

const rules = computed(() => {
  return {
    category_id: {
      required: helpers.withMessage(
        trans("ERR_VAL_0001", [trans("ATTRIBUTE.product.category_id")]),
        required
      ),
    },
    name: {
      required: helpers.withMessage(
        trans("ERR_VAL_0001", [trans("ATTRIBUTE.product.name")]),
        required
      ),
    },
    slug: {
      required: helpers.withMessage(
        trans("ERR_VAL_0001", [trans("ATTRIBUTE.product.slug")]),
        required
      ),
    },
    price: {
      required: helpers.withMessage(
        trans("ERR_VAL_0001", [trans("ATTRIBUTE.product.price")]),
        required
      ),
      numeric: helpers.withMessage(
        trans("ERR_VAL_0003", [trans("ATTRIBUTE.product.price")]),
        numeric
      ),
    },
    discount: {
      maxValue: helpers.withMessage(
        trans("ERR_VAL_0004", [trans("ATTRIBUTE.product.discount"), 1, 100]),
        maxValue(100)
      ),
    },
    quantity: {
      required: helpers.withMessage(
        trans("ERR_VAL_0001", [trans("ATTRIBUTE.product.quantity")]),
        required
      ),
      numeric: helpers.withMessage(
        trans("ERR_VAL_0003", [trans("ATTRIBUTE.product.quantity")]),
        numeric
      ),
    },
    images: {
      required: helpers.withMessage(
        trans("ERR_VAL_0001", [trans("ATTRIBUTE.product.images")]),
        required
      ),
    },
    colors: {
      $each: helpers.forEach({
        name: {
          required: helpers.withMessage(
            trans("ERR_VAL_0001", [trans("ATTRIBUTE.product.colors.name")]),
            required
          ),
        },
      }),
    },
    materials: {
      $each: helpers.forEach({
        name: {
          required: helpers.withMessage(
            trans("ERR_VAL_0001", [trans("ATTRIBUTE.product.materials.name")]),
            required
          ),
        },
      }),
    },
    sizes: {
      $each: helpers.forEach({
        length: {
          required: helpers.withMessage(
            trans("ERR_VAL_0001", [trans("ATTRIBUTE.product.sizes.parameter")]),
            required
          ),
          numeric: helpers.withMessage(
            trans("ERR_VAL_0003", [trans("ATTRIBUTE.product.sizes.parameter")]),
            numeric
          ),
        },
        width: {
          required: helpers.withMessage(
            trans("ERR_VAL_0001", [trans("ATTRIBUTE.product.sizes.parameter")]),
            required
          ),
          numeric: helpers.withMessage(
            trans("ERR_VAL_0003", [trans("ATTRIBUTE.product.sizes.parameter")]),
            numeric
          ),
        },
        height: {
          required: helpers.withMessage(
            trans("ERR_VAL_0001", [trans("ATTRIBUTE.product.sizes.parameter")]),
            required
          ),
          numeric: helpers.withMessage(
            trans("ERR_VAL_0003", [trans("ATTRIBUTE.product.sizes.parameter")]),
            numeric
          ),
        },
        price: {
          numeric: helpers.withMessage(
            trans("ERR_VAL_0003", [trans("ATTRIBUTE.product.sizes.price")]),
            numeric
          ),
        },
      }),
    },
  };
});

const v$ = useVuelidate(rules, product, { $lazy: true });

const sizeParameterError = (index: number) => {
  if (
    v$.value.sizes.$each.$response &&
    v$.value.sizes.$each.$response.$errors[index].length.length
  ) {
    return v$.value.sizes.$each.$response.$errors[index].length;
  } else if (
    v$.value.sizes.$each.$response &&
    v$.value.sizes.$each.$response.$errors[index].width.length
  ) {
    return v$.value.sizes.$each.$response.$errors[index].width;
  } else if (
    v$.value.sizes.$each.$response &&
    v$.value.sizes.$each.$response.$errors[index].height.length
  ) {
    return v$.value.sizes.$each.$response.$errors[index].height;
  } else {
    return;
  }
};

const submit = async () => {
  const result = await v$.value.$validate();
  if (result) {
    sessionStore.isLoading = true;

    let formData = new FormData();
    formData.append("category_id", product.category_id);
    formData.append("name", product.name);
    formData.append("slug", product.slug);
    formData.append("price", product.price);
    if (product.discount) {
      formData.append("discount", product.discount);
    } else {
      formData.append("discount", "");
    }
    formData.append("quantity", product.quantity);
    formData.append("published", product.published);
    formData.append("description", product.description);

    if (deleteImages.value.length) {
      deleteImages.value.forEach((image, i) => {
        formData.append("deleteImages[" + i + "][id]", image.id);
      });
    }

    product.images.forEach((image) => {
      if (image.hasOwnProperty("file")) {
        formData.append("newImages[]", image.file);
      }
    });

    product.colors.forEach((color, i) => {
      formData.append("colors[" + i + "][id]", color.id);
      formData.append("colors[" + i + "][product_id]", color.product_id);
      formData.append("colors[" + i + "][name]", color.name);
    });

    product.materials.forEach((material, i) => {
      formData.append("materials[" + i + "][id]", material.id);
      formData.append("materials[" + i + "][product_id]", material.product_id);
      formData.append("materials[" + i + "][name]", material.name);
    });

    product.sizes.forEach((size, i) => {
      formData.append("sizes[" + i + "][id]", size.id);
      formData.append("sizes[" + i + "][product_id]", size.product_id);
      formData.append("sizes[" + i + "][length]", size.length);
      formData.append("sizes[" + i + "][width]", size.width);
      formData.append("sizes[" + i + "][height]", size.height);
      if (size.price) {
        formData.append("sizes[" + i + "][price]", size.price);
      } else {
        formData.append("sizes[" + i + "][price]", "");
      }
    });

    formData.append("_method", "PUT");

    const res = await productService.update(formData, route.params.id);

    if (res.status) {
      showAlert();
      setTimeout(() => {
        sessionStore.isLoading = false;
        navigateTo("/store/products");
      }, 200);
    }
  }
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
    title: "Cập nhật sản phẩm thành công",
  });
};
</script>
<template>
  <StoreLayout>
    <div class="bg-[#F6F6F6] h-full p-6">
      <div class="bg-white">
        <h3 class="text-xl font-bold pt-4 pl-4">Chỉnh Sửa Sản Phẩm</h3>
        <div class="mt-3 h-[4px] bg-[#007006]" />
        <div class="p-4">
          <h3 class="text-xl">Thông tin cơ bản</h3>
          <div class="px-4">
            <div class="mt-5 flex">
              <label for="category" class="text-sm w-[200px] mt-3"
                >Danh mục sản phẩm <span class="text-red-400">(*)</span></label
              >
              <div class="relative flex-1">
                <select
                  id="category"
                  class="w-1/2 border border-[#BDBDBD] text-sm px-3 py-2.5 rounded appearance-none"
                  v-model="product.category_id"
                >
                  <option disabled selected value="">Chọn danh mục</option>
                  <option
                    v-for="category in categories"
                    :key="category"
                    :value="category.id"
                  >
                    {{ category.name }}
                  </option>
                </select>
                <p
                  class="text-red-400 font-light text-sm mt-1"
                  v-for="e in v$.category_id.$errors"
                  :key="e"
                >
                  {{ e.$message }}
                </p>
                <div class="absolute right-[51%] top-1.5">
                  <Icon name="ic:round-arrow-drop-down" size="29" />
                </div>
              </div>
            </div>
            <div class="mt-5 flex">
              <label for="name" class="text-sm w-[200px] mt-3"
                >Tên sản phẩm <span class="text-red-400">(*)</span></label
              >
              <div class="flex-1">
                <input
                  type="text"
                  id="name"
                  placeholder="Tên sản phẩm"
                  autocomplete="off"
                  class="w-[86%] border border-[#BDBDBD] px-3 py-2.5 rounded text-sm"
                  v-model="product.name"
                />
                <p
                  class="text-red-400 font-light text-sm mt-1"
                  v-for="e in v$.name.$errors"
                  :key="e"
                >
                  {{ e.$message }}
                </p>
              </div>
            </div>
            <div class="mt-5 flex">
              <label for="slug" class="text-sm w-[200px] mt-3"
                >Slug <span class="text-red-400">(*)</span></label
              >
              <div class="flex-1">
                <input
                  type="text"
                  id="slug"
                  placeholder="Slug"
                  autocomplete="off"
                  class="w-[86%] border border-[#BDBDBD] px-3 py-2.5 rounded text-sm"
                  v-model="product.slug"
                />
                <p
                  class="text-red-400 font-light text-sm mt-1"
                  v-for="e in v$.slug.$errors"
                  :key="e"
                >
                  {{ e.$message }}
                </p>
              </div>
            </div>
            <div class="mt-5 flex">
              <label for="price" class="text-sm w-[200px] mt-3"
                >Đơn giá <span class="text-red-400">(*)</span></label
              >
              <div class="w-1/4">
                <input
                  type="text"
                  id="price"
                  placeholder="Đơn giá"
                  autocomplete="off"
                  min="1"
                  class="w-full border border-[#BDBDBD] px-3 py-2.5 rounded text-sm"
                  v-model="product.price"
                />
                <p
                  class="text-red-400 font-light text-sm mt-1"
                  v-for="e in v$.price.$errors"
                  :key="e"
                >
                  {{ e.$message }}
                </p>
              </div>
              <label for="discount" class="text-sm w-[150px] mt-3 ml-10"
                >Giảm giá (%)</label
              >
              <div class="w-1/4">
                <input
                  type="text"
                  id="discount"
                  placeholder="Giảm giá"
                  autocomplete="off"
                  class="w-full border border-[#BDBDBD] px-3 py-2.5 rounded text-sm"
                  v-model="product.discount"
                />
                <p
                  class="text-red-400 font-light text-sm mt-1"
                  v-for="e in v$.discount.$errors"
                  :key="e"
                >
                  {{ e.$message }}
                </p>
              </div>
            </div>
            <div class="mt-5 flex">
              <label for="quantity" class="text-sm w-[200px] mt-3"
                >Số lượng <span class="text-red-400">(*)</span></label
              >
              <div class="w-1/4">
                <input
                  type="number"
                  id="quantity"
                  placeholder="Số lượng"
                  autocomplete="off"
                  min="1"
                  class="w-full border border-[#BDBDBD] px-3 py-2.5 rounded text-sm"
                  v-model="product.quantity"
                />
                <p
                  class="text-red-400 font-light text-sm mt-1"
                  v-for="e in v$.quantity.$errors"
                  :key="e"
                >
                  {{ e.$message }}
                </p>
              </div>
            </div>
            <div class="mt-5 flex items-center">
              <label class="text-sm w-[200px]">Tình trạng</label>
              <div class="flex flex-1 gap-8">
                <div class="flex items-center">
                  <input
                    type="radio"
                    id="publish"
                    name="published"
                    value="1"
                    v-model="product.published"
                  />
                  <label for="publish" class="text-sm ml-2">Đang bán</label>
                </div>
                <div class="flex items-center">
                  <input
                    type="radio"
                    id="inactive"
                    name="published"
                    value="2"
                    v-model="product.published"
                  />
                  <label for="inactive" class="text-sm ml-2">Dừng bán</label>
                </div>
              </div>
            </div>
            <div class="mt-5">
              <h3 class="py-2">
                Hình Ảnh <span class="text-red-400">(*)</span>
              </h3>
              <p
                class="text-red-400 font-light text-sm mt-1"
                v-for="e in v$.images.$errors"
                :key="e"
              >
                {{ e.$message }}
              </p>
              <label for="image">
                <div
                  class="h-[200px] flex items-center justify-center border border-[#00000066] border-dotted hover:cursor-pointer"
                >
                  <div
                    class="px-[39px] py-[13px] border border-[#00000033] text-[20px] text-[#00000033] leading-[24.2px]"
                  >
                    Tải ảnh lên
                  </div>
                </div>
                <input
                  type="file"
                  hidden
                  multiple
                  id="image"
                  @change="uploadImage"
                />
              </label>
              <div
                v-if="product.images"
                class="mt-[30px] px-[30px] flex flex-wrap gap-[30px]"
              >
                <div
                  class="relative"
                  v-for="(image, i) in product.images"
                  :key="image"
                >
                  <img
                    :src="image.path"
                    width="120"
                    class="rounded border border-[#00000033]"
                  />
                  <div
                    class="absolute -top-3 -right-3 hover:cursor-pointer"
                    @click="deleteImage(i)"
                  >
                    <Icon
                      name="material-symbols-light:close-rounded"
                      size="25"
                      class="bg-gray-400 rounded-full"
                    />
                  </div>
                </div>
              </div>
            </div>
            <div class="mt-5">
              <h3 class="py-1 text-lg">Thông tin mô tả</h3>
              <div class="mt-3">
                <rich-editor
                  :value="product.description"
                  v-model="product.description"
                  @input="(event) => (product.description = event)"
                />
              </div>
            </div>
            <div class="mt-5">
              <h3 class="py-1 text-lg">Biến thể</h3>
              <div class="flex mt-3">
                <div class="w-[200px]">Màu sắc</div>
                <div class="flex-1 text-sm">
                  <button class="text-[#007006]" @click="addColor">
                    + Thêm màu sắc
                  </button>
                  <div
                    class="mt-5 flex"
                    v-for="(color, index) in product.colors"
                    :key="color"
                  >
                    <label class="w-[120px] mt-3"
                      >Tên màu <span class="text-red-400">(*)</span></label
                    >
                    <div class="w-[350px]">
                      <input
                        type="text"
                        placeholder="Tên màu"
                        autocomplete="off"
                        class="w-full border border-[#BDBDBD] px-3 py-2.5 rounded"
                        v-model="color.name"
                      />
                      <p
                        class="text-red-400 font-light text-sm mt-1"
                        v-if="v$.colors.$each.$response"
                      >
                        <span
                          v-for="e in v$.colors.$each.$response.$errors[index]
                            .name"
                          :key="e"
                          >{{ e.$message }}</span
                        >
                      </p>
                    </div>
                    <button
                      class="ml-8"
                      v-if="index > colorsLength - 1"
                      @click="deleteColor(index)"
                    >
                      <Icon name="bi:trash3" size="20" />
                    </button>
                  </div>
                </div>
              </div>
              <div class="flex mt-10">
                <div class="w-[200px]">Chất liệu</div>
                <div class="flex-1 text-sm">
                  <button class="text-[#007006]" @click="addMaterial">
                    + Thêm chất liệu
                  </button>
                  <div
                    class="mt-5 flex"
                    v-for="(material, index) in product.materials"
                    :key="material"
                  >
                    <label class="w-[120px] mt-3"
                      >Tên chất liệu
                      <span class="text-red-400">(*)</span></label
                    >
                    <div class="w-[350px]">
                      <input
                        type="text"
                        placeholder="Tên chất liệu"
                        autocomplete="off"
                        class="w-full border border-[#BDBDBD] px-3 py-2.5 rounded"
                        v-model="material.name"
                      />
                      <p
                        class="text-red-400 font-light text-sm mt-1"
                        v-if="v$.materials.$each.$response"
                      >
                        <span
                          v-for="e in v$.materials.$each.$response.$errors[
                            index
                          ].name"
                          :key="e"
                          >{{ e.$message }}</span
                        >
                      </p>
                    </div>
                    <button
                      class="ml-8"
                      v-if="index > materialsLength - 1"
                      @click="deleteMaterial(index)"
                    >
                      <Icon name="bi:trash3" size="20" />
                    </button>
                  </div>
                </div>
              </div>
              <div class="flex mt-10">
                <div class="w-[200px]">Kích thước (cm)</div>
                <div class="flex-1 text-sm">
                  <button class="text-[#007006]" @click="addSize">
                    + Thêm thông số
                  </button>
                </div>
              </div>
              <div
                class="mt-5 flex text-sm"
                v-for="(size, index) in product.sizes"
                :key="size"
              >
                <label class="w-[150px] mt-3 ml-[50px]"
                  >Thông số <span class="text-red-400">(*)</span></label
                >
                <div>
                  <div class="flex flex-1 gap-3">
                    <div class="w-[100px]">
                      <input
                        type="text"
                        placeholder="Ngang"
                        autocomplete="off"
                        class="w-full border border-[#BDBDBD] px-3 py-2.5 rounded"
                        v-model="size.length"
                      />
                    </div>
                    <div class="mt-3">x</div>
                    <div class="w-[100px]">
                      <input
                        type="text"
                        placeholder="Cao"
                        autocomplete="off"
                        class="w-full border border-[#BDBDBD] px-3 py-2.5 rounded"
                        v-model="size.width"
                      />
                    </div>
                    <div class="mt-3">x</div>
                    <div class="w-[100px]">
                      <input
                        type="text"
                        placeholder="Sâu"
                        autocomplete="off"
                        class="w-full border border-[#BDBDBD] px-3 py-2.5 rounded"
                        v-model="size.height"
                      />
                    </div>
                  </div>
                  <p
                    class="text-red-400 font-light text-sm mt-1"
                    v-if="sizeParameterError(index)"
                  >
                    <span v-for="e in sizeParameterError(index)" :key="e">{{
                      e.$message
                    }}</span>
                  </p>
                </div>
                <label class="w-[120px] mt-3 ml-[30px]">Đơn giá</label>
                <div>
                  <input
                    type="text"
                    placeholder="Đơn giá"
                    autocomplete="off"
                    class="w-full border border-[#BDBDBD] px-3 py-2.5 rounded"
                    v-model="size.price"
                  />
                  <p
                    class="text-red-400 font-light text-sm mt-1"
                    v-if="v$.sizes.$each.$response"
                  >
                    <span
                      v-for="e in v$.sizes.$each.$response.$errors[index].price"
                      :key="e"
                      >{{ e.$message }}</span
                    >
                  </p>
                </div>
                <button
                  class="ml-8"
                  v-if="index > sizesLength - 1"
                  @click="deleteSize(index)"
                >
                  <Icon name="bi:trash3" size="20" />
                </button>
              </div>
            </div>
            <div class="flex gap-16 mt-20 mb-[60px] text-sm">
              <NuxtLink
                to="/store/products"
                class="w-1/3 ml-[204px] px-[73px] py-[16px] border border-[#0000001A] rounded text-[#000000CC] leading-[19.36px] text-center"
              >
                Hủy Bỏ
              </NuxtLink>
              <!-- <button
                class="px-[63px] py-[16px] border border-[#0000001A] rounded bg-[#CCCCCC] text-[#000000B2] leading-[19.36px]"
              >
                Lưu Nháp
              </button> -->
              <button
                @click="submit()"
                class="w-1/3 px-[43px] py-[16px] border border-[#0000001A] rounded bg-[#007006] text-white leading-[19.36px]"
              >
                Lưu & Công Bố
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </StoreLayout>
</template>