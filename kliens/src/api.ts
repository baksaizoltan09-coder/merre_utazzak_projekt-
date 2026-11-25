export const API = 'http://localhost:8000/api'

export async function lekeres<T>(utvonal: string): Promise<T> {
  const valasz = await fetch(`${API}${utvonal}`, { headers: { Accept: 'application/json' } })
  if (!valasz.ok) throw new Error('Nem sikerült betölteni az adatokat.')
  return valasz.json()
}