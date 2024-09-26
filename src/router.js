import { createRouter, createWebHistory } from "vue-router";
import Home from "./views/Home.vue";
import BookingPage from "./views/BookingPage.vue";
import ContactPage from "./views/ContactPage.vue";
import ProfilePage from "./views/ProfilePage.vue";
import LoginPage from "./views/LoginPage.vue";
import RegisterPage from "./views/RegisterPage.vue";
import BuildingPlan from "./views/BuildingPlan.vue";

const routes = [
  { path: "/", component: Home },
  { path: "/reserver-salle", component: BookingPage },
  { path: "/contact", component: ContactPage },
  { path: "/profil", component: ProfilePage },
  { path: "/login", component: LoginPage }, // Route pour la connexion
  { path: "/register", component: RegisterPage }, // Route pour l'inscription
  { path: "/building-plan", component: BuildingPlan },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
