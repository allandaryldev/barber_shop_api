<template>
  <vs-dialog  v-model="modal" square not-padding blur prevent-close not-close  :loading="loading" >
    <template #header >
      <div class="bg-red-500  w-full px-2 pt-4">
        <p class="text-left font-bold text-white">Confirm to Delete</p>
      </div>
    </template>
    
    <div class="px-2 py-4">
      <slot></slot>
    </div>

    <template #footer>
      <div class="flex ">
        <vs-button success border class="animate-pulse" @click="close">Cancel</vs-button>
      <vs-button danger border @click="handleConfirm">Confirm</vs-button>
      </div>
    </template>
  </vs-dialog>
</template>

<script>
export default {
  props: {
    modal: {
      required: true,
      type: Boolean,
      default: false,
    },
    get: Function,
    close: {Function},
  },
  data() {
    return {
      loading: false,
    }
  },methods:{
    handleConfirm(){
      this.$emit('confirm',true)
      this.get();
    }
  }
}
</script>

<style></style>
