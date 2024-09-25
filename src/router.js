import { createRouter, createWebHistory } from "vue-router";
import Home from "./views/Home.vue";
import BookingPage from "./views/BookingPage.vue";
import ContactPage from "./views/ContactPage.vue";
import ProfilePage from "./views/ProfilePage.vue";

const routes = [
  { path: "/", component: Home },
  { path: "/reserver-salle", component: BookingPage },
  { path: "/contact", component: ContactPage },
  { path: "/profil", component: ProfilePage },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
