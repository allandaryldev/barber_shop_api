<template>
  <vs-dialog v-model="modal" blur prevent-close not-close :loading="loading">
    <template #header>
      <PageTitle page_title="add customer" />
    </template>
    <div>
      <ValidationObserver v-slot="{}" ref="customerform">
        <form class="px-3" @submit.prevent="addCustomer">
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
                  <b-input
                    v-model="fields.name"
                    placeholder="Full Name"
                    class="capitalize"
                  />
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
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
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
                  position="is-top-right"
                >
                  <b-datepicker
                    v-model="fields.birth_date"
                    trap-focus
                    ref="datepicker"
                    placeholder="click to select"
                    :max-date="new Date()"
                    position="is-top-right"
                  >
                  </b-datepicker>
                </b-field>
              </ValidationProvider>
            </div>
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
    <template #footer>
      <div class="flex">
        <vs-button dark border @click="close">Close</vs-button>
        <vs-button
          success
          border
          @click="addCustomer"
          type="submit"
          :loading="loading"
          >Submit</vs-button
        >
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
  },
  data() {
    return {
      loading: false,
      fields: {
        name: '',
        mobile_no: '',
        birth_date: null,
        notes: '',
        group: '',
      },
    }
  },
  methods: {
    ...mapActions({
      store: 'customers/create',
    }),
    async addCustomer() {
      try {
        this.loading = true
        const customerform = this.$refs.customerform
        await customerform.validate().then(async (success) => {
          if (success) {
            const bday = this.fields.birth_date
              ? this.$moment(this.fields.birth_date).format('YYYY-MM-DD hh:mm ')
              : null
            const data = {
              ...this.fields,
              birth_date: bday,
            }
            await this.store(data)
            this.$vs.notification({
              title: 'Customer Added',
              text: 'New Customet Added',
              color: 'success',
            })
            this.close()
            this.resetForm()
            this.get()
          }
        })
        this.loading = false
      } catch (error) {
        this.loading = false
        if (error.response.status === 422) {
          this.$vs.notification({
            title: 'Form error',
            text: 'Mobile Number or Customer name Exist',
            color: 'danger',
          })
        }
      }
    },
    resetForm() {
      this.fields = {
        name: '',
        mobile_no: '',
        birth_date: null,
        notes: '',
        group: '',
      }
    },
  },
}
</script>

<style></style>
