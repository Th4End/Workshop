<script setup>
import { ref } from "vue";
import Header from "../components/Header.vue";
import Footer from "../components/Footer.vue";

// Variables pour stocker les données du formulaire
const name = ref("");
const email = ref("");
const message = ref("");
const errorMessage = ref("");
const successMessage = ref("");

// Fonction pour gérer l'envoi des messages via API
const sendMessage = async () => {
  // Réinitialiser les messages
  errorMessage.value = "";
  successMessage.value = "";

  // Vérifier que tous les champs sont remplis
  if (!name.value || !email.value || !message.value) {
    errorMessage.value = "Veuillez remplir tous les champs.";
    return;
  }

  try {
    // Envoyer les données au backend
    const response = await fetch(
      "http://localhost/back-end/routes/routes.php?action=sendContactMessage",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          name: name.value,
          email: email.value,
          message: message.value,
        }),
      }
    );

    if (!response.ok) {
      throw new Error(`Erreur HTTP: ${response.status}`);
    }

    const data = await response.json();
    if (data.success) {
      successMessage.value = "Votre message a été envoyé avec succès !";
      // Réinitialiser les champs du formulaire
      name.value = "";
      email.value = "";
      message.value = "";
    } else {
      errorMessage.value = data.message || "Erreur lors de l'envoi du message.";
    }
  } catch (error) {
    errorMessage.value = "Erreur lors de l'envoi du message : " + error.message;
    console.error(error.message);
  }
};
</script>

<template>
  <div class="main-container">
    <Header />
    <section class="contact-page">
      <h2>Contactez-nous</h2>

      <!-- Messages d'erreur ou de succès -->
      <div v-if="errorMessage" class="error-message">{{ errorMessage }}</div>
      <div v-if="successMessage" class="success-message">
        {{ successMessage }}
      </div>

      <!-- Formulaire de contact -->
      <div class="contact-form">
        <label for="name">Nom</label>
        <input type="text" id="name" v-model="name" placeholder="Votre nom" />

        <label for="email">Email</label>
        <input
          type="email"
          id="email"
          v-model="email"
          placeholder="Votre email"
        />

        <label for="message">Message</label>
        <textarea
          id="message"
          v-model="message"
          placeholder="Votre message"
        ></textarea>

        <button @click="sendMessage">Envoyer</button>
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

.contact-page {
  padding: 20px;
  text-align: center;
  flex-grow: 1;
}

.contact-form {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-bottom: 20px;
  margin: 0 auto;
  width: 100%;
  max-width: 400px;
}

.contact-form label {
  font-weight: bold;
}

.contact-form input,
.contact-form textarea {
  padding: 10px;
  font-size: 16px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

button {
  background-color: var(--bg-color);
  color: var(--color-text);
  padding: 5px 10px;
  margin: auto;
  border: none;
  border-radius: 5px;
}

.error-message {
  color: red;
  margin-bottom: 20px;
}

.success-message {
  color: green;
  margin-bottom: 20px;
}

@media (min-width: 768px) {
  .contact-form {
    max-width: 600px;
  }

  button {
    cursor: pointer;
  }

  button:hover {
    background-color: var(--color-secondary);
  }
}

@media (min-width: 1024px) {
  .contact-form {
    max-width: 800px;
  }
}
</style>
