<script setup>
import Header from "../components/Header.vue";
import Footer from "../components/Footer.vue";
import Plan3d from "../components/Plan3d.vue"; // Importer le composant Plan3d

// Tu peux ajouter d'autres variables ou fonctions si nécessaire
</script>

<template>
  <Header />
  <div class="building-plan-page">
    <h1>Plan du bâtiment</h1>
    <p>
      Utilisez le plan ci-dessous pour vous repérer dans le campus et choisir
      l'étage souhaité.
    </p>

    <!-- Intégration du composant Plan3d -->
    <Plan3d />
  </div>
  <Footer />
</template>

<style scoped>
.building-plan-page {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding: 1rem;
  text-align: center;
}

h1 {
  margin-bottom: 20px;
}

p {
  margin-bottom: 30px;
}
</style>
