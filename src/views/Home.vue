<script setup>
import { useRouter } from "vue-router"; // Importer la méthode useRouter pour naviguer
import Header from "../components/Header.vue";
import Footer from "../components/Footer.vue";

const router = useRouter(); // Initialiser l'instance du routeur

const goToLogin = () => {
  router.push("/login"); // Naviguer vers la route de connexion
};

const goToRegister = () => {
  router.push("/register"); // Naviguer vers la route d'inscription
};
</script>

<template>
  <Header />
  <div class="home-page">
    <h1>Badg`it | Ta vie de campus, easy bro !</h1>
    <p>
      Avec Badg`it, un simple bip te suffit pour signaler ta présence et
      réserver un espace de travail. Plus besoin d'embêter l'admin', tout est
      fluide et rapide.
    </p>
    <div class="btn-group">
      <!-- Boutons Connexion et Inscription -->
      <button @click="goToLogin">Connexion</button>
      <button @click="goToRegister">Inscription</button>
    </div>
  </div>
  <Footer />
</template>

<style scoped>
.home-page {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  min-height: calc(100vh - 60px - 80px);
  padding: 1rem;
  text-align: center;
  box-sizing: border-box;
}

.btn-group {
  margin-top: 40px;
  display: flex;
  justify-content: center;
  gap: 10px;
}

button {
  background-color: var(--bg-color);
  color: var(--color-text);
  padding: 5px 10px;
  border: none;
  border-radius: 5px;
}
</style>
