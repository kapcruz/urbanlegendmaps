import api from "./connect"

export function getLegends() {
  return api.get("/legends")
}

export function getLegendBySlug(slug) {
  return api.get(`/legends?slug=${slug}`)
}
