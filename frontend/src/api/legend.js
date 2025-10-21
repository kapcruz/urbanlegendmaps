import api from "./connect"

export async function getLegends() {
  const response = await api.get("/legends")
  return response.data 
}

export async function getLegendBySlug(slug) {
  const response = await api.get(`/legends?slug=${slug}`)
  return response.data 
}
