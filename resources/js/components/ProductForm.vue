<!-- ProductForm.vue -->
<template>
    <form
        @submit.prevent="submitForm"
        class="max-w-md mx-auto p-6 bg-white rounded shadow space-y-4"
    >
        <input
            v-model="form.name"
            placeholder="Product Name"
            required
            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
        />

        <textarea
            v-model="form.description"
            placeholder="Description"
            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
        ></textarea>

        <input
            v-model.number="form.price"
            type="number"
            placeholder="Price"
            required
            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
        />

        <input
            v-model="form.stock"
            placeholder="Stock"
            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
        />

        <button
            type="submit"
            class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition"
        >
            {{ isEdit ? "Update" : "Create" }} Product
        </button>
    </form>
</template>

<script setup>
import { reactive, watchEffect } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

const props = defineProps({
    product: Object, // optional for editing
});

const router = useRouter();
const isEdit = !!props.product;

const form = reactive({
    name: "",
    description: "",
    price: 0,
    stock: "",
});

// pre-fill form in edit mode
watchEffect(() => {
    if (props.product) {
        Object.assign(form, props.product);
    }
});

const submitForm = async () => {
    try {
        if (isEdit) {
            await axios.put(`/api/products/${props.product.id}`, form);
        } else {
            await axios.post("/api/products", form);
        }
        alert("Success!");
        router.push("/");
    } catch (err) {
        console.error(err);
        alert("Something went wrong");
    }
};
</script>
