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
      <img class="footer-logo" src="../../public/logo_badgit.webp" alt="logo" @click="router.push('/')">
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
  background-color: var(--bg-color);
  padding: 0.5rem;
  box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.1);
}

.footer-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100%;
  margin-bottom: 0.5rem;
}

.footer-logo {
  width: 120px;
  height: 120px;
}

.footer-links {
  display: flex;
  gap: 1rem;
}

.footer-links span {
  color: var(--color-primary);
  text-decoration: none;
}

.footer-copyright {
  font-size: 12px;
  margin-top: 0.5rem;
  color: #666;
}
</style>
