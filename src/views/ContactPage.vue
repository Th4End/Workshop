<script setup>
import { ref } from "vue";

// Variables pour stocker les données du formulaire
const name = ref("");
const email = ref("");
const message = ref("");
const messages = ref([]);

// Fonction pour gérer l'envoi des messages
const sendMessage = () => {
  if (name.value && email.value && message.value) {
    messages.value.push({
      name: name.value,
      email: email.value,
      message: message.value,
      date: new Date().toLocaleString(),
    });
    alert("Votre message a été envoyé !");
    // Réinitialiser les champs après l'envoi
    name.value = "";
    email.value = "";
    message.value = "";
  } else {
    alert("Veuillez remplir tous les champs.");
  }
};
</script>

<template>
  <section class="contact-page">
    <h2>Contactez-nous</h2>

    <!-- Formulaire de contact -->
    <div class="contact-form">
      <label for="name">Nom :</label>
      <input
        type="text"
        id="name"
        v-model.trim="name"
        placeholder="Votre nom"
      />

      <label for="email">Email :</label>
      <input
        type="email"
        id="email"
        v-model.trim="email"
        placeholder="Votre email"
      />

      <label for="message">Message :</label>
      <textarea
        id="message"
        v-model.trim="message"
        placeholder="Votre message"
      ></textarea>

      <button @click="sendMessage">Envoyer</button>
    </div>

    <!-- Affichage des messages envoyés -->
    <div class="messages">
      <h3>Messages envoyés :</h3>
      <div v-for="(msg, index) in messages" :key="index" class="message">
        <p>
          <strong>{{ msg.name }} ({{ msg.email }})</strong>
        </p>
        <p>{{ msg.message }}</p>
        <p>
          <em>Envoyé le {{ msg.date }}</em>
        </p>
      </div>
    </div>
  </section>
</template>

<style scoped>
.contact-page {
  padding: 20px;
  text-align: center;
}

.contact-form {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-bottom: 20px;
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
  padding: 10px 20px;
  background-color: #3498db;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

button:hover {
  background-color: #2980b9;
}

.messages {
  text-align: left;
}

.message {
  border-bottom: 1px solid #ccc;
  margin-bottom: 10px;
  padding-bottom: 10px;
}
</style>
