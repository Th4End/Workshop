<script setup>
import { ref, onMounted } from "vue";
import Header from "../components/Header.vue";
import Footer from "../components/Footer.vue";

// Informations utilisateur
const user = ref({
  firstName: "",
  lastName: "",
  email: "",
  password: "",
  role_id: "",
});

// Erreurs de validation
const errors = ref({
  firstName: "",
  lastName: "",
  email: "",
  password: "",
  role_id: "",
});

// Fonction de validation
const validate = () => {
  let isValid = true;

  // Validation des champs
  if (!user.value.firstName) {
    errors.value.firstName = "Le prénom est obligatoire";
    isValid = false;
  }

  if (!user.value.lastName) {
    errors.value.lastName = "Le nom est obligatoire";
    isValid = false;
  }

  if (!user.value.email || !user.value.email.includes("@")) {
    errors.value.email = "L'adresse e-mail est obligatoire et doit être valide";
    isValid = false;
  }

  if (!user.value.password || user.value.password.length < 6) {
    errors.value.password = "Le mot de passe doit contenir au moins 6 caractères";
    isValid = false;
  }

  if (!user.value.role_id) {
    errors.value.role_id = "Le rôle est obligatoire";
    isValid = false;
  }

  return isValid;
};

// Fetch pour récupérer les informations du profil
const fetchUserProfile = async () => {
  try {
    const response = await fetch(
      "http://localhost/back-end/routes/routes.php?action=getUserProfile",
      {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
        },
      }
    );

    // Vérification si la réponse est correcte
    if (!response.ok) {
      throw new Error(`Erreur HTTP: ${response.status}`);
    }

    const data = await response.json(); // Lire la réponse JSON directement
    user.value = data; // Mettre à jour l'utilisateur avec les données récupérées
  } catch (error) {
    console.error("Erreur lors de la récupération du profil :", error.message);
  }
};

// Fetch pour mettre à jour le profil
const updateUserProfile = async () => {
  if (validate()) {
    try {
      const response = await fetch(
        "http://localhost/back-end/routes/routes.php?action=updateUserProfile",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(user.value),
        }
      );

      if (!response.ok) {
        throw new Error(`Erreur HTTP: ${response.status}`);
      }

      const data = await response.json();
      if (data.message === "Profil mis à jour avec succès") {
        alert("Profil mis à jour avec succès !");
      } else {
        console.error("Erreur de mise à jour :", data.message);
      }
    } catch (error) {
      console.error("Erreur lors de la mise à jour du profil :", error.message);
    }
  }
};

// Appeler fetchUserProfile lors du montage du composant
onMounted(() => {
  fetchUserProfile();
});
</script>

<template>
  <div class="main-container">
    <Header />
    <section class="profile-page">
      <h2>Profil Utilisateur</h2>

      <!-- Affichage des informations de l'utilisateur -->
      <div class="profile-info">
        <label for="firstName">Prénom </label>
        <input
          type="text"
          id="firstName"
          v-model="user.firstName"
          :class="{ error: errors.firstName }"
        />
        <span v-if="errors.firstName">{{ errors.firstName }}</span>

        <label for="lastName">Nom </label>
        <input
          type="text"
          id="lastName"
          v-model="user.lastName"
          :class="{ error: errors.lastName }"
        />
        <span v-if="errors.lastName">{{ errors.lastName }}</span>

        <label for="email">Email </label>
        <input
          type="email"
          id="email"
          v-model="user.email"
          :class="{ error: errors.email }"
        />
        <span v-if="errors.email">{{ errors.email }}</span>

        <label for="password">Mot de passe </label>
        <input
          type="password"
          id="password"
          v-model="user.password"
          :class="{ error: errors.password }"
        />
        <span v-if="errors.password">{{ errors.password }}</span>

        <label for="role_id">Rôle </label>
        <input
          type="text"
          id="role_id"
          v-model="user.role_id"
          :class="{ error: errors.role_id }"
        />
        <span v-if="errors.role_id">{{ errors.role_id }}</span>
      </div>

      <!-- Boutons pour modifier et sauvegarder -->
      <div class="button-group">
        <button @click="updateUserProfile">Enregistrer</button>
        <button @click="fetchUserProfile">Annuler</button>
      </div>
    </section>
    <Footer />
  </div>
</template>


<style scoped>
.main-container {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

/* Centrage simple du contenu */
.profile-page {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 20px;
  flex-grow: 1;
}

.profile-info {
  display: flex;
  flex-direction: column;
  gap: 10px;
  width: 300px;
  margin-bottom: 20px;
}

/* Centrage des boutons */
.button-group {
  display: flex;
  justify-content: center;
  gap: 10px;
}

button {
  background-color: var(--bg-color);
  color: var(--color-text);
  padding: 5px 10px;
  margin: auto;
  border: none;
  border-radius: 5px;
}

button:disabled {
  background-color: #ccc;
}

.error {
  border-color: red;
}

span {
  color: red;
  font-size: 12px;
}

@media (min-width: 768px) {
  button {
    cursor: pointer;
  }

  button:disabled {
  cursor: not-allowed;
  }

  button:hover {
    background-color: var(--bg-color);
  }

  button:hover:not(:disabled) {
    background-color: var(--bg-color);
  }
}
</style>
