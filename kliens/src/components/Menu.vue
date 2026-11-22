<script setup lang="ts">
import { ref } from 'vue'

const aktiv = ref('Otthon')
const linkek = ['Otthon', 'Úti célok', 'Országok']
</script>

<template>
  <nav class="menu">
    <span
      v-for="link in linkek"
      :key="link"
      class="link"
      :class="{ aktiv: aktiv === link }"
      @click="aktiv = link"
    >
      {{ link }}
    </span>

    <div class="pirula">
      🔍 <input placeholder="Keresés" /> <kbd>/</kbd>
    </div>
    <button class="pirula">🌐 magyar ⌄</button>
  </nav>
</template>

<style scoped>
.menu {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1.25rem 2rem;
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
</style>