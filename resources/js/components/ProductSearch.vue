<template>
    <div class="max-w-4xl mx-auto p-6">
        <!-- Search Input -->
        <div class="relative">
            <input
                v-model="searchTerm"
                type="text"
                placeholder="Search products..."
                class="w-full px-5 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            />
        </div>

        <!-- Loading Indicator -->
        <div v-if="loading" class="mt-4 text-sm text-gray-500">
            Searching...
        </div>

        <!-- Product List -->
        <ul v-if="results.length" class="mt-6 grid grid-cols-1 gap-4">
            <li
                v-for="item in results"
                :key="item.id"
                class="border border-gray-200 rounded-xl p-5 bg-white shadow-sm hover:shadow-md transition"
            >
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">
                            {{ item.name }}
                        </h2>
                        <p class="mt-1 text-gray-600 text-sm">
                            {{ item.description }}
                        </p>
                    </div>
                    <div
                        class="flex flex-col sm:flex-row gap-2 sm:gap-3 mt-3 sm:mt-0"
                    >
                        <router-link :to="`/products/${item.id}/edit`">
                            <button
                                class="px-4 py-2 text-sm font-medium text-blue-600 border border-blue-500 rounded-md hover:bg-blue-50 transition"
                            >
                                Edit
                            </button>
                        </router-link>
                        <button
                            @click="deleteProduct(item.id)"
                            class="px-4 py-2 text-sm font-medium text-red-600 border border-red-500 rounded-md hover:bg-red-50 transition"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </li>
        </ul>

        <!-- No Results -->
        <div
            v-else-if="!loading && searchTerm"
            class="mt-6 text-center text-gray-400"
        >
            No products found.
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import axios from "axios";
import debounce from "lodash/debounce";

// Reactive state variables for search input, results, and loading state
const searchTerm = ref("");
const results = ref([]);
const loading = ref(false);

/**
 * Load initial products from the API (e.g., first 10 products).
 * Called on component mount and when search is cleared.
 */
const loadInitialProducts = async () => {
    loading.value = true;
    try {
        const response = await axios.get("/api/products", {
            params: { limit: 10 },
        });
        results.value = response.data.data;
        console.log("Initial products loaded:", results.value);
    } catch (error) {
        console.error("Failed to load products:", error);
    } finally {
        loading.value = false;
    }
};

/**
 * Search products from the API based on the search term.
 * Only triggers if the search term is not empty.
 */
const searchProducts = async () => {
    if (!searchTerm.value.length) {
        // If search is cleared, show initial products
        results.value = [];
        loadInitialProducts();
        return;
    }

    loading.value = true;

    try {
        // Send GET request to fetch products matching the search term
        const { data } = await axios.get("/api/products", {
            params: { q: searchTerm.value },
        });
        results.value = data.data; // Update results with API response
    } catch (err) {
        console.error(err);
        results.value = [];
    } finally {
        loading.value = false;
    }
};

// Debounced function to limit API calls while typing
const debouncedSearch = debounce((value) => {
    searchProducts(value);
}, 800); // adjust debounce delay as needed

// Watch searchTerm and trigger debounced search on change
watch(searchTerm, (newTerm) => {
    debouncedSearch(newTerm);
});

/**
 * Delete a product by its ID.
 * Asks for confirmation before sending the delete request.
 * Updates the local results list on success.
 */
const deleteProduct = async (productId) => {
    if (!confirm("Are you sure you want to delete this product?")) {
        return;
    }

    try {
        await axios.delete(`/api/products/${productId}`);
        // Remove the deleted product from the local results list
        results.value = results.value.filter((p) => p.id !== productId);
        alert("Product deleted successfully");
    } catch (error) {
        console.error(error);
        alert("Failed to delete the product");
    }
};

// Load initial products when the component is mounted
onMounted(() => {
    loadInitialProducts();
});
</script>
