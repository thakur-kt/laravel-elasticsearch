// router/index.js
import { createRouter, createWebHistory } from "vue-router";
import ProductCreate from "@/views/ProductCreate.vue";
import ProductEdit from "@/views/ProductEdit.vue";
import ProductList from "@/views/ProductList.vue";

const routes = [
    { path: "/", component: ProductList },
    { path: "/products/create", component: ProductCreate },
    { path: "/products/:id/edit", component: ProductEdit },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
