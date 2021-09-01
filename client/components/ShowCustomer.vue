<template>
  <vs-dialog v-model="modal" blur prevent-close not-close :loading="loading">
    <template #header>
      <div class="w-full">
        <div v-if="!isEdit" class="flex justify-between items-center">
          <div class="">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-20 w-20"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
          </div>
          <div v-if="!loading">
            <vue-qr :text="customer.name" :size="100"></vue-qr>
          </div>
        </div>
        <div v-else>Update Customer</div>
      </div>
    </template>

    <div class="px-2 py-4">
      <div v-if="!isEdit">
        <div class="grid grid-cols-1 md:grid-cols-2">
          <div class="font-bold">
            <span class="text-sm font-normal"> Name:</span>
            <span class="capitalize">
               {{ customer.name }}
            </span>
          </div>
          <div class="font-bold">
            <span class="text-sm font-normal">Mobile #: </span>
            {{ customer.mobile_no }}
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 gap-y-4">
          <div class="font-bold">
            <span class="text-sm font-normal"> Birth Date:</span>
            {{ customer.birth_date_formatted }}
          </div>
          <div class="font-bold">
            <span class="text-sm font-normal">Group: </span>
            <span class="uppercase">
              {{ customer.group }}
            </span>
          </div>
        </div>
      </div>
      <div v-else>
        <ValidationObserver v-slot="{}" ref="customerform">
          <form class="px-3" @submit.prevent="updateCustomer">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <ValidationProvider
                  vid="name"
                  name="Full Name"
                  rules="required|min:6"
                  v-slot="{ errors }"
                >
                  <b-field
                    label="Full Name"
                    label-position="inside"
                    :type="{ 'is-danger': errors[0] }"
                    :message="errors[0]"
                  >
                    <b-input v-model="fields.name" placeholder="Full Name" />
                  </b-field>
                </ValidationProvider>
              </div>
              <div>
                <ValidationProvider
                  vid="phone"
                  name="Phone Number"
                  rules="required|phnumber"
                  v-slot="{ errors }"
                >
                  <b-field
                    label="Phone Number"
                    label-position="inside"
                    :type="{ 'is-danger': errors[0] }"
                    :message="errors[0]"
                  >
                    <b-input
                      v-model="fields.mobile_no"
                      placeholder="Phone Number"
                    />
                  </b-field>
                </ValidationProvider>
              </div>
            </div>
            <!-- <div class="grid grid-cols-1 md:grid-cols-2 gap-6"> -->
              <div >
              <!-- <div>
                <ValidationProvider
                  vid="date_of_birth"
                  name="Date of birth"
                  rules="required_if:date_of_birth|bith_date"
                  v-slot="{ errors }"
                >
                  <b-field
                    label="Date of birth"
                    label-position="inside"
                    :type="{ 'is-danger': errors[0] }"
                    :message="errors[0]"
                  >
                    <b-datepicker
                    v-model="fields.birth_date"
                      placeholder="click to select"
                      :max-date="new Date()"
                    >
                    </b-datepicker>
                  </b-field>
                </ValidationProvider>
              </div> -->
              <div>
                <ValidationProvider
                  vid="group"
                  name="Customer Group"
                  rules="required"
                  v-slot="{ errors }"
                >
                  <b-field
                    label="Customer Group"
                    label-position="inside"
                    :type="{ 'is-danger': errors[0] }"
                    :message="errors[0]"
                  >
                    <b-select
                      placeholder="Click Here"
                      v-model="fields.group"
                      expanded
                    >
                      <option value="vip">VIP</option>
                      <option value="regular">Regular</option>
                    </b-select>
                  </b-field>
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
        <vs-button transparent @click="updateCustomer"
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
    customer_id: Number,
  },

  data() {
    return {
      loading: true,
      isEdit: false,
      fields: {
        name: '',
        mobile_no: '',
        birth_date: null,
        notes: '',
        group: '',
      },
    }
  },
  watch: {
    modal(value) {
      if (value) {
        this.getCustomer()
      }
    },
  },
  computed: {
    ...mapGetters({
      customer: 'customers/customer',
    }),
  },
  methods: {
    ...mapActions({
      show: 'customers/show',
      update: 'customers/update',
    }),
    async getCustomer() {
      try {
        this.loading = true
        await this.show(this.customer_id)
        this.loading = false
      } catch (error) {
        console.log(error)
      }
    },
    async updateCustomer() {
      try {
        this.loading = true
        const customerform = this.$refs.customerform
        customerform.validate().then(async (success) => {
          if (success) {
            const data = {
              id: this.customer_id,
              formData: {
                ...this.fields,
                birth_date: this.fields.birth_date ? this.$moment(this.fields.birth_date).format(
                  'YYYY-MM-DD hh:mm '
                ):"",
              },
            }
            await this.update(data)
            this.close()
            this.setToEdit()
            this.get()
          }
        })
        this.loading = false
      } catch (error) {
        this.loading = false
        this.close()
      }
    },
    setToEdit() {
      this.isEdit = !this.isEdit
      this.fields = { ...this.customer }
    },
  },
}
</script>

<style></style>
