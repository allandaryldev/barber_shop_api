<template>
  <v-app  >
    <!-- <v-app-bar
      app
    >
      <v-toolbar-title v-text="title" />
      <v-spacer />

    </v-app-bar> -->
    <v-main class="bg-gradient-to-b from-gray-500 via-gray-700 to-gray-900">
      <v-container class="bg-transparent ">
        <Nuxt />
      </v-container>
    </v-main>

  </v-app>
</template>

<script>
export default {
  data(){
    return{
      title:"Barber Shop"
    }
  } 
}
</script>
