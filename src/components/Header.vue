<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";

const showMenu = ref(false);
const router = useRouter();

const goToProfile = () => {
  // Logique pour accéder au profil de l'utilisateur connecté
  router.push("/profil");
};

const toggleMenu = () => {
  showMenu.value = !showMenu.value;
};

const handleLogout = () => {
  // Logique pour la déconnexion
  console.log("Déconnexion");
};
</script>

<template>
  <header class="header-container">
    <div class="header-left">
      <font-awesome-icon
        :icon="['fas', 'user']"
        class="icon-user"
        @click="goToProfile"
      />
    </div>

    <h1 class="header-title">Badg'it</h1>

    <div class="header-right">
      <font-awesome-icon
        :icon="['fas', 'bars']"
        class="icon-burger"
        @click="toggleMenu"
      />
      <div v-if="showMenu" class="dropdown-menu">
        <ul>
          <li @click="router.push('/reserver-salle')">Réserver une salle</li>
          <li @click="router.push('/contact')">Contact</li>
          <li @click="handleLogout">Déconnexion</li>
        </ul>
      </div>
    </div>
  </header>
</template>

<style scoped>
.header-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  height: 60px;
  padding: 10px;
  background-color: #f5f5f5;
}

.header-left {
  flex: 1;
}

.header-title {
  flex: 2;
  text-align: center;
  font-size: 24px;
}

.header-right {
  flex: 1;
  position: relative;
  text-align: right;
}

.icon-user,
.icon-burger {
  font-size: 24px;
  cursor: pointer;
}

.dropdown-menu {
  position: absolute;
  top: 100%;
  right: 0;
  width: 200px;
  background-color: white;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
  border-radius: 4px;
  z-index: 1000;
  padding: 10px 0;
}

.dropdown-menu ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.dropdown-menu ul li {
  padding: 12px 20px;
  text-align: center;
  font-size: 16px;
}

@media (min-width: 768px) {
  .icon-user,
  .icon-burger {
    cursor: pointer;
  }

  .dropdown-menu ul li {
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  .dropdown-menu ul li:hover {
    background-color: #f5f5f5;
  }
}
</style>
