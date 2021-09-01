<template>
  <vs-dialog v-model="modal" blur prevent-close not-close :loading="loading">
    <template #header>
      <PageTitle page_title="Service" />
    </template>

    <div>
      <div v-if="!isEdit">
        <div >
          <span class="text-xs">Name: </span> 
           <p class="pl-2 font-bold capitalize">
             {{ service.name }}
           </p>
           </div>
        <div>
            <span class="text-xs">Fee or Charge: </span> 
          <p class="pl-2 font-bold">
            {{ $moneyFormat.format(service.value) }}
          </p>
        </div>
        <div>
          <span class="text-xs">Description</span>
          <div class="pl-2 whitespace-pre-line text-sm bg-gray-100">
            {{ service.description ? service.description:"No Description" }}
          </div>
        </div>
      </div>
      <div v-else>
        <ValidationObserver v-slot="{}" ref="serviceform">
          <form class="" @submit.prevent="updateService">
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
    </div>

    <template #footer>
      <div class="flex" v-if="!isEdit">
        <vs-button success border :loading="loading" @click="close"
          >close</vs-button
        >
        <vs-button warn border :loading="loading" @click="setToEdit"
          ><box-icon
            type="solid"
            name="edit"
            animation="tada-hover"
            color="orange"
          ></box-icon
        ></vs-button>
      </div>
      <div class="flex" v-else>
        <vs-button success border @click="isEdit = !isEdit">Cancel</vs-button>
        <vs-button transparent @click="updateService"
          ><box-icon
            name="send"
            type="solid"
            animation="tada-hover"
            color="orange"
          ></box-icon
        ></vs-button>
      </div>
    </template>
  </vs-dialog>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
export default {
  props: {
    modal: {
      required: true,
      type: Boolean,
      default: false,
    },
    close: Function,
    get: Function,
    service_id: Number,
  },
  data() {
    return {
      loading: false,
      isEdit: false,
      fields: {
        name: '',
        description: '',
        value: '',
      },
    }
  },
  computed: {
    ...mapGetters({
      service: 'services/service',
    }),
  },
  watch: {
    modal(value) {
      if (value) {
        this.getService()
      }
    },
  },
  methods: {
    ...mapActions({
      show: 'services/show',
      update: 'services/update',
    }),
    async getService() {
      try {
        this.loading = true
        await this.show(this.service_id)
        this.fields = await { ...this.service }
        this.loading = false
      } catch (error) {
        this.loading = false
      }
    },
    async updateService() {
      try {
        this.loading = true
        const serviceform = this.$refs.serviceform
        await serviceform.validate().then(async (success) => {
          if (success) {
            const data = { id: this.service_id, formData: { ...this.fields } }
            await this.update(data)
            this.close()
            this.get()
            this.setToEdit();
          }
        })
        this.loading = false
      } catch (error) {
        this.loading = false
        console.log(error)
        // if (error.response.status === 422) {
        //   this.$vs.notification({
        //     title: 'Form Error',
        //     text: error.response.data.errors.name[0],
        //     color: 'danger',
        //   })
        // }
      }
    },
    setToEdit() {
      this.isEdit = !this.isEdit
      this.fields = { ...this.service }
    },
  },
}
</script>

<style></style>
