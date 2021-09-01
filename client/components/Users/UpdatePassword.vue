<template>
  <vs-dialog v-model="modal" blur prevent-close not-close :loading="loading">
    <template #header>
      <h4 class="not-margin">Change User Password</h4>
    </template>

    <div>
      <ValidationObserver v-slot="{}" ref="passwordForm">
        <form class="" @submit.prevent="addUser">
          <div class="space-y-4">
            <div>
              <ValidationProvider
                vid="current_password"
                name="Current Password"
                rules="required|min:8"
                v-slot="{ errors }"
              >
                <b-field
                  label="Current Password"
                  label-position="inside"
                  :type="{ 'is-danger': errors[0] }"
                  :message="errors[0]"
                >
                  <b-input
                    type="password"
                    v-model="fields.current_password"
                    password-reveal
                  >
                  </b-input>
                </b-field>
              </ValidationProvider>
            </div>
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
                  <b-input
                    type="password"
                    v-model="fields.password_confirmation"
                    password-reveal
                  >
                  </b-input>
                </b-field>
              </ValidationProvider>
            </div>
          </div>
        </form>
      </ValidationObserver>
    </div>

    <template #footer>
      <div class="con-footer flex">
        <vs-button @click="close"> Close </vs-button>
        <vs-button @click="changePassword" border danger> Change </vs-button>
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
  },
  data() {
    return {
      loading: false,
      fields: {
        current_password: '',
        password: '',
        password_confirmation: '',
      },
    }
  },
  methods: {
    ...mapActions({
      change_password: 'users/changepassword',
    }),
    async changePassword() {
      try {
        const passwordForm = this.$refs.passwordForm
        this.loading = true
        await passwordForm.validate().then(async (success) => {
          if (success) {
            const data = { ...this.fields, id: this.$auth.$state.user.id }
            await this.change_password(data)
            this.$vs.notification({
              title: 'Password changed',
              text: 'Successsfully changed your password',
              color: 'success',
            });
            this.close();
          }
        })
        this.loading = false
      } catch (error) {
        this.loading = false
        if (error.response.status === 422) {
          console.log(error.response.data.errors)
          this.$vs.notification({
            title: 'Password Error',
            text: error.response.data.errors.current_password? error.response.data.errors.current_password :"Request failed",
            color: 'danger',
          })
        }
      }
    },
  },
}
</script>

<style></style>
