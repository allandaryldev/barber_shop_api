<template>
  <vs-dialog
    v-model="modal"
    blur
    prevent-close
    not-close
    :loading="loading"
    width="100"
  >
    <template #header>
      <PageTitle page_title="User" />
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

          </div>
        </form>
      </ValidationObserver>
    </div>

    <template #footer>
      <div class="flex" >
        <vs-button success border :loading="loading" @click="close"
          >Close</vs-button
        >
        <b-tooltip
          class="d-flex"
          type="is-dark"
          label="Edit User"
          position="is-right"
        >
        <vs-button danger border :loading="loading"  @click="updateUser">
          <!-- <box-icon name='send' type='solid'  color=" rgba(239, 68, 68) "  animation="tada-hover"></box-icon> -->
          Submit
        </vs-button>
        </b-tooltip>
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
    user_id: Number,
  },
  data() {
    return {
      loading: false,
      isEdit: false,
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
  computed: {
    ...mapGetters({
      user: 'users/user',
    }),
  },
  watch: {
    modal(value) {
      if (value) {
        this.getUser()
      }
    },
  },
  methods: {
    ...mapActions({
      show: 'users/show',
      update: 'users/update',
    }),
    async getUser() {
      try {
        this.loading = true
        await this.show(this.user_id)
        this.fields = await { ...this.user }
        this.loading = false
      } catch (error) {
        this.loading = false
      }
    },
    async updateUser() {
      try {
        this.loading = true
        const userForm = this.$refs.usersForm
        await userForm.validate().then(async (success) => {
          if (success) {
            const data = { id: this.user_id, formData: { ...this.fields } }
            await this.update(data)
            await this.$vs.notification({
              title:"User Update",
              text:"Updated Sucessfully",
              color:"success"
            });
            this.close()
            this.get()
            this.setToEdit()
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
    confirm2() {
      this.$buefy.dialog.confirm({
        message: 'Continue on this task?',
        onConfirm: () => this.$buefy.toast.open('User confirmed'),
      })
    },
  },
}
</script>

<style></style>
