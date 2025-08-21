<script setup>
const props = defineProps({
  showOrClose: {
    type: Boolean, 
    default: false 
  } 
})
</script>

<template>
  <section class="panel" v-if="showOrClose" role="dialog" aria-modal="true">
    <div class="wrapper">
      <header class="header">
        <h1 class="title">
          <slot name="title"></slot>
        </h1>

        <button
          type="button"
          class="fechar"
          aria-label="Fechar e voltar para o mapa"
          @click="$emit('closePage')"
        >
          <svg viewBox="0 0 24 24" height="24" width="24" fill="currentColor" role="img" aria-hidden="true">
            <path d="M13.41 12l4.3-4.29a1 1 0 10-1.42-1.42L12 10.59l-4.29-4.3a1 1 0 00-1.42 1.42l4.3 4.29-4.3 4.29a1 1 0 000 1.42 1 1 0 001.42 0l4.29-4.3 4.29 4.3a1 1 0 001.42 0 1 1 0 000-1.42z"/>
          </svg>
        </button>
      </header>

      <div class="content">
        <p v-if="$slots.content">
          <slot name="content"></slot>
        </p>
      </div>
    </div>
  </section>
</template>

<style>
.header {
  position: sticky;
  top: 0; 
  z-index: 10;
  display: flex; 
  align-items: center; 
  justify-content: center;
  padding: 1.2rem 4.8rem 1.2rem 1.2rem; 
  backdrop-filter: blur(2px);
}

.title {
  font-size: var(--fs-lg);
  text-align: center;
  font-weight: 600;
}

.fechar {
  position: absolute; 
  right: 1rem; 
  top: .8rem;
  width: 44px; 
  height: 44px; 
  display: grid; 
  place-items: center;
  border-radius: 999px;
  touch-action: manipulation;
}
.fechar:hover { 
  color: var(--highlight); 
}

@media (hover: none) {
  .fechar:active { 
    transform: scale(0.96); 
  }
}

.content {
  width: 100%;
  max-width: var(--container);
  padding: 0 1.2rem 2.4rem 1.2rem;
  overflow: auto;
}
.content p {
  font-size: var(--fs-md);
  line-height: 1.6;
  margin: 1rem 0;
}
.content a { 
  color: var(--highlight); 
  text-decoration: underline; 
}

@media (min-width: 768px) {
  .header { 
    padding: 1.6rem 6.4rem 1.6rem 1.6rem; 
  }
  .fechar { 
    right: 1.6rem; 
    top: 1.2rem; 
  }
  .content { 
    padding: 0 1.6rem 3.2rem 1.6rem; 
  }
}

.fechar:focus-visible {
  outline: 2px solid var(--highlight);
  outline-offset: 2px;
}
</style>
