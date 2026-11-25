<script setup lang="ts">
const ev = new Date().getFullYear()

const linkek = ['Főoldal', 'Úti célok', 'Kvíz', 'Húzogató', 'Tervező', 'Bakancslista']
</script>

<template>
  <footer class="lablec">
    <div class="tartalom">
      <div class="marka">
        <strong>Merre utazzak?</strong>
        <p>Utazásinspiráló és -tervező oldal magyar utazóknak.</p>
      </div>

      <nav class="linkek">
        <a v-for="link in linkek" :key="link" href="#">{{ link }}</a>
      </nav>
    </div>

    <p class="jog">© {{ ev }} Merre utazzak. Az árak és a repülési idők tájékoztató jellegűek.</p>
  </footer>
</template>

<style scoped>
.lablec {
  padding: 3rem max(2rem, calc((100% - 70rem) / 2)) 1.5rem;
  text-align: left;
  color: white;
  background: black;
}

.tartalom {
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 2rem;
}

.marka strong {
  font-size: 1.4rem;
}

.marka p {
  margin: 0.5rem 0 0;
  color: rgba(255, 255, 255, 0.7);
}

.linkek {
  display: flex;
  flex-wrap: wrap;
  gap: 1.5rem;
}

.linkek a {
  color: white;
  font-weight: 600;
  text-decoration: none;
  border-bottom: 3px solid transparent;
}

.linkek a:hover {
  border-color: #ffc107;
}

.jog {
  margin: 2rem 0 0;
  padding-top: 1.5rem;
  font-size: 0.9rem;
  color: rgba(255, 255, 255, 0.6);
  border-top: 1px solid rgba(255, 255, 255, 0.15);
}
</style>