<template>
  <!-- <div class="bg-gradient-to-b from-gray-500 via-gray-700 to-gray-900"> -->
  <div>
    <div class="grid place-items-center h-screen">
      <div>
        <TabView :activeIndex="active">
          <TabPanel header="Log In">
            <div class="p-tabview-panel">
              <v-card
                class="rouded-xl w-96"
                :loading="loading"
                shaped
                elevation="10"
              >
                <template slot="progress">
                  <v-progress-linear
                    color="deep-purple"
                    height="10"
                    indeterminate
                  ></v-progress-linear>
                </template>
                <v-card-title primary-title>
                  <div>
                    <h3 class="headline mb-0 font-bold">Welcome back!</h3>
                    <div class="font-normal">Login please</div>
                  </div>
                </v-card-title>
                <v-card-text>
                  <div>
                    <ValidationObserver v-slot="{}" ref="loginform">
                      <form class="px-3" @submit.prevent="login">
                        <div class="space-y-4">
                          <div>
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
                                <b-input
                                  v-model="username"
                                  placeholder="Username"
                                />
                              </b-field>
                            </ValidationProvider>
                          </div>
                          <div>
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
                                <b-input
                                  type="password"
                                  v-model="password"
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
                </v-card-text>
                <v-card-actions>
                  <vs-button border @click="login" type="submit" :loading="loading"
                    >Login</vs-button
                  >
                </v-card-actions>
              </v-card>
            </div>
          </TabPanel>
          <TabPanel header="Create Account">
            <div class="p-tabview-panel">
              <v-card
                class="rouded-xl w-full"
                :loading="loading"
                shaped
                elevation="10"
              >
                <template slot="progress">
                  <v-progress-linear
                    color="deep-purple"
                    height="10"
                    indeterminate
                  ></v-progress-linear>
                </template>
                <v-card-title primary-title>
                  <div>
                    <h3 class="headline mb-0 font-bold"></h3>
                    <div class="font-normal">Register please</div>
                  </div>
                </v-card-title>
                <v-card-text>
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
                                rules="required|min:6"
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
                          <!-- <div class="grid grid-cols-2 gap-6">
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
                                    <option value="deactivated">
                                      Deactivated
                                    </option>
                                  </b-select>
                                </b-field>
                              </ValidationProvider>
                            </div>
                          </div> -->

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
                        </div>
                      </form>
                    </ValidationObserver>
                  </div>
                </v-card-text>
                <v-card-actions>
                  <vs-button border @click="addUser" type="submit" :loading="loading"
                    >Submit</vs-button
                  >
                </v-card-actions>
              </v-card>
            </div>
          </TabPanel>
        </TabView>
      </div>
    </div>
  </div>
</template>

<script>
import {mapActions} from 'vuex'
export default {
  layout: 'authlayout',
  data() {
    return {
      active: 0,
      loading: false,
      username: '',
      password: '',
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
      register: 'users/register',
    }),
    async addUser() {
      try {
        this.loading = true
        const usersForm = this.$refs.usersForm
        await usersForm.validate().then(async (success) => {
          if (success) {
            const data = { ...this.fields }
            await this.register(data)
            await this.$vs.notification({
              title: 'New User Added',
              text: 'Please Login',
              color: 'success',
            })
            this.active = 0;
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
                this.$router.push({ name: 'app-transactions' })
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
