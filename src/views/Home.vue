<script setup>
import Header from "../components/Header.vue";
import Footer from "../components/Footer.vue";
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

</style>
