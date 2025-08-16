<script setup>
import { ref } from 'vue'
import { LMap, LTileLayer, LMarker, LPopup } from '@vue-leaflet/vue-leaflet'
import * as L from 'leaflet'
import { useRouter } from 'vue-router'

import marker2x from 'leaflet/dist/images/marker-icon-2x.png'
import marker1x from 'leaflet/dist/images/marker-icon.png'
import markerShadow from 'leaflet/dist/images/marker-shadow.png'

L.Icon.Default.mergeOptions({
  iconRetinaUrl: marker2x,
  iconUrl: marker1x,
  shadowUrl: markerShadow
})

const router = useRouter()

const center = ref([-15.7942, -47.8825])
const zoom = ref(4)
const points = ref([
  {
    id: 1,
    lat: -22.9035, lon: -43.2096,
    name: 'Loira do Banheiro',
    slug: 'loira-do-banheiro',
    links: [
      { label: 'Detalhes', to: '/loira-do-banheiro' },
      { label: 'Wikipedia', href: 'https://pt.wikipedia.org/wiki/Loira_do_banheiro' } 
    ]
  }
])

function openLegend(p) {
  router.push(`/${p.slug}`)
}
</script>


<template>
  <div class="map-container">
    <l-map :zoom="zoom" :center="center" style="height:100vh">
      <l-tile-layer
        url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
        attribution="&copy; OpenStreetMap contributors"
      />
      <l-marker
        v-for="p in points"
        :key="p.id"
        :lat-lng="[p.lat, p.lon]"
        @click="openLegend(p)"
      >
        <l-popup>
          <div style="display:flex; flex-direction:column; gap:.5rem">
            <strong>{{ p.name }}</strong>
            <a :href="`/${p.slug}`" @click.prevent="openLegend(p)">Abrir detalhes</a>
            <a
              v-for="(lk, i) in p.links.filter(l => l.href)"
              :key="i"
              :href="lk.href"
              target="_blank" rel="noopener"
            >
              {{ lk.label }}
            </a>
          </div>
        </l-popup>
      </l-marker>
    </l-map>
  </div>
</template>

<style>
html, body, #app {
  height: 100%;
  margin: 0;
  padding: 0;
}

.map-container {
  height: 100vh;
  width: 100vw;
}

.l-map {
  height: 100%;
  width: 100%;
}
</style>