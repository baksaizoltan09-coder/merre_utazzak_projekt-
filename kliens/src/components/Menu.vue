<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { felhasznalo } from '../auth'

const emit = defineEmits<{
  (e: 'bejelentkezes'): void
  (e: 'profil'): void
  (e: 'fooldal'): void
}>()
const aktiv = ref('Főoldal')
const linkek = ['Főoldal', 'Úti célok', 'Kvíz', 'Felfedező', 'Tervező', 'Bakancslista']

const legordult = ref(false)
const nyitva = ref(false)

function onScroll() {
  legordult.value = window.scrollY > 20
}

function valaszt(link: string) {
  aktiv.value = link
  nyitva.value = false
  if (link === 'Főoldal') emit('fooldal')
}

function bejelentkezes() {
  nyitva.value = false
  emit('bejelentkezes')
}

function profil() {
  aktiv.value = ''
  nyitva.value = false
  emit('profil')
}

function onKey(e: KeyboardEvent) {
  if (e.key === 'Escape') nyitva.value = false
}

onMounted(() => {
  onScroll()
  window.addEventListener('keydown', onKey)
  window.addEventListener('scroll', onScroll, { passive: true })
})

onUnmounted(() => {
  window.removeEventListener('scroll', onScroll)
  window.removeEventListener('keydown', onKey)
})
</script>

<template>
  <nav class="menu" :class="{ legordult, nyitva }">
    <img class="logo" src="/favicon.png" alt="Merre utazzak" />

    <div class="tartalom">
      <div class="linkek">
        <span
          v-for="link in linkek"
          :key="link"
          class="link"
          :class="{ aktiv: aktiv === link }"
          @click="valaszt(link)"
        >
          {{ link }}
        </span>
      </div>

      <div class="jobb">
        <button v-if="felhasznalo" class="pirula" aria-label="Profil" @click="profil">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
               fill="none" stroke="currentColor" stroke-width="2"
               stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="7" r="4"/>
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
          </svg>
          {{ felhasznalo.name }}
        </button>
        <button v-else class="pirula" aria-label="Bejelentkezés" @click="bejelentkezes">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
               fill="none" stroke="currentColor" stroke-width="2"
               stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="7" r="4"/>
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
          </svg>
        </button>
        <button class="pirula" aria-label="Nyelv">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
               fill="none" stroke="currentColor" stroke-width="2"
               stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="12" r="10"/>
            <path d="M2 12h20"/>
            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
          </svg>
        </button>
      </div>
    </div>

    <button
      class="hamburger"
      :aria-expanded="nyitva"
      aria-label="Menü megnyitása"
      @click="nyitva = !nyitva"
    >
      <span></span>
      <span></span>
      <span></span>
    </button>
  </nav>
</template>

<style scoped>
.menu {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 100;
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1.25rem 2rem;
  color: white;
  background: transparent;
  transition: background 0.25s ease, padding 0.25s ease, box-shadow 0.25s ease;
}

.menu.legordult {
  padding-top: 0.75rem;
  padding-bottom: 0.75rem;
  background: black;
  backdrop-filter: blur(8px);
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.25);
}

.logo {
  height: 2.5rem;
  width: 2.5rem;
  object-fit: contain;
}

.tartalom {
  flex: 1;
  display: grid;
  grid-template-columns: 1fr auto 1fr;
  align-items: center;
}

.linkek {
  grid-column: 2;
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

.jobb {
  grid-column: 3;
  justify-self: end;
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.hamburger {
  display: none;
}

.link {
  padding-bottom: 0.3rem;
  font-weight: 600;
  cursor: pointer;
  border-bottom: 3px solid transparent;
}

.link.aktiv {
  border-color: #ffc107;
}

.pirula {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  padding: 0.4rem 0.8rem;
  color: white;
  font-size: 0.875rem;
  font-weight: 600;
  background: rgba(255, 255, 255, 0.12);
  border: 1px solid rgba(255, 255, 255, 0.35);
  border-radius: 999px;
}

.pirula svg {
  width: 1rem;
  height: 1rem;
  flex-shrink: 0;
}

button.pirula {
  cursor: pointer;
}

button.pirula:hover {
  background: rgba(255, 255, 255, 0.25);
}

@media (max-width: 768px) {
  .menu {
    justify-content: space-between;
  }

  .menu.nyitva {
    background: rgba(20, 30, 48, 0.97);
  }

  .hamburger {
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 5px;
    width: 2.75rem;
    height: 2.75rem;
    padding: 0.6rem;
    cursor: pointer;
    background: black;
    border: 1px solid black;
    border-radius: 999px;
  }

  .hamburger span {
    display: block;
    height: 2px;
    background: white;
    border-radius: 2px;
    transition: transform 0.25s ease, opacity 0.2s ease;
  }

  .nyitva .hamburger span:nth-child(1) {
    transform: translateY(7px) rotate(45deg);
  }

  .nyitva .hamburger span:nth-child(2) {
    opacity: 0;
  }

  .nyitva .hamburger span:nth-child(3) {
    transform: translateY(-7px) rotate(-45deg);
  }

  .tartalom {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    display: none;
    flex-direction: column;
    align-items: stretch;
    gap: 0.75rem;
    max-height: calc(100vh - 4.5rem);
    padding: 1rem 2rem 1.5rem;
    overflow-y: auto;
    background: black;
    box-shadow: 0 8px 16px black;
  }

  .nyitva .tartalom {
    display: flex;
  }

  .linkek {
    grid-column: auto;
    flex-direction: column;
    align-items: stretch;
    gap: 0;
  }

  .link {
    padding: 0.6rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.12);
  }

  .link.aktiv {
    color: #ffc107;
  }

  .jobb {
    grid-column: auto;
    justify-self: stretch;
    justify-content: space-between;
  }
}
</style>