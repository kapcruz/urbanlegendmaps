<script setup>

const props = defineProps({
  showOrClose: {
    type: Boolean,
    default: false
  }
})

</script>

<template>
  <section v-if="(showOrClose && showOrClose) || !showOrClose">
    <div class="wrapper">
      <div class="header">
        <div class="title">
          <slot name="title"></slot>
          <button
            type="button"
            class="fechar"
            aria-label="Fechar e voltar para o mapa"
            @click="$emit('closePage')"
          >
            <svg
              viewBox="0 0 24 24"
              height="48"
              width="48"
              fill="currentColor"
              role="img"
            >
              <path
                d="M13.41 12l4.3-4.29a1 1 0 10-1.42-1.42L12 10.59l-4.29-4.3a1 1 0 00-1.42 1.42l4.3 4.29-4.3 4.29a1 1 0 000 1.42 1 1 0 001.42 0l4.29-4.3 4.29 4.3a1 1 0 001.42 0 1 1 0 000-1.42z"
              />
            </svg>
          </button>
        </div>
      </div>
      <div class="content">
        <p>
          <slot name="content"></slot>
        </p>
      </div>
    </div>
  </section>
</template>

<style>

section {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  background: var(--background);
  color: var(--white);
}

.wrapper {
  max-width: var(--container);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.title {
  font-size: var(--large);
  margin-bottom: var(--large);

  .fechar {
    cursor: pointer; 
    position: absolute; 
    top: 1rem; 
    right: 1rem;
    display: flex;
    align-items: center;
    &:hover {
      color: var(--highlight);
    }
  }
}

.content {
  min-width: var(--container);
  p {
    font-size: var(--medium);
    line-height: var(--medium);
  }
  a {
    color: var(--highlight);
  }
}

</style>