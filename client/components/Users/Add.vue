<template>
  <vs-dialog v-model="modal" blur prevent-close not-close :loading="loading">
    <template #header>
      <PageTitle page_title="add user" />
    </template>

    <div>
      <ValidationObserver v-slot="{}" ref="usersForm">
        <form class="" @submit.prevent="addUser">
          <div class="space-y-4">
            <div class="grid grid-cols-2 gap-6">
              <div>
                <ValidationProvider
                  vid="fname"
                  name="Firstname"
                  rules="required"
                  v-slot="{ errors }"
                >
                  <b-field
                    label="Firstname"
                    label-position="inside"
                    :type="{ 'is-danger': errors[0] }"
                    :message="errors[0]"
                  >
                    <b-input
                      v-model="fields.firstname"
                      placeholder=""
                      class=""
                      expanded
                    />
                  </b-field>
                </ValidationProvider>
              </div>
              <div>
                <ValidationProvider
                  vid="lname"
                  name="Lastname"
                  rules="required"
                  v-slot="{ errors }"
                >
                  <b-field
                    label="Lastname"
                    label-position="inside"
                    :type="{ 'is-danger': errors[0] }"
                    :message="errors[0]"
                  >
                    <b-input
                      v-model="fields.lastname"
                      placeholder=""
                      class=""
                      expanded
                    />
                  </b-field>
                </ValidationProvider>
              </div>
            </div>
            <div class="grid grid-cols-2 gap-6">
              <div>
                <ValidationProvider
                  vid="uname"
                  name="Username"
                  rules="required"
                  v-slot="{ errors }"
                >
                  <b-field
                    label="Username"
                    label-position="inside"
                    :type="{ 'is-danger': errors[0] }"
                    :message="errors[0]"
                  >
                    <b-input
                      v-model="fields.username"
                      placeholder=""
                      class=""
                      expanded
                    />
                  </b-field>
                </ValidationProvider>
              </div>
              <div>
                <ValidationProvider
                  vid="email"
                  name="Email"
                  rules="email"
                  v-slot="{ errors }"
                >
                  <b-field
                    label="Email"
                    label-position="inside"
                    :type="{ 'is-danger': errors[0] }"
                    :message="errors[0]"
                  >
                    <b-input
                      v-model="fields.email"
                      type="email"
                      placeholder=""
                      class=""
                      expanded
                    />
                  </b-field>
                </ValidationProvider>
              </div>
            </div>
            <div class="grid grid-cols-2 gap-6">
              <div>
                <ValidationProvider
                  vid="type"
                  name="User Type"
                  rules="required"
                  v-slot="{ errors }"
                >
                  <b-field
                    label="User Type"
                    label-position="inside"
                    :type="{ 'is-danger': errors[0] }"
                    :message="errors[0]"
                  >
                    <b-select
                      placeholder="Click Here"
                      v-model="fields.type"
                      expanded
                    >
                      <option value="staff">Staff</option>
                      <option value="admin">Admin</option>
                    </b-select>
                  </b-field>
                </ValidationProvider>
              </div>
              <div>
                <ValidationProvider
                  vid="status"
                  name="User Status"
                  rules="required"
                  v-slot="{ errors }"
                >
                  <b-field
                    label="User Status"
                    label-position="inside"
                    :type="{ 'is-danger': errors[0] }"
                    :message="errors[0]"
                  >
                    <b-select v-model="fields.status" expanded>
                      <option value="active">Active</option>
                      <option value="deactivated">Deactivated</option>
                    </b-select>
                  </b-field>
                </ValidationProvider>
              </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
              <div>
                <ValidationProvider
                  vid="password"
                  name="Password"
                  rules="required|min:8|confirmed:password_confirmation"
                  v-slot="{ errors }"
                >
                  <b-field
                    label="Password"
                    label-position="inside"
                    :type="{ 'is-danger': errors[0] }"
                    :message="errors[0]"
                  >
                    <b-input
                      type="password"
                      v-model="fields.password"
                      password-reveal
                    >
                    </b-input>
                  </b-field>
                </ValidationProvider>
              </div>
              <div>
                <ValidationProvider
                  name="Password confirmation"
                  vid="password_confirmation"
                  rules="required|min:8"
                  v-slot="{ errors }"
                >
                  <b-field
                    label="Password confirmation"
                    label-position="inside"
                    :type="{ 'is-danger': errors[0] }"
                    :message="errors[0]"
                  >
                    <b-input type="password" v-model="fields.password_confirmation" password-reveal>
                    </b-input>
                  </b-field>
                </ValidationProvider>
              </div>
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
          @click="addUser"
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
        firstname: '',
        lastname: '',
        email: '',
        type: 'staff',
        status: 'active',
        password: '',
        password_confirmation: '',
      },
    }
  },
  methods: {
    ...mapActions({
      store: 'users/create',
    }),
    async addUser() {
      try {
        this.loading = true
        const usersForm = this.$refs.usersForm
        await usersForm.validate().then(async (success) => {
          if (success) {
            const data = { ...this.fields };
            await this.store(data)
            this.$vs.notification({
              title: 'User Added',
              text: 'New User Added',
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
