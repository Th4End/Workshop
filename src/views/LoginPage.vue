<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import Header from "../components/Header.vue";
import Footer from "../components/Footer.vue";

const router = useRouter();

// Champs du formulaire
const email = ref("");
const password = ref("");
const errorMessage = ref(null);

// Fonction pour gérer la connexion
const login = async () => {
  // Réinitialiser le message d'erreur
  errorMessage.value = "";

  // Vérification basique des champs
  if (!email.value || !password.value) {
    errorMessage.value = "Tous les champs doivent être remplis.";
    return;
  }

  try {
    // Envoyer les données de connexion au backend via une requête POST
    const response = await fetch(
      "http://localhost/back-end/routes/routes.php?action=loginUser",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          email: email.value,
          password: password.value,
        }),
      }
    );

    if (!response.ok) {
      throw new Error(`Erreur HTTP: ${response.status}`);
    }

    const data = await response.json();

    if (data.success) {
      // Si la connexion est réussie, rediriger vers la page d'accueil ou le profil
      router.push("/home");
    } else {
      // Afficher le message d'erreur renvoyé par le backend
      errorMessage.value =
        data.message || "Échec de la connexion. Veuillez réessayer.";
    }
  } catch (error) {
    // Gérer les erreurs côté client
    errorMessage.value = "Erreur lors de la connexion : " + error.message;
    console.error(error.message);
  }
};
</script>

<template>
  <Header />
  <div class="login-page">
    <h1>Connexion</h1>
    <p>Connectez-vous pour accéder à votre profil et gérer vos réservations.</p>

    <div v-if="errorMessage" class="error-message">{{ errorMessage }}</div>

    <!-- Formulaire de connexion -->
    <form @submit.prevent="login" class="login-form">
      <div class="form-group">
        <label for="email">Adresse email :</label>
        <input
          type="email"
          id="email"
          v-model="email"
          placeholder="Entrez votre email"
          required
        />
      </div>

      <div class="form-group">
        <label for="password">Mot de passe :</label>
        <input
          type="password"
          id="password"
          v-model="password"
          placeholder="Entrez votre mot de passe"
          required
        />
      </div>

      <button type="submit">Connexion</button>
    </form>

    <p>
      Vous n'avez pas encore de compte ?
      <button @click="router.push('/register')">Inscription</button>
    </p>
  </div>
  <Footer />
</template>

<style scoped>
.login-page {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  min-height: calc(100vh - 60px - 80px);
  padding: 1rem;
  text-align: center;
  box-sizing: border-box;
}

.error-message {
  color: red;
  margin-bottom: 20px;
}

.login-form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  width: 100%;
  max-width: 400px;
  padding: 1rem;
  border: 1px solid #ccc;
  border-radius: 8px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.form-group {
  display: flex;
  flex-direction: column;
  text-align: left;
}

label {
  margin-bottom: 5px;
}

input {
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 16px;
  width: 100%;
  box-sizing: border-box;
}

button {
  background-color: var(--bg-color);
  color: var(--color-text);
  padding: 5px 10px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  width: 100%;
  max-width: 100px;
  margin: 10px auto;
}

button:hover {
  background-color: var(--bg-hover-color);
}

button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}
</style>
