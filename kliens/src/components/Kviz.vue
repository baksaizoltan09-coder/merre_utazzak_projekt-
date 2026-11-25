<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { lekeres } from '../api'

const emit = defineEmits<{ bezar: [] }>()

interface UtiCel {
  id: number
  nev: string
  orszag: string
  szezon: string | null
  koltseg: string | null
  kviz_cimkek: string[]
}

const kerdesek = [
  {
    kerdes: 'Milyen környezetben töltenéd a szabadságot?',
    valaszok: [
      { szoveg: '🏖️ Tengerparton', cimke: 'tenger' },
      { szoveg: '🏙️ Nagyvárosban', cimke: 'varos' },
      { szoveg: '⛰️ Hegyek között', cimke: 'hegy' },
    ],
  },
  {
    kerdes: 'Mit csinálnál a legszívesebben?',
    valaszok: [
      { szoveg: '🛋️ Pihennék', cimke: 'pihenes' },
      { szoveg: '🏛️ Látnivalókat néznék', cimke: 'kultura' },
      { szoveg: '🥾 Túráznék, felfedeznék', cimke: 'kaland' },
    ],
  },
  {
    kerdes: 'Milyen időjárást szeretsz?',
    valaszok: [
      { szoveg: '☀️ Forró, napos', cimke: 'meleg' },
      { szoveg: '🌤️ Enyhe', cimke: 'enyhe' },
      { szoveg: '❄️ Hűvös, friss', cimke: 'hideg' },
    ],
  },
  {
    kerdes: 'Mennyit költenél?',
    valaszok: [
      { szoveg: '💸 Minél olcsóbb legyen', cimke: 'olcso' },
      { szoveg: '💶 Közepes költségvetés', cimke: 'arkozepes' },
      { szoveg: '💎 Megengedhetem magamnak', cimke: 'dragabb' },
    ],
  },
  {
    kerdes: 'Milyen estéket szeretsz?',
    valaszok: [
      { szoveg: '🍝 Finom vacsora', cimke: 'gasztro' },
      { szoveg: '🎉 Buli, szórakozás', cimke: 'buli' },
      { szoveg: '🌙 Csendes, nyugodt este', cimke: 'nyugi' },
    ],
  },
  {
    kerdes: 'Meddig repülnél szívesen?',
    valaszok: [
      { szoveg: '✈️ Max. 2 órát', cimke: 'rovid' },
      { szoveg: '✈️ 2–4 órát', cimke: 'kozepes' },
      { szoveg: '✈️ Bármeddig', cimke: 'hosszu' },
    ],
  },
]

const uticelok = ref<UtiCel[]>([])
const betolt = ref(true)
const hiba = ref('')

onMounted(async () => {
  try {
    uticelok.value = await lekeres<UtiCel[]>('/uti-celok?limit=500')
  } catch {
    hiba.value = 'Nem sikerült betölteni az úti célokat. Fut a szerver?'
  } finally {
    betolt.value = false
  }
})

const valasztottak = ref<string[]>([])
const aktualis = computed(() => kerdesek[valasztottak.value.length])

const legjobbak = computed(() =>
  uticelok.value
    .map((u) => {
      const egyezes = valasztottak.value.filter((c) => u.kviz_cimkek.includes(c)).length
      return { ...u, szazalek: Math.round((egyezes / kerdesek.length) * 100) }
    })
    .sort((a, b) => b.szazalek - a.szazalek)
    .slice(0, 3),
)

function valaszt(cimke: string) {
  valasztottak.value.push(cimke)
}

function vissza() {
  valasztottak.value.pop()
}

function ujra() {
  valasztottak.value = []
}
</script>

<template>
  <div class="hatter" @click.self="emit('bezar')">
    <div class="doboz">
      <button class="bezar" @click="emit('bezar')">✕</button>

      <template v-if="aktualis">
        <div class="folyamat">
          <div class="sav" :style="{ width: (valasztottak.length / kerdesek.length) * 100 + '%' }"></div>
        </div>
        <p class="szamlalo">{{ valasztottak.length + 1 }} / {{ kerdesek.length }}</p>

        <h3>{{ aktualis.kerdes }}</h3>

        <button
          v-for="v in aktualis.valaszok"
          :key="v.cimke"
          class="valasz"
          @click="valaszt(v.cimke)"
        >
          {{ v.szoveg }}
        </button>

        <button v-if="valasztottak.length > 0" class="vissza" @click="vissza">← Vissza</button>
      </template>

      <template v-else>
        <h3>A három legjobb úti célod</h3>

        <p v-if="betolt" class="uzenet">Betöltés...</p>
        <p v-else-if="hiba" class="uzenet hiba">{{ hiba }}</p>
        <p v-else-if="legjobbak.length === 0" class="uzenet">Még nincs úti cél az adatbázisban.</p>

        <template v-else>
          <div v-for="(u, i) in legjobbak" :key="u.id" class="talalat">
            <span class="helyezes">{{ i + 1 }}</span>
            <div class="adatok">
              <strong>{{ u.nev }}, {{ u.orszag }}</strong>
              <small>Szezon: {{ u.szezon ?? '–' }} · Költség: {{ u.koltseg ?? '–' }}</small>
            </div>
            <span class="szazalek">{{ u.szazalek }}%</span>
          </div>

          <p class="jelmagyarazat">€ olcsó · €€ közepes · €€€ drágább</p>
        </template>

        <button class="valasz kozep" @click="ujra">Újra kitöltöm</button>
      </template>
    </div>
  </div>
</template>

<style scoped>
.hatter {
  position: fixed;
  inset: 0;
  z-index: 200;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 1rem;
  background: rgba(0, 0, 0, 0.6);
}

.doboz {
  position: relative;
  width: 100%;
  max-width: 34rem;
  padding: 2rem;
  color: #000;
  background: white;
  border-radius: 24px;
  box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
}

.bezar {
  position: absolute;
  top: 1rem;
  right: 1rem;
  font-size: 1.2rem;
  color: #000;
  background: none;
  border: none;
  cursor: pointer;
}

.folyamat {
  height: 6px;
  margin-top: 1rem;
  background: #eef1f3;
  border-radius: 999px;
  overflow: hidden;
}

.sav {
  height: 100%;
  background: #ffc107;
  transition: width 0.3s ease;
}

.szamlalo {
  margin: 0.75rem 0 0;
  color: #000;
  font-size: 0.9rem;
}

h3 {
  margin: 0.5rem 0 1rem;
  font-size: 1.6rem;
}

.valasz {
  display: block;
  width: 100%;
  margin-top: 0.75rem;
  padding: 0.9rem 1rem;
  font-size: 1rem;
  font-weight: 600;
  text-align: left;
  color: #000;
  cursor: pointer;
  background: #f4f6f6;
  border: 2px solid transparent;
  border-radius: 12px;
  transition: border-color 0.15s, background 0.15s;
}

.valasz:hover {
  background: #fff8e1;
  border-color: #ffc107;
}

.kozep {
  text-align: center;
}

.vissza {
  margin-top: 1rem;
  color: #000;
  font-weight: 600;
  background: none;
  border: none;
  cursor: pointer;
}

.uzenet {
  margin: 1rem 0;
  color: #000;
}

.hiba {
  padding: 0.6rem 0.8rem;
  color: #8a1c1c;
  background: #fdecec;
  border-radius: 8px;
}

.talalat {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-top: 0.75rem;
  padding: 1rem;
  background: #f4f6f6;
  border-radius: 12px;
}

.helyezes {
  font-size: 1.4rem;
  font-weight: 800;
  color: #000;
}

.adatok {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 0.2rem;
}

.adatok small {
  color: #000;
}

.szazalek {
  font-size: 1.3rem;
  font-weight: 800;
}

.jelmagyarazat {
  margin: 1rem 0 0;
  color: #000;
  font-size: 0.85rem;
}
</style>