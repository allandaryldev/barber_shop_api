<template>
  <v-row class="py-4">
    <v-col>
      <div class="text-xl font-black capitalize">
        {{ page_title }}
      </div>
    </v-col>
    <v-col>
      <slot></slot>
    </v-col>
  </v-row>
</template>

<script>
export default {
  props: {
    page_title: {
      required: true,
      type: String,
      default: 'Page',
    },
  },
}
</script>

<style></style>
