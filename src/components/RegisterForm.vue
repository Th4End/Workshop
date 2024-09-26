<script setup>
import { ref } from "vue";

// Formulaire d'inscription
const form = ref({
  firstname: "",
  lastname: "",
  email: "",
  pswd: "",
});

// Messages d'erreur et de succès
const errorMsg = ref("");
const successMsg = ref("");

// Fonction pour soumettre le formulaire d'inscription
const submitForm = async () => {
  // Réinitialiser les messages
  errorMsg.value = "";
  successMsg.value = "";

  // Vérifier que tous les champs sont remplis
  if (
    !form.value.firstname ||
    !form.value.lastname ||
    !form.value.email ||
    !form.value.pswd
  ) {
    errorMsg.value = "Tous les champs sont obligatoires.";
    return;
  }

  try {
    // Envoyer les données au backend
    const response = await fetch(
      "http://localhost/back-end/routes/routes.php?action=registerUser",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          firstname: form.value.firstname,
          lastname: form.value.lastname,
          email: form.value.email,
          password: form.value.pswd,
        }),
      }
    );

    if (!response.ok) {
      throw new Error(`Erreur HTTP: ${response.status}`);
    }

    const data = await response.json();
    if (data.success) {
      // Si l'inscription est réussie
      successMsg.value =
        "Inscription réussie ! Vous pouvez maintenant vous connecter.";
      // Réinitialiser le formulaire
      form.value.firstname = "";
      form.value.lastname = "";
      form.value.email = "";
      form.value.pswd = "";
    } else {
      // Gérer les erreurs renvoyées par le backend
      errorMsg.value =
        data.message || "Une erreur est survenue lors de l'inscription.";
    }
  } catch (error) {
    // Gérer les erreurs côté client
    errorMsg.value = "Erreur lors de l'inscription : " + error.message;
    console.error(error.message);
  }
};
</script>

<template>
  <section class="container">
    <h2>Inscription</h2>
    <form @submit.prevent="submitForm">
      <label for="firstname">
        Prénom
        <input type="text" id="firstname" v-model="form.firstname" required />
      </label>
      <label for="lastname">
        Nom
        <input type="text" id="lastname" v-model="form.lastname" required />
      </label>
      <label for="email">
        Email
        <input type="email" id="email" v-model="form.email" required />
      </label>
      <label for="pswd">
        Mot de passe
        <input type="password" id="pswd" v-model="form.pswd" required />
      </label>

      <!-- Affichage des messages d'erreur ou de succès -->
      <p v-if="errorMsg" class="error">{{ errorMsg }}</p>
      <p v-if="successMsg" class="success">{{ successMsg }}</p>

      <button type="submit">M'inscrire</button>
    </form>
  </section>
</template>

<style scoped>
.container {
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
}

form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

label {
  display: flex;
  flex-direction: column;
}

button {
  background-color: var(--bg-color);
  color: var(--color-text);
  padding: 10px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

button:hover {
  background-color: var(--bg-hover-color);
}

.error {
  color: red;
  margin-top: 10px;
}

.success {
  color: green;
  margin-top: 10px;
}
</style>
