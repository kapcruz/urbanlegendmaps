<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import UrbanLegendPageItem from './UrbanLegendPageItem.vue'
import { getLegendBySlug } from "../../../api/legend"

const route = useRoute()
const router = useRouter()

const legend = ref([])
const loading = ref(true)
const error = ref(null)

onMounted(async () => {
  try {
    const { data } = await getLegendBySlug(route.params.slug)
    legend.value = data[0]
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
})

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
      <div v-if="legend" v-html="legend.description"></div>
      <div v-else>Não encontramos detalhes para este slug.</div>
    </template>
  </UrbanLegendPageItem>
</template>

