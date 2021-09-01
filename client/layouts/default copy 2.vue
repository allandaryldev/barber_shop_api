<template>
  <v-app>
    <v-navigation-drawer
      v-model="drawer"
      :mini-variant="miniVariant"
      :clipped="clipped"
      fixed
      app
    >
      <div class="px-2">
        <SidePanelProfile />
      </div>
      <v-list>
        <v-list-item
          v-for="(item, i) in items"
          :key="i"
          :to="item.to"
          router
          exact
        >
          <v-list-item-action>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title v-text="item.title" />
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
    <!-- <v-app-bar
      :clipped-left="clipped"
      fixed
      app
      dark
    >
      <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
      <v-btn
        icon
        @click.stop="miniVariant = !miniVariant"
      >
        <v-icon>mdi-{{ `chevron-${miniVariant ? 'right' : 'left'}` }}</v-icon>
      </v-btn>
      <v-btn
        icon
        @click.stop="clipped = !clipped"
      >
        <v-icon>mdi-application</v-icon>
      </v-btn>
      <v-btn
        icon
        @click.stop="fixed = !fixed"
      >
        <v-icon>mdi-minus</v-icon>
      </v-btn>
      <v-toolbar-title v-text="title" />
      <v-spacer />
    </v-app-bar>
    <v-main>
      <v-container>
        <Nuxt />
      </v-container>
    </v-main> -->
    <v-main style="background-color: #f4f5fa !important; overflow-y: hidden">
      <div id="v_app_bar" class="fixed z-50">
        <v-app-bar
          elevate-on-scroll
          scroll-target="#scrolling-techniques-7"
          fixed
          app
          dark
          class="bg-primary text-white backdrop-filter backdrop-blur"
        >
          <!-- <v-app-bar-nav-icon @click.stop="drawer = !drawer" /> -->
          <v-spacer />
          <OverlayPanel
            id="overlay_menu"
            appendTo="body"
            ref="menu"
            class="z-50 -p-2"
          >
            <div class="grid gap-2">
              <div
                class="
                  flex
                  items-center
                  px-4
                  cursor-pointer
                  border-b-1
                  hover:bg-whitelight
                "
              >
                <!-- @click="changepassmodal.active = true" -->
                <i class="pi pi-key text-xs mr-4"></i>
                <h3 class="text-sm">Password</h3>
              </div>
              <div
                class="
                  flex
                  items-center
                  px-4
                  cursor-pointer
                  border-b-1
                  hover:bg-whitelight
                "
                @click="dialog = true"
              >
                <i class="pi pi-power-off text-xs mr-4"></i>
                <h3 class="text-sm">Log out</h3>
              </div>
            </div>
          </OverlayPanel>

          <!-- @click="toggle" -->
          <v-btn small aria-haspopup="true" aria-controls="overlay_menu" icon>
            <v-icon>mdi-account</v-icon>
          </v-btn>
          <!-- <v-toolbar-title
            class="text-sm mr-1"
            v-if="this.$auth.user && this.$auth.user.name"
            v-text="this.$auth.user.name"
          /> -->
        </v-app-bar>
        <!-- <the-top-bar /> -->
      </div>
      <v-sheet
        id="scrolling-techniques-7"
        style="background-color: #f4f5fa !important"
        class="px-2 py-1"
      >
        <client-only>
          <transition name="scroll-x-transition" >
            <nuxt
              keep-alive
              :keep-alive-props="{
                include: ['Index'],
              }"
            />
          </transition>
          <!-- <application-list-modal />
          <agent-application-list-modal />
          <the-user-change-password-modal
            :modal="changepassmodal"
            :close="closeChangePassModal"
          /> -->
        </client-only>
      </v-sheet>
    </v-main>
  </v-app>
</template>

<script>
export default {
  data() {
    return {
      clipped: false,
      drawer: true,
      fixed: false,
      items: [
        {
          icon: 'mdi-apps',
          title: 'Customers',
          to: '/app/customers',
        },
        {
          icon: 'mdi-chart-bubble',
          title: 'Services',
          to: '/app/services',
        },
         {
          icon: 'mdi-chart-bubble',
          title: 'Transactions',
          to: '/app/transactions',
        },
      ],
      miniVariant: false,
      right: true,
      rightDrawer: false,
      title: 'Vuetify.js',
    }
  },
}
</script>
