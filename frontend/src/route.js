import { createRouter, createWebHistory } from 'vue-router'
import UrbanLegendsMap from './components/maps/UrbanLegendMap.vue'
import UrbanLegendPage from './components/maps/page/UrbanLegendPage.vue'

export default createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', component: UrbanLegendsMap },
    { path: '/:slug', component: UrbanLegendPage },
    { path: '/:pathMatch(.*)*', component: { template: '<div>404</div>' } }
  ]
})
