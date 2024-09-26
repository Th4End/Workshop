<script setup>
import Header from "../components/Header.vue";
import Footer from "../components/Footer.vue";
import RegisterForm from "../components/RegisterForm.vue"; // Importer le composant RegisterForm
</script>

<template>
  <Header />
  <div class="register-page">
    <h1>Inscription</h1>
    <p>
      Rejoignez-nous et profitez des services de Badg'it pour gérer vos
      réservations de salles et plus encore.
    </p>

    <!-- Intégrer le formulaire d'inscription -->
    <RegisterForm />
  </div>
  <Footer />
</template>

<style scoped>
.register-page {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  min-height: calc(100vh - 60px - 80px);
  padding: 1rem;
  text-align: center;
  box-sizing: border-box;
}
</style>
