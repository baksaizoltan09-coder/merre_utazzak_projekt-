import { ref } from 'vue'

const API = 'http://localhost:8000/api'

export interface Felhasznalo {
  id: number
  name: string
  email: string
}

const mentett = localStorage.getItem('felhasznalo')
export const felhasznalo = ref<Felhasznalo | null>(mentett ? JSON.parse(mentett) : null)

async function kuldes(utvonal: string, adat: object, token?: string) {
  const valasz = await fetch(`${API}${utvonal}`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      Accept: 'application/json',
      ...(token ? { Authorization: `Bearer ${token}` } : {}),
    },
    body: JSON.stringify(adat),
  })
  const json = await valasz.json().catch(() => ({}))

  if (!valasz.ok) {
    const elso = json.errors ? (Object.values(json.errors)[0] as string[]) : null
    throw new Error(elso?.[0] ?? json.uzenet ?? 'Valami hiba történt.')
  }
  return json
}

function mentes(json: { token: string; felhasznalo: Felhasznalo }) {
  localStorage.setItem('token', json.token)
  localStorage.setItem('felhasznalo', JSON.stringify(json.felhasznalo))
  felhasznalo.value = json.felhasznalo
}

export async function regisztracio(nev: string, email: string, jelszo: string) {
  mentes(await kuldes('/regisztracio', { nev, email, jelszo }))
}

export async function bejelentkezes(email: string, jelszo: string) {
  mentes(await kuldes('/bejelentkezes', { email, jelszo }))
}

export async function kijelentkezes() {
  const token = localStorage.getItem('token') ?? undefined
  try {
    await kuldes('/kijelentkezes', {}, token)
  } finally {
    localStorage.removeItem('token')
    localStorage.removeItem('felhasznalo')
    felhasznalo.value = null
  }
}