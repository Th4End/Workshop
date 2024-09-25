<script setup>
import { ref } from "vue";
import Header from "../components/Header.vue";
import Footer from "../components/Footer.vue";

// Informations utilisateur
const user = ref({
  firstName: "John",
  lastName: "Doe",
  email: "johndoe@example.com",
  password: "",
  avatar: null, // Fichier d'image
  bio: "Développeur web passionné.",
  location: "Paris, France",
  phone: "123-456-7890",
  editable: false,
});

// Erreurs de validation
const errors = ref({
  firstName: "",
  lastName: "",
  email: "",
  password: "",
  bio: "",
  location: "",
  phone: "",
  avatar: "",
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
    errors.value.password =
      "Le mot de passe doit contenir au moins 6 caractères";
    isValid = false;
  }

  if (!user.value.bio) {
    errors.value.bio = "La bio est obligatoire";
    isValid = false;
  }

  if (!user.value.location) {
    errors.value.location = "La localisation est obligatoire";
    isValid = false;
  }

  if (!user.value.phone) {
    errors.value.phone = "Le téléphone est obligatoire";
    isValid = false;
  }

  return isValid;
};

// Fonction pour sauvegarder le profil
const saveProfile = () => {
  if (validate()) {
    if (confirm("Voulez-vous vraiment sauvegarder les modifications ?")) {
      user.value.editable = false;
      alert("Profil mis à jour avec succès !");
    }
  }
};

// Fonction pour gérer l'ajout d'un avatar
const handleAvatarChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = () => {
      user.value.avatar = reader.result;
    };
    reader.readAsDataURL(file);
  }
};
</script>

<template>
  <div class="main-container">
  <Header />
    <section class="profile-page">
      <h2>Profile Utilisateur</h2>

      <!-- Affichage des informations de l'utilisateur -->
      <div class="profile-info">
        <label for="firstName">Prénom </label>
        <input
          type="text"
          id="firstName"
          v-model="user.firstName"
          :disabled="!user.editable"
          :class="{ error: errors.firstName }"
        />
        <span v-if="errors.firstName">{{ errors.firstName }}</span>

        <label for="lastName">Nom </label>
        <input
          type="text"
          id="lastName"
          v-model="user.lastName"
          :disabled="!user.editable"
          :class="{ error: errors.lastName }"
        />
        <span v-if="errors.lastName">{{ errors.lastName }}</span>

        <label for="email">Email </label>
        <input
          type="email"
          id="email"
          v-model="user.email"
          :disabled="!user.editable"
          :class="{ error: errors.email }"
        />
        <span v-if="errors.email">{{ errors.email }}</span>

        <label for="password">Mot de passe </label>
        <input
          type="password"
          id="password"
          v-model="user.password"
          :disabled="!user.editable"
          :class="{ error: errors.password }"
        />
        <span v-if="errors.password">{{ errors.password }}</span>

        <label for="avatar">Avatar </label>
        <input
          type="file"
          id="avatar"
          @change="handleAvatarChange"
          :disabled="!user.editable"
        />
        <div v-if="user.avatar">
          <img :src="user.avatar" alt="Avatar" class="avatar-preview" />
        </div>

        <label for="bio">Bio </label>
        <textarea
          id="bio"
          v-model="user.bio"
          :disabled="!user.editable"
          :class="{ error: errors.bio }"
        ></textarea>
        <span v-if="errors.bio">{{ errors.bio }}</span>

        <label for="location">Localisation </label>
        <input
          type="text"
          id="location"
          v-model="user.location"
          :disabled="!user.editable"
          :class="{ error: errors.location }"
        />
        <span v-if="errors.location">{{ errors.location }}</span>

        <label for="phone">Téléphone </label>
        <input
          type="text"
          id="phone"
          v-model="user.phone"
          :disabled="!user.editable"
          :class="{ error: errors.phone }"
        />
        <span v-if="errors.phone">{{ errors.phone }}</span>
      </div>

      <!-- Boutons pour activer l'édition ou sauvegarder les modifications -->
      <div class="button-group">
        <button v-if="!user.editable" @click="user.editable = true">
          Modifier
        </button>
        <button v-if="user.editable" @click="saveProfile">Enregistrer</button>
        <button v-if="user.editable" @click="user.editable = false">
          Annuler
        </button>
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

.avatar-preview {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  object-fit: cover;
  margin: 10px 0;
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
