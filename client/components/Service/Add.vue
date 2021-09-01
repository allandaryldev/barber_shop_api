<template>
  <vs-dialog v-model="modal" blur prevent-close not-close :loading="loading">
    <template #header>
      <PageTitle page_title="add service" />
    </template>

    <div>
      <ValidationObserver v-slot="{}" ref="serviceform">
        <form class="" @submit.prevent="addService">
          <div class="space-y-4">
            <div>
              <ValidationProvider
                vid="name"
                name="Name"
                rules="required"
                v-slot="{ errors }"
              >
                <b-field
                  label="Name"
                  label-position="inside"
                  :type="{ 'is-danger': errors[0] }"
                  :message="errors[0]"
                >
                  <b-input
                    v-model="fields.name"
                    placeholder="Name"
                    class="capitalize"
                  />
                </b-field>
              </ValidationProvider>
            </div>

            <div>
              <ValidationProvider
                vid="desc"
                name="Description"
                rules=""
                v-slot="{ errors }"
              >
                <b-field
                  label="Description"
                  label-position="inside"
                  :type="{ 'is-danger': errors[0] }"
                  :message="errors[0]"
                >
                  <b-input
                    v-model="fields.description"
                    placeholder="Input Here"
                    class=""
                    type="textarea"
                  />
                </b-field>
              </ValidationProvider>
            </div>
            <div>
              <ValidationProvider
                vid="value"
                name="Value"
                rules="required"
                v-slot="{ errors }"
              >
                <div>
                  <span>Service Fee or Charge</span>
                  <!-- <money
                    v-model="fields.value"
                    placeholder="Input Service Fee or Charge"
                    class="
                      w-full
                      p-2
                      text-base
                      bg-gray-700
                      text-gray-300
                      focus:shadow-outline-gray
                      rounded-lg
                    "
                  ></money> -->
                </div>
                <InputNumber
                  v-model="fields.value"
                  class="w-full p-0 m-0"
                  mode="currency"
                  currency="PHP"
                  locale="en-US"
                  :minimumFractionDigits="2"
                />
                <p v-if="errors.length" class="mt-2 text-sm text-red-500">
                  {{ errors[0] }}
                </p>
              </ValidationProvider>
            </div>
          </div>
        </form>
      </ValidationObserver>
    </div>

    <template #footer>
      <div class="flex">
        <vs-button dark border @click="close">Close</vs-button>
        <vs-button
          success
          border
          @click="addService"
          type="submit"
          :loading="loading"
          >Submit</vs-button
        >
      </div>
    </template>
  </vs-dialog>
</template>

<script>
import { mapActions } from 'vuex'
export default {
  props: {
    modal: {
      required: true,
      type: Boolean,
      default: false,
    },
    close: Function,
    get: Function,
  },
  data() {
    return {
      loading: false,
      fields: {
        name: '',
        description: '',
        value: '', //price
      },
    }
  },
  methods: {
    ...mapActions({
      store: 'services/create',
    }),
    async addService() {
      try {
        this.loading = true
        const serviceform = this.$refs.serviceform
        await serviceform.validate().then(async (success) => {
          if (success) {
            const data = { ...this.fields }
            await this.store(data)
            this.$vs.notification({
              title: 'Service Added',
              text: 'New Services Added',
              color: 'success',
            })
            this.close()
            this.get()
          }
        })
        this.loading = false
      } catch (error) {
        this.loading = false
        if (error.response.status === 422) {
          this.$vs.notification({
            title: 'Form Error',
            text: error.response.data.errors.name[0],
            color: 'danger',
          })
        }
      }
    },
  },
}
</script>

<style></style>
