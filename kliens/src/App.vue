<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import Menu from './components/Menu.vue'
import Footer from './components/Footer.vue'
import Kviz from './components/Kviz.vue'
import Bejelentkezes from './components/Bejelentkezes.vue'
import Profil from './components/Profil.vue'
import { lekeres } from './api'

interface Ut {
  id: number
  nev: string
  orszag: string
  kep: string | null
  cimke: string | null
  idojaras: string | null
  ar: number | null
  cimkek: string[]
}

const masodik = ref<HTMLElement | null>(null)
const kvizNyitva = ref(false)
const authNyitva = ref(false)
const profilNyitva = ref(false)
const utak = ref<Ut[]>([])

const funkciok = [
  { cim: 'Kvíz', leiras: '6 kérdés után megmutatjuk a három legjobb úti célt egyezési százalékkal, szezonnal és költséggel.' },
  { cim: 'Felfedező', leiras: 'Jobbra húzod, ami tetszik, balra, ami nem. Gombokkal és a nyilakkal is működik.' },
  { cim: 'Úti célok', leiras: 'Rengeteg hely, keresés névre, országra vagy hangulatra, költségszintekkel és repülési idővel.' },
  { cim: 'Tervező', leiras: 'Napi program 1–14 napra, amit szabadon átírhatsz, bővíthetsz és elmentheted.' },
  { cim: 'Bakancslista és utazások', leiras: 'Gyűjtsd egy helyen a vágyott helyeket, a „Voltam ott” gombbal pedig átkerülnek az utazásaid közé.' },
]

const gombLeirasok = {
  kviz: 'Válaszolj 6 rövid kérdésre, és megmutatjuk a három legjobb úti célt.',
  felfedezo: 'Jobbra húzod, ami tetszik, balra, ami nem.',
}

const pontok = [
  { x: 15, y: 25 },
  { x: 40, y: 20 },
  { x: 62, y: 15 },
  { x: 82, y: 30 },
  { x: 25, y: 62 },
  { x: 85, y: 68 },
]

const repulo = ref({ x: 15, y: 25, szog: 0 })
let idozito: number

function veletlenPont() {
  return pontok[Math.floor(Math.random() * pontok.length)]!
}

function kovetkezoPont() {
  let uj = veletlenPont()
  while (uj.x === repulo.value.x && uj.y === repulo.value.y) {
    uj = veletlenPont()
  }

  const dx = ((uj.x - repulo.value.x) / 100) * window.innerWidth
  const dy = ((uj.y - repulo.value.y) / 100) * window.innerHeight
  const szog = (Math.atan2(dy, dx) * 180) / Math.PI + 90
  const kulonbseg = ((szog - repulo.value.szog + 540) % 360) - 180

  repulo.value = { x: uj.x, y: uj.y, szog: repulo.value.szog + kulonbseg }
}

onMounted(() => {
  kovetkezoPont()
  idozito = window.setInterval(kovetkezoPont, 5000)

  lekeres<Ut[]>('/uti-celok?nepszeru=1&limit=5')
    .then((adat) => (utak.value = adat))
    .catch(() => (utak.value = []))
})

onUnmounted(() => clearInterval(idozito))

function profilMegnyitas() {
  profilNyitva.value = true
  window.scrollTo(0, 0)
}

function fooldalMegnyitas() {
  profilNyitva.value = false
  window.scrollTo(0, 0)
}

function kviz() {
  kvizNyitva.value = true
}

function felfedezo() {
  alert('Felfedezős keresés indul...')
}

function tovabb() {
  masodik.value?.scrollIntoView({ behavior: 'smooth' })
}
</script>

<template>
  <Menu
    @bejelentkezes="authNyitva = true"
    @profil="profilMegnyitas"
    @fooldal="fooldalMegnyitas"
  />

  <Profil v-if="profilNyitva" @vissza="fooldalMegnyitas" />

  <template v-else>
    <main class="kezdolap">
      <svg
        class="repulo"
        viewBox="0 0 24 24"
        :style="{
          left: repulo.x + '%',
          top: repulo.y + '%',
          transform: `translate(-50%, -50%) rotate(${repulo.szog}deg)`,
        }"
      >
        <path
          fill="currentColor"
          d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"
        />
      </svg>

      <h1>Tervezd meg a következő utad</h1>
      <p>Nem tudod, hová menj? Mi segítünk választani.</p>

      <div class="gombok">
        <div class="tip-wrap">
          <button class="gomb" @click="kviz">Kvíz</button>
          <span class="tip" role="tooltip">{{ gombLeirasok.kviz }}</span>
        </div>
        <div class="tip-wrap">
          <button class="gomb" @click="felfedezo">Felfedező</button>
          <span class="tip" role="tooltip">{{ gombLeirasok.felfedezo }}</span>
        </div>
      </div>

      <button class="tovabb" @click="tovabb">
        <span>Tovább</span>
        <span class="nyil">⌄</span>
      </button>
    </main>

    <section class="bemutatkozas">
      <div class="szoveg">
        <h2>Mi az a Merre utazzak?</h2>
        <p>
          Magyar nyelvű utazásinspiráló és -tervező oldal. Segít kitalálni, hová
          utazz, megírja a napi programot, és egy helyen tartja a bakancslistádat
          és az emlékeidet. Az árak és a repülési idők magyarországi utazóra
          vonatkoznak.
        </p>
      </div>

      <div class="funkciok">
        <div v-for="(f, i) in funkciok" :key="i" class="funkcio">
          <span class="szam">0{{ i + 1 }}</span>
          <h3>{{ f.cim }}</h3>
          <p>{{ f.leiras }}</p>
        </div>
      </div>
    </section>

    <section ref="masodik" class="masodik">
      <div class="fejlec">
        <div>
          <h2>Legnépszerűbb utazásaink</h2>
          <p>Gondosan válogatott úti célok, amelyeket az utazók szeretnek</p>
        </div>
        <button class="osszes">Összes úti cél megtekintése</button>
      </div>

      <div class="kartyak">
        <article v-for="ut in utak" :key="ut.id" class="kartya">
          <div class="kep">
            <img v-if="ut.kep" :src="ut.kep" :alt="ut.nev" />
            <span v-if="ut.cimke" class="cimke">{{ ut.cimke }}</span>
            <div v-if="ut.ar" class="ar">Tól <strong>{{ ut.ar.toLocaleString('hu-HU') }} Ft</strong> /nap</div>
          </div>

          <h3>{{ ut.nev }}</h3>
          <p class="hely">{{ ut.orszag }} {{ ut.idojaras }}</p>
          <div class="cimkek">
            <span v-for="c in ut.cimkek" :key="c">{{ c }}</span>
          </div>
        </article>
      </div>
    </section>

    <section class="kartya-szekcio">
      <div class="szekcio-fejlec">
        <h2>Fő funkcióink</h2>
        <p>Ezek az oldal legfőbb részei.</p>
      </div>

      <div class="info-kartya vilagos">
        <span class="felirat">Utazási kvíz</span>
        <h2>Készen állsz a következő utazásra?</h2>
        <div class="sor">
          <p>Válaszolj 6 rövid kérdésre, és megmutatjuk, hová érdemes utaznod.</p>
          <button class="gomb" @click="kviz">Kitöltöm a kvízt! <span>→</span></button>
        </div>
      </div>

      <div class="info-kartya szurke">
        <span class="felirat">Felfedező</span>
        <h2>Bővíteni akarod a bakancslistád?</h2>
        <p>Húzd jobbra azokat az utazásokat, amik tetszenek, és balra azokat, amik nem.</p>
        <button class="gomb" @click="felfedezo">Bővítem a bakancslistát! <span>→</span></button>
      </div>
    </section>
  </template>

  <Footer />

  <Kviz v-if="kvizNyitva" @bezar="kvizNyitva = false" />
  <Bejelentkezes v-if="authNyitva" @bezar="authNyitva = false" />
</template>

<style>
html, body, #app {
  margin: 0;
  padding: 0;
  width: 100%;
  max-width: none;
  display: block;
  font-family: sans-serif;
}

.kezdolap {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
  color: white;
  background:
    linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
    url('/hatter.jpg') center / cover;
  overflow: hidden;
  position: relative;
}

.repulo {
  position: absolute;
  width: 2rem;
  height: 2rem;
  color: rgba(255, 255, 255, 0.85);
  pointer-events: none;
  transition: left 5s linear, top 5s linear, transform 1s ease;
}

.gombok {
  display: flex;
  gap: 1.25rem;
  margin-top: 2rem;
  flex-wrap: wrap;
  justify-content: center;
}

.gomb {
  padding: 1rem 2.5rem;
  font-size: 1.25rem;
  font-weight: 600;
  color: white;
  background: rgba(255, 255, 255, 0.15);
  border: 2px solid rgba(255, 255, 255, 0.6);
  border-radius: 999px;
  cursor: pointer;
  backdrop-filter: blur(6px);
  transition: transform 0.2s, background 0.2s;
}

.gomb:hover {
  transform: scale(1.05);
  background: rgba(255, 255, 255, 0.3);
}

.tovabb {
  position: absolute;
  bottom: 0.5rem;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  flex-direction: column;
  align-items: center;
  color: white;
  font-size: 1.1rem;
  font-weight: 600;
  background: none;
  border: none;
  cursor: pointer;
}

.nyil {
  font-size: 2rem;
  line-height: 1;
  animation: pattog 1.5s ease-in-out infinite;
}

@keyframes pattog {
  0%, 100% { transform: translateY(0); }
  50%      { transform: translateY(8px); }
}

.masodik {
  min-height: 100vh;
  padding: 4rem 2rem;
  background: white;
  color: #000;
}

.fejlec {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 1rem;
  max-width: 70rem;
  margin: 0 auto 4rem;
}

.fejlec h2 {
  margin: 0;
  font-size: 2.5rem;
}

.fejlec p {
  margin: 0.5rem 0 0;
  color: #000;
}

.osszes {
  padding: 0.9rem 1.5rem;
  color: white;
  font-weight: 700;
  background: #12222c;
  border: none;
  border-radius: 999px;
  cursor: pointer;
}

.kartyak {
  display: flex;
  justify-content: center;
  align-items: flex-start;
  flex-wrap: wrap;
  gap: 2rem;
}

.kartya {
  position: relative;
  width: 15rem;
  padding: 0.7rem 0.7rem 1.2rem;
  color: #000;
  background: white;
  border-radius: 24px;
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
  transition: transform 0.2s;
}

.kartya:nth-child(odd) { transform: rotate(-2deg); }
.kartya:nth-child(even) { transform: rotate(1.5deg); margin-top: 2.5rem; }

.kartya:hover {
  transform: rotate(0) scale(1.03);
}

.kartya::before {
  content: '';
  position: absolute;
  top: -0.8rem;
  left: 50%;
  width: 6rem;
  height: 1.6rem;
  transform: translateX(-50%) rotate(-3deg);
  background: rgba(255, 200, 60, 0.7);
}

.kartya:nth-child(2n)::before { background: rgba(120, 190, 240, 0.7); }
.kartya:nth-child(3n)::before { background: rgba(120, 180, 190, 0.7); }

.kep {
  position: relative;
  height: 15rem;
  background: #dfe5e8;
  border-radius: 16px;
}

.kep img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 16px;
}

.cimke {
  position: absolute;
  top: 0.8rem;
  left: 0.8rem;
  padding: 0.3rem 0.8rem;
  font-size: 0.7rem;
  font-weight: 800;
  text-transform: uppercase;
  background: #ffc107;
  border-radius: 999px;
}

.ar {
  position: absolute;
  right: -1rem;
  bottom: -1.5rem;
  padding: 0.6rem 1.2rem;
  text-align: center;
  background: #ffe27a;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
  transform: rotate(2deg);
}

.ar strong {
  display: block;
  font-size: 1.4rem;
}

.kartya h3 {
  min-height: 2rem;
  margin: 1.5rem 0 0.3rem;
  font-size: 1.8rem;
}

.hely {
  min-height: 1.2rem;
  margin: 0 0 0.8rem;
  font-weight: 700;
  color: #000;
}

.cimkek {
  display: flex;
  flex-wrap: wrap;
  gap: 0.4rem;
}

.cimkek span {
  padding: 0.3rem 0.8rem;
  font-size: 0.8rem;
  font-weight: 600;
  color: #000;
  background: #eef1f3;
  border-radius: 999px;
}

.bemutatkozas {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 4rem;
  padding: 5rem max(2rem, calc((100% - 70rem) / 2));
  background: white;
  color: #000;
}

.szoveg {
  flex: 1 1 20rem;
}

.szoveg h2 {
  margin: 0 0 1rem;
  font-size: 2.5rem;
}

.szoveg p {
  margin: 0;
  font-size: 1.1rem;
  line-height: 1.7;
  color: #000;
}

.funkciok {
  flex: 2 1 30rem;
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1.25rem;
}

.funkcio {
  padding: 1.5rem;
  background: #f4f6f6;
  border: 1px solid rgba(0, 0, 0, 0.15);
  border-radius: 20px;
}

.funkcio:last-child {
  grid-column: 1 / -1;
}

.szam {
  font-size: 1.2rem;
  font-weight: 800;
  color: #000;
}

.funkcio h3 {
  margin: 0.5rem 0;
}

.funkcio p {
  margin: 0;
  line-height: 1.6;
  color: #000;
}

.kartya-szekcio {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
  padding: 4rem max(2rem, calc((100% - 70rem) / 2));
  background: white;
  color: #000;
}

.szekcio-fejlec {
  margin-bottom: 1.5rem;
  text-align: left;
}

.szekcio-fejlec h2 {
  margin: 0;
  font-size: 2.5rem;
}

.szekcio-fejlec p {
  margin: 0.5rem 0 0;
  color: #000;
}

.info-kartya {
  padding: 2rem 1.75rem;
  color: #000;
  text-align: left;
  border: 1px solid rgba(0, 0, 0, 0.35);
  border-radius: 14px;
}

.info-kartya.vilagos { background: #fafafa; }
.info-kartya.szurke  { background: #ececec; }

.felirat {
  display: block;
  margin-bottom: 0.5rem;
  font-size: 0.8rem;
  letter-spacing: 0.06em;
  text-transform: uppercase;
}

.info-kartya h2 {
  margin: 0 0 1rem;
  font-size: 2.5rem;
  font-weight: 500;
  line-height: 1.1;
}

.info-kartya p {
  margin: 0;
  font-size: 1rem;
  color: #000;
}

.info-kartya .sor {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 1rem;
}

.info-kartya .gomb {
  padding: 0.9rem 1.6rem;
  font-size: 1.1rem;
  color: white;
  background: #12222c;
  border: none;
}

.info-kartya.szurke .gomb {
  margin-top: 1.5rem;
}

.info-kartya .gomb:hover {
  background: #1f3a4a;
}

.info-kartya .gomb span {
  margin-left: 0.4rem;
}

@media (max-width: 600px) {
  .funkciok {
    grid-template-columns: 1fr;
  }

  .szekcio-fejlec h2 {
    font-size: 1.8rem;
  }

  .info-kartya h2 {
    font-size: 1.8rem;
  }
}

.tip-wrap {
  position: relative;
  display: inline-block;
}

.tip {
  position: absolute;
  bottom: calc(100% + 12px);
  left: 50%;
  transform: translateX(-50%) translateY(4px);
  width: max-content;
  max-width: 15rem;
  padding: 0.5rem 0.8rem;
  font-size: 0.9rem;
  font-weight: 400;
  line-height: 1.3;
  color: white;
  background: #1f2430;
  border: 1px solid rgba(255, 255, 255, 0.25);
  border-radius: 8px;
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.15s ease, transform 0.15s ease;
  z-index: 10;
}

.tip::after {
  content: '';
  position: absolute;
  top: 100%;
  left: 50%;
  transform: translateX(-50%);
  border: 6px solid transparent;
  border-top-color: #1f2430;
}

.tip-wrap:hover .tip,
.tip-wrap:has(:focus-visible) .tip {
  opacity: 1;
  transform: translateX(-50%) translateY(0);
}
</style>