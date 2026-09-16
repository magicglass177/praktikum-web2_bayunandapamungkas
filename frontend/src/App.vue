<script setup>
import { ref, onMounted } from 'vue'

const apiStatus = ref('Menghubungkan ke API...')
const framework = ref('')

onMounted(async () => {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/status')
    const data = await response.json()

    apiStatus.value = data.message
    framework.value = data.framework
  } catch (error) {
    apiStatus.value = 'API tidak dapat diakses'
  }
})
</script>

<template>
  <main>
    <h1>Pemrograman Web 2</h1>

    <h2>Status Backend</h2>

    <p>{{ apiStatus }}</p>

    <p v-if="framework">
      Framework: {{ framework }}
    </p>
  </main>
</template>