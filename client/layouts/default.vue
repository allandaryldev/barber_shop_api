<template>
  <v-app class="bg-gradient-to-b from-gray-500 via-gray-700 to-gray-900">
    <div class="z-50 mb-20" id="v_app_bar ">
      <vs-navbar
        shadow
        square
        fixed
        center-collapsed
        v-model="active"
        color="dark"
        text-white
      >
        <template #left>
          <!-- <vs-button @click="activeSidebar = !activeSidebar" flat icon>
            <i class='bx bx-menu'></i>
          </vs-button> -->
        </template>
        <div v-for="(item, index) in items" :key="index">
          <vs-navbar-item
            :active="active == item.name"
            :id="item.name"
            :to="item.to"
          >
            {{ item.title }}
          </vs-navbar-item>
        </div>
        <template #right>
          <vs-button @click="logout" warn border>Log out</vs-button>
          <vs-button @click="handleChangePassModal">Change Password</vs-button>
        </template>
      </vs-navbar>
    </div>
    <v-main id="padding-scroll-content " class="">
      <v-sheet id="scrolling-techniques-7" class="px-10 py-1">
        <client-only>
          <transition name="scroll-x-transition">
            <nuxt
              keep-alive
              :keep-alive-props="{
                include: ['Index'],
              }"
            />
          </transition>
        </client-only>
      </v-sheet>
    </v-main>
    <UsersUpdatePassword
      :modal="changepassModal"
      :close="handleChangePassModal"
    />
  </v-app>
</template>

<script>
export default {
  data() {
    return {
      active: 'app-dashboard',
      activeSidebar: false,
      clipped: false,
      drawer: true,
      fixed: false,
      items: [
        {
          icon: 'mdi-apps',
          title: 'Dashboard',
          to: '/app/dashboard',
          name: 'app-dashboard',
        },
        {
          icon: 'mdi-chart-bubble',
          title: 'Transactions',
          to: '/app/transactions',
          name: 'app-transactions',
        },
        {
          icon: 'mdi-apps',
          title: 'Customers',
          to: '/app/customers',
          name: 'app-customers',
        },
        {
          icon: 'mdi-chart-bubble',
          title: 'Services',
          to: '/app/services',
          name: 'app-services',
        },
        {
          icon: 'mdi-chart-bubble',
          title: 'Users',
          to: '/app/users',
          name: 'app-users',
        },
      ],
      miniVariant: false,
      right: true,
      rightDrawer: false,
      title: 'Vuetify.js',
      changepassModal:false,
    }
  },
  watch: {
    // $route: {
    //   handler(value) {
    //     this.active = value.name
    //     // this.$axios.get(`api/abilities`).then(response => {
    //     //   this.$ability.update([
    //     //     { subject: "all", action: response.data.data }
    //     //   ]);
    //     // });
    //   },
    //   immediate: true
    // },
    // active(value){
    // },
  },
  mounted() {
    this.active = this.$route.name
  },
  methods: {
    handleChangePassModal() {
      this.changepassModal = !this.changepassModal
    },
    async logout() {
      try {
        await this.$auth.logout()
        this.$toast.success('logged out')
      } catch (error) {}
    },
  },
}
</script>
