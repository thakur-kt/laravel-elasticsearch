<!-- views/ProductCreate.vue -->
<template>
  <div class="max-w-2xl mx-auto p-6 bg-white rounded shadow space-y-4">
    <div class="flex items-center justify-between">
      <h2 class="text-2xl font-semibold text-gray-800">Create Product</h2>
      <router-link :to="`/`">
        <button class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded">
          All Products
        </button>
      </router-link>
    </div>

    <ProductForm />
  </div>
</template>

<script setup>
import ProductForm from '@/components/ProductForm.vue';
</script>
