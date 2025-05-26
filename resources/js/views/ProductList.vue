<!-- views/ProductList.vue -->
<template>
  <div class="max-w-4xl mx-auto p-6 bg-white rounded shadow space-y-4">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold text-gray-800">Search Products</h1>
      <router-link to="/products/create">
        <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
          + Add Product
        </button>
      </router-link>
    </div>

    <ProductSearch />
  </div>
</template>


<script setup>
import ProductSearch from '@/components/ProductSearch.vue';
</script>
