<script setup lang="ts">
import { computed } from 'vue'
import { felhasznalo, kijelentkezes } from '../auth'

const emit = defineEmits<{ (e: 'vissza'): void }>()

function datum(ertek?: string) {
  if (!ertek) return ''
  return new Date(ertek).toLocaleDateString('hu-HU', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
}

const adatok = computed(() => {
  const u = felhasznalo.value as any
  if (!u) return []
  return [
    { cimke: 'Név', ertek: u.name },
    { cimke: 'E-mail', ertek: u.email },
    { cimke: 'Regisztráció dátuma', ertek: datum(u.created_at) },
  ].filter((s) => s.ertek)
})

function kilepes() {
  kijelentkezes()
  emit('vissza')
}
</script>

<template>
  <main class="profil">
    <div class="doboz" v-if="felhasznalo">
      <div class="fejlec">
        <div class="avatar">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
               fill="none" stroke="currentColor" stroke-width="2"
               stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="7" r="4"/>
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
          </svg>
        </div>
        <h1>Profilom</h1>
      </div>

      <dl>
        <div v-for="sor in adatok" :key="sor.cimke" class="sor">
          <dt>{{ sor.cimke }}</dt>
          <dd>{{ sor.ertek }}</dd>
        </div>
      </dl>

      <button class="kilepes" @click="kilepes">Kilépés</button>
    </div>
  </main>
</template>

<style scoped>
.profil {
  position: relative;
  min-height: 100vh;
  padding: 7rem 1rem 3rem;
  color: #000;
  background: white;
}

.profil::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 5rem;
  background: black;
}

.doboz {
  position: relative;
  max-width: 32rem;
  margin: 0 auto;
  padding: 2rem 1.75rem;
  background: white;
  border: 1px solid rgba(0, 0, 0, 0.15);
  border-radius: 16px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}

.fejlec {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.avatar {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 3.5rem;
  height: 3.5rem;
  color: white;
  background: black;
  border-radius: 50%;
}

.avatar svg {
  width: 1.75rem;
  height: 1.75rem;
}

h1 {
  margin: 0;
  font-size: 1.8rem;
}

dl {
  margin: 0 0 1.5rem;
}

.sor {
  padding: 0.85rem 0;
  border-bottom: 1px solid rgba(0, 0, 0, 0.12);
}

dt {
  font-size: 0.8rem;
  font-weight: 600;
  color: rgba(0, 0, 0, 0.6);
}

dd {
  margin: 0.2rem 0 0;
  font-size: 1.05rem;
  font-weight: 600;
}

.kilepes {
  padding: 0.7rem 1.5rem;
  font-size: 1rem;
  font-weight: 700;
  color: white;
  background: black;
  border: none;
  border-radius: 999px;
  cursor: pointer;
}

.kilepes:hover {
  background: #8a1c1c;
}
</style>