<script setup>
import { useRouter } from 'vue-router';

const router = useRouter();

const goToFAQ = () => {
  router.push('/faq'); // Remplacez par le chemin de page FAQ
};

const goToContact = () => {
  router.push('/contact'); // Remplacez par le chemin de page Contact
};
</script>

<template>
  <footer class="footer-container">
    <div class="footer-content">
      <h1>Logo</h1>
      <nav class="footer-links">
        <span @click="goToFAQ">FAQ</span>
        <span @click="goToContact">Contact</span>
      </nav>
    </div>
    <div class="footer-copyright">
      © 2024 Badg'it. Tous droits réservés.
    </div>
  </footer>
</template>

<style scoped>
.footer-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  height: 120px;
  background-color: #f5f5f5;
  padding: 0.5rem;
}

.footer-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100%;
  margin-bottom: 0.5rem;
}

.footer-links {
  display: flex;
  gap: 1rem;
}

.footer-links span {
  color: #000000;
  text-decoration: none;
}

.footer-copyright {
  font-size: 12px;
  margin-top: 0.5rem;
  color: #666;
}
</style>
