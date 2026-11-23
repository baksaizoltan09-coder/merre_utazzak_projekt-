<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

const aktiv = ref('Főoldal')
const linkek = ['Főoldal', 'Úti célok', 'Kvíz', 'Húzogató', 'Tervező', 'Bakancslista']

const legordult = ref(false)
const nyitva = ref(false)

function onScroll() {
  legordult.value = window.scrollY > 20
}

function valaszt(link: string) {
  aktiv.value = link
  nyitva.value = false
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
    <div class="tartalom">
      <span
        v-for="link in linkek"
        :key="link"
        class="link"
        :class="{ aktiv: aktiv === link }"
        @click="valaszt(link)"
      >
        {{ link }}
      </span>

      <div class="pirula">
        🔍 <input placeholder="Keresés" /> <kbd>/</kbd>
      </div>
      <button class="pirula">🌐 magyar ⌄</button>
    </div>

    <img class="logo" src="/favicon.png" alt="Merre utazzak" />

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

.tartalom {
  display: contents;
}

.hamburger {
  display: none;
}

.menu.legordult {
  padding-top: 0.75rem;
  padding-bottom: 0.75rem;
  background: rgba(20, 30, 48, 0.92);
  backdrop-filter: blur(8px);
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.25);
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
  gap: 0.5rem;
  padding: 0.6rem 1rem;
  color: white;
  font-size: 1rem;
  font-weight: 600;
  background: rgba(255, 255, 255, 0.12);
  border: 1px solid rgba(255, 255, 255, 0.35);
  border-radius: 999px;
}

input {
  width: 7rem;
  color: white;
  font-size: 1rem;
  background: none;
  border: none;
  outline: none;
}

input::placeholder {
  color: white;
}

kbd {
  padding: 0 0.5rem;
  border: 1px solid white;
  border-radius: 6px;
}

.logo {
  height: 2.5rem;
  width: 2.5rem;
  margin-left: auto;
  object-fit: contain;
}

@media (max-width: 768px) {
  .menu {
    justify-content: space-between;
  }

  .menu.nyitva {
    background: rgba(20, 30, 48, 0.97);
  }

  .logo {
    order: -1;
    margin-left: 0;
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
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.35);
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
    background: rgba(20, 30, 48, 0.97);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.25);
  }

  .nyitva .tartalom {
    display: flex;
  }

  .link {
    padding: 0.6rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.12);
  }

  .link.aktiv {
    color: #ffc107;
  }

  input {
    flex: 1;
    width: auto;
    min-width: 0;
  }

  button.pirula {
    align-self: flex-start;
  }
}
</style>