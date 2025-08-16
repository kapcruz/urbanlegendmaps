<script setup>
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import UrbanLegendPageItem from './UrbanLegendPageItem.vue'

const route = useRoute()
const router = useRouter()

const legendsBySlug = {
  'loira-do-banheiro': {
    title: 'Loira do Banheiro',
    content: `
      Uma lenda urbana brasileira… 
      <a href="https://pt.wikipedia.org/wiki/Loira_do_banheiro" target="_blank" rel="noopener">Wikipedia</a>
    `
  }
}

const legend = computed(() => legendsBySlug[route.params.slug] ?? null)

function closePage() {
  router.push('/') 
}
</script>

<template>
  <UrbanLegendPageItem
    :showOrClose="true"
    @closePage="closePage"
  >
    <template #title>
      {{ legend?.title ?? 'Lenda desconhecida' }}
    </template>
    <template #content>
      <div v-if="legend" v-html="legend.content"></div>
      <div v-else>Não encontramos detalhes para este slug.</div>
    </template>
  </UrbanLegendPageItem>
</template>

