<template>
  <div class="grid place-items-center h-full">
    <v-card class="w-1/2 rounded-lg">
      <ValidationObserver v-slot="{}" ref="loginform">
        <v-card-title primary-title class="">
          <div class="text-center">
            <h3 class="headline mb-0">Barbers</h3>
            <div>Shop</div>
          </div>
        </v-card-title>
        <!-- <form class="flex flex-col justify-center items-center"> -->
        <form class="px-3" @submit.prevent="login">
          <ValidationProvider
            vid="username"
            name="Username"
            rules="required|min:6"
            v-slot="{ errors }"
          >
            <b-field
              label="Username"
              label-position="inside"
              :type="{ 'is-danger': errors[0] }"
              :message="errors[0]"
            >
              <b-input v-model="username" placeholder="Username" />
            </b-field>
          </ValidationProvider>
          <ValidationProvider
            vid="password"
            name="Password"
            rules="required|min:6"
            v-slot="{ errors }"
          >
            <b-field
              label="Password"
              label-position="inside"
              :type="{ 'is-danger': errors[0] }"
              :message="errors[0]"
            >
              <b-input type="password" v-model="password" password-reveal>
              </b-input>
            </b-field>
          </ValidationProvider>
          <v-card-actions class="flex justify-center">
            <vs-button border type="submit" :loading="loading"
              >Submit</vs-button
            >
          </v-card-actions>
        </form>
      </ValidationObserver>
    </v-card>
  </div>
</template>

<script>
export default {
  layout: 'authlayout',
  data() {
    return {
      loading: false,
      username: '',
      password: '',
      rules: {
        required: (value) => !!value || 'Required.',
        counter: (value) => value.length <= 20 || 'Max 20 characters',
        min: (value) => value.length >= 8 || 'Min 8 characters',
      },
    }
  },
  methods: {
    async login() {
      let form = this.$refs.loginform
      this.loading = true
      await form
        .validate()
        .then(async (success) => {
          if (success) {
            const data = {
              username: this.username,
              password: this.password,
            }
            await this.$auth
              .loginWith('laravelSanctum', { data })
              .then((response) => {
                // this.$toast.success('Logged In')
                this.$vs.notification({
                  title: 'Login Success',
                  text: 'Redirecting',
                  color: 'success',
                })
                this.$router.push({ name: 'app-dashboard' })
              })
              .catch((error) => {
                // this.$toast.error('Login Failed')
                this.$vs.notification({
                  title: 'Login Failed',
                  text: error.response.data.errors
                    ? error.response.data.errors.username[0]
                    : 'Invalid Credentials',
                  color: 'danger',
                })
              })
              .finally(() => {
                this.loading = false
              })
          }
        })
        .catch((error) => {
          console.log(error)
        })
      this.loading = false
    },
  },
}
</script>

<style></style>
