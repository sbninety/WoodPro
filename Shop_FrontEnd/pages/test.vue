<template>
  <button @click="showAlert">Hello world</button>
</template>

<script setup>
// const { $swal } = useNuxtApp();

// const showAlert = () => {
//   $swal.fire({
//     icon: "success",
//     title: "Your work has been saved",
//     showConfirmButton: false,
//     timer: 1500,
//   });
// };

import Swal from "sweetalert2";

const showAlert = () => {
  Swal.fire({
    icon: "success",
    title: "Your work has been saved",
    showConfirmButton: false,
    timer: 1500,
  });
};
</script>