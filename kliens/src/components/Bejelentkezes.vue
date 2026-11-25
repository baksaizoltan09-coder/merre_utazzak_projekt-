<script setup lang="ts">
import { ref } from 'vue'
import { bejelentkezes, regisztracio } from '../auth'

const emit = defineEmits<{ (e: 'bezar'): void }>()

const regisztral = ref(false)
const nev = ref('')
const email = ref('')
const jelszo = ref('')
const hiba = ref('')
const betolt = ref(false)

function valtas() {
  regisztral.value = !regisztral.value
  hiba.value = ''
}

async function kuldes() {
  hiba.value = ''
  betolt.value = true
  try {
    if (regisztral.value) {
      await regisztracio(nev.value, email.value, jelszo.value)
    } else {
      await bejelentkezes(email.value, jelszo.value)
    }
    emit('bezar')
  } catch (e) {
    hiba.value = e instanceof Error ? e.message : 'Valami hiba történt.'
  } finally {
    betolt.value = false
  }
}
</script>

<template>
  <div class="auth-hatter" @click.self="emit('bezar')">
    <div class="auth-doboz" role="dialog" aria-modal="true">
      <button class="auth-x" aria-label="Bezárás" @click="emit('bezar')">×</button>

      <h2>{{ regisztral ? 'Regisztráció' : 'Bejelentkezés' }}</h2>
      <p class="auth-alcim">
        {{ regisztral ? 'Hozd létre a fiókod, és mentsd el az utazásaid.' : 'Üdv újra! Jelentkezz be a fiókodba.' }}
      </p>

      <form @submit.prevent="kuldes">
        <label v-if="regisztral">
          Név
          <input v-model="nev" type="text" autocomplete="name" required />
        </label>

        <label>
          E-mail
          <input v-model="email" type="email" autocomplete="email" required />
        </label>

        <label>
          Jelszó
          <input
            v-model="jelszo"
            type="password"
            minlength="8"
            :autocomplete="regisztral ? 'new-password' : 'current-password'"
            required
          />
        </label>

        <p v-if="hiba" class="auth-hiba">{{ hiba }}</p>

        <button class="auth-kuldes" type="submit" :disabled="betolt">
          {{ betolt ? 'Egy pillanat...' : regisztral ? 'Regisztrálok' : 'Belépek' }}
        </button>
      </form>

      <p class="auth-valto">
        {{ regisztral ? 'Már van fiókod?' : 'Még nincs fiókod?' }}
        <button type="button" @click="valtas">
          {{ regisztral ? 'Jelentkezz be' : 'Regisztrálj' }}
        </button>
      </p>
    </div>
  </div>
</template>

<style scoped>
.auth-hatter {
  position: fixed;
  inset: 0;
  z-index: 100;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
  background: rgba(0, 0, 0, 0.55);
}

.auth-doboz {
  position: relative;
  width: 100%;
  max-width: 24rem;
  padding: 2rem 1.75rem;
  color: #000;
  background: white;
  border-radius: 16px;
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
}

.auth-x {
  position: absolute;
  top: 0.6rem;
  right: 0.9rem;
  font-size: 1.8rem;
  line-height: 1;
  color: #000;
  background: none;
  border: none;
  cursor: pointer;
}

h2 {
  margin: 0 0 0.4rem;
  font-size: 1.8rem;
}

.auth-alcim {
  margin: 0 0 1.5rem;
  color: #000;
}

form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

label {
  display: flex;
  flex-direction: column;
  gap: 0.35rem;
  font-size: 0.9rem;
  font-weight: 600;
}

input {
  padding: 0.7rem 0.9rem;
  font-size: 1rem;
  color: #000;
  background: white;
  border: 1px solid rgba(0, 0, 0, 0.4);
  border-radius: 10px;
}

input:focus {
  outline: 2px solid #12222c;
  outline-offset: 1px;
}

.auth-hiba {
  margin: 0;
  padding: 0.6rem 0.8rem;
  font-size: 0.9rem;
  color: #8a1c1c;
  background: #fdecec;
  border-radius: 8px;
}

.auth-kuldes {
  padding: 0.85rem;
  font-size: 1.05rem;
  font-weight: 700;
  color: white;
  background: #12222c;
  border: none;
  border-radius: 999px;
  cursor: pointer;
}

.auth-kuldes:hover:not(:disabled) {
  background: #1f3a4a;
}

.auth-kuldes:disabled {
  opacity: 0.6;
  cursor: default;
}

.auth-valto {
  margin: 1.25rem 0 0;
  text-align: center;
  font-size: 0.9rem;
}

.auth-valto button {
  padding: 0;
  font-size: inherit;
  font-weight: 700;
  color: #000;
  text-decoration: underline;
  background: none;
  border: none;
  cursor: pointer;
}
</style>